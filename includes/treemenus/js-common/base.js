/* *
 * base classes extensions
 * */

/* *
fix ecma compliance
 * */
if (!Function.prototype.apply) {
	Function.prototype.apply = function (o,a) {
		var r;
		if(!o){ o = {}; } // in case func.apply(null, arguments).
		o.___apply=this;
		switch((a && a.length) || 0) {
			case 0: r = o.___apply(); break;
			case 1: r = o.___apply(a[0]); break;
			case 2: r = o.___apply(a[0],a[1]); break;
			case 3: r = o.___apply(a[0],a[1],a[2]); break;
			case 4: r = o.___apply(a[0],a[1],a[2],a[3]); break;
			case 5: r = o.___apply(a[0],a[1],a[2],a[3],a[4]); break;
			case 6: r = o.___apply(a[0],a[1],a[2],a[3],a[4],a[5]); break;
			default: 
				for(var i=0, s=""; i<a.length;i++){
					if(i!=0){ s += ","; }
					s += "a[" + i +"]";
				}
				r = eval("o.___apply(" + s + ")");
		}
		o.__apply = null;
		return r;
	}
}

if (!Function.prototype.call) {
	Function.prototype.call = function(o) {
		// copy arguments and use apply
		var args = new Array(arguments.length - 1);
		for(var i=1;i<arguments.length;i++){
			args[i - 1] = arguments[i];
		}
		return this.apply(o, args);
	}
}

if (!Array.prototype.push) {
	Array.prototype.push = function() {
		for (var i=0; i<arguments.length; i++) {
			this[this.length] = arguments[i];
		}
		return this.length;
	}
}

if (!Array.prototype.pop) {
	Array.prototype.pop = function() {
		if(this.length == 0){ 
			try{
				return undefined; 
			} catch(e) {
				return null;
			}
		}
		return this[this.length--];
	}
}
if (!Array.prototype.shift) {
	Array.prototype.shift = function() {
		this.reverse();
		var lastv = this.pop();
		this.reverse();
		return lastv;
	}
}
// this splice works differently than the one provided with browsers
// because it doesn't change the original array
if (!Array.prototype.splice) {
  Array.prototype.splice = function(start, deleteCount) {
    var len = parseInt(this.length);

    start = start ? parseInt(start) : 0;
    start = (start < 0) ? Math.max(start+len,0) : Math.min(len,start);

    deleteCount = deleteCount ? parseInt(deleteCount) : 0;
    deleteCount = Math.min(Math.max(parseInt(deleteCount),0), len);

    var deleted = this.slice(start, start+deleteCount);

    var insertCount = Math.max(arguments.length - 2,0);
    // new len, 1 more than last destination index
    var new_len = this.length + insertCount - deleteCount;
    var start_slide = start + insertCount;
    var nslide = len - start_slide; // (this.length - deleteCount) - start
    // slide up
    for(var i=new_len - 1;i>=start_slide;--i){
		this[i] = this[i - nslide];
	}
    // copy inserted elements
    for(i=start;i<start+insertCount;++i){
		this[i] = arguments[i-start+2];
	}
    return deleted;
  }
}

/* Object extensions */
// .toArray
Object.prototype.toArray = function(delim) {
	var result;
	if (typeof(delim) == 'undefined') {
		delim = ',';
	}
	switch(typeof(this)) {
		case 'array':
			result = this;
			break;
		case 'string':
			if (this.indexOf(delim)) {
				result = this.split(delim);
			} else {
				result.push(this);
			}
			break;
		default:
			result.push(this);
			break;
	}
}

/* Array extensions */
// .indexOf : behaves exactly as String#indexOf
Array.prototype.indexOf = function(x) {
	for (var i=0; i<this.length; i++) {
		if (this[i] == x) {
			return i;
		}
	}
	return -1;
}
// .lastIndexOf : behaves exactly as String#lastIndexOf
Array.prototype.lastIndexOf = function(x) {
	for (var i=this.length-1; i>=0; i--) {
		if (this[i] == x) {
			return i;
		}
	}
	return -1;
}
Array.prototype.last = function() {
	if (this.length > 0) {
		return this[this.length - 1];
	}
}


/* String extensions */
// .trim : trim whitespace at beginning and end of a string
String.prototype.trim = function(str) {
	if (!str) str = this;
	return str.replace(/^\s*/, "").replace(/\s*$/, "");
}

// .normalize_space : return string with extra whitespace removed
String.prototype.normalize_space = function(str) {
	if (!str) str = this;
	return str.trim().replace(/\s+/g, " ");
}

/* from ruby.js */
Array.prototype.each = function(block) {
	for (var index = 0; index < this.length; ++index) {
		var item = this[index]
		block(item, index)
	}
	return(this)
}

Number.prototype.times = function(block) {
	for (var i = 0; i < this; i++) block(i)
}
 
/* helpers */
// min for array, string and as function
//  [3, 2, 4].min() => 2
Array.prototype.min = function() {
	if (this.length == 0) return false;
	if (this.length == 1) return this[0];
	var min, me, val;
	min = 0;
	me = this;
	me.each(function(val, i) {
		if (val < me[min]) {
			min = i;
		}
	});
	return this[min];
}
// "3,2,4".min() => 2
String.prototype.min = function() {
	return this.split(',').min();
}
// min(3, 2, 4) => 2
function min() {
	arguments.each = Array.prototype.each;
	var a = [];
	arguments.each(function(val, i) {
		a.push(val);
	});
	return a.min();
}

// max for array, string and as function
//  [3, 2, 4].max() => 4
Array.prototype.max = function() {
	if (this.length == 0) return false;
	if (this.length == 1) return this[0];
	var max, me, val;
	max = 0;
	me = this;
	me.each(function(val, i) {
		if (val > me[max]) {
			max = i;
		}
	});
	return this[max];
}
// "3,2,4".max() => 4
String.prototype.max = function() {
	return this.split(',').max();
}
// max(3, 2, 4) => 4
function max() {
	arguments.each = Array.prototype.each;
	var a = [];
	arguments.each(function(val, i) {
		a.push(val);
	});
	return a.max();
}

Number.prototype.times = function(block) {
	for (var i = 0; i < this; i++) block(i)
}
 


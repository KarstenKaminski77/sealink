<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:wdg="http://ns.adobe.com/addt">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="../includes/skins/mxkollection3.css" rel="stylesheet" type="text/css" media="all" />

<style type="text/css">
* {
  -webkit-box-sizing: border-box;
  -moz-box-sizing: border-box;
  box-sizing: border-box;
}

body {
  padding: 20px 15%;
}
form header {
  margin: 0 0 20px 0; 
}
form header div {
  font-size: 90%;
  color: #999;
}
form header h2 {
  margin: 0 0 5px 0;
}
form > div {
  clear: both;
  overflow: hidden;
  padding: 1px;
  margin: 0 0 10px 0;
}
form > div > fieldset > div > div {
  margin: 0 0 5px 0;
}
form > div > label,
legend {
	width: 25%;
  float: left;
  padding-right: 10px;
}
form > div > div,
form > div > fieldset > div {
  width: 75%;
  float: right;
}
form > div > fieldset label {
	font-size: 90%;
}
fieldset {
	border: 0;
  padding: 0;
}

input[type=text],
input[type=email],
input[type=url],
input[type=password],
textarea {
	width: 100%;
  border-top: 1px solid #ccc;
  border-left: 1px solid #ccc;
  border-right: 1px solid #eee;
  border-bottom: 1px solid #eee;
}
input[type=text],
input[type=email],
input[type=url],
input[type=password] {
  width: 50%;
}
input[type=text]:focus,
input[type=email]:focus,
input[type=url]:focus,
input[type=password]:focus,
textarea:focus {
  outline: 0;
  border-color: #4697e4;
}

@media (max-width: 600px) {
  form > div {
    margin: 0 0 15px 0; 
  }
  form > div > label,
  legend {
	  width: 100%;
    float: none;
    margin: 0 0 5px 0;
  }
  form > div > div,
  form > div > fieldset > div {
    width: 100%;
    float: none;
  }
  input[type=text],
  input[type=email],
  input[type=url],
  input[type=password],
  textarea,
  select {
    width: 100%; 
  }
}
@media (min-width: 1200px) {
  form > div > label,
	legend {
  	text-align: right;
  }
}
</style>
</head>

<body>
<form action="#">

  <header>
    <h2>Example Responsive Form</h2>
    <div>This form breaks at 600px and goes from a left-label form to a top-label form. At above 1200px, the labels align right.</div>
  </header>
  
  <div>
    <label class="desc" id="title1" for="Field1">Full Name</label>
    <div>
      <input id="Field1" name="Field1" type="text" class="field text fn" value="" size="8" tabindex="1">
    </div>
  </div>
    
  <div>
    <label class="desc" id="title3" for="Field3">
      Email
    </label>
    <div>
      <input id="Field3" name="Field3" type="email" spellcheck="false" value="" maxlength="255" tabindex="3"> 
   </div>
  </div>
    
  <div>
    <label class="desc" id="title4" for="Field4">
      Message
    </label>
  
    <div>
      <textarea id="Field4" name="Field4" spellcheck="true" rows="10" cols="50" tabindex="4"></textarea>
    </div>
  </div>
    
  <div>
    <fieldset>
    
      <legend id="title5" class="desc">
        Select a Choice
      </legend>
      
      <div>
      	<input id="radioDefault_5" name="Field5" type="hidden" value="">
      	<div>
      		<input id="Field5_0" name="Field5" type="radio" value="First Choice" tabindex="5" checked="checked">
      		<label class="choice" for="Field5_0">First Choice</label>
      	</div>
        <div>
        	<input id="Field5_1" name="Field5" type="radio" value="Second Choice" tabindex="6">
        	<label class="choice" for="Field5_1">Second Choice</label>
        </div>
        <div>
        	<input id="Field5_2" name="Field5" type="radio" value="Third Choice" tabindex="7">
        	<label class="choice" for="Field5_2">Third Choice</label>
        </div>
      </div>
    </fieldset>
  </div>
  
  <div>
    <fieldset>
      <legend id="title6" class="desc">
        Check All That Apply
      </legend>
      <div>
      <div>
      	<input id="Field6" name="Field6" type="checkbox" value="First Choice" tabindex="8">
      	<label class="choice" for="Field6">First Choice</label>
      </div>
      <div>
      	<input id="Field7" name="Field7" type="checkbox" value="Second Choice" tabindex="9">
      	<label class="choice" for="Field7">Second Choice</label>
      </div>
      <div>
      	<input id="Field8" name="Field8" type="checkbox" value="Third Choice" tabindex="10">
      	<label class="choice" for="Field8">Third Choice</label>
      </span>
      </div>
    </fieldset>
  </div>
  
  <div>
    <label class="desc" id="title106" for="Field106">
    	Select a Choice
    </label>
    <div>
    <select id="Field106" name="Field106" class="field select medium" tabindex="11"> 
      <option value="First Choice">First Choice</option>
      <option value="Second Choice">Second Choice</option>
      <option value="Third Choice">Third Choice</option>
    </select>
    </div>
  </div>
  
  <div>
		<div>
  		<input id="saveForm" name="saveForm" type="submit" value="Submit">
    </div>
	</div>
  
</form>
</body>
</html>
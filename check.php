<html>
  <head>
    <script language='JavaScript'>
      checked = false;
      function checkedAll () {
        if (checked == false){checked = true}else{checked = false}
	for (var i = 0; i < document.getElementById('myform').elements.length; i++) {
	  document.getElementById('myform').elements[i].checked = checked;
	}
      }
    </script>
  </head>
  <body>
    <form id="myform">
      <input type="checkbox" name="foo"/>
      <input type="checkbox" name="bar"/>
      <BR>Check all: <input type='checkbox' name='checkall' onclick='checkedAll();'>
    </form>
  </body>
</html>
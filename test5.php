<?php
	// Remove white spaces and put user invoice numbers into array
	if(!empty($_POST['inv-no'])){
		
		$pieces = explode(',', preg_replace('/\s/', '', strip_tags($_POST['inv-no'])));
	
		$inv_array = array();
		
		for($i=0;$i<count($pieces);$i++){
		
			array_push($inv_array, $pieces[$i]);
		}
		
	} else {
		
		$inv_array = $_POST['checkbox'];
		
	}
	
	for($i=0;$i<count($inv_array);$i++){
		
		echo $inv_array[$i] .'<br>';
	}

?>
<form id="form1" name="form1" method="post" action="">
  <textarea name="inv-no" id="inv-no" cols="45" rows="5"></textarea>
  <br />
  <input name="checkbox[]" type="checkbox" id="checkbox[]" value="1" />
  <input name="checkbox[]" type="checkbox" id="checkbox[]" value="2" />
  <input name="checkbox[]" type="checkbox" id="checkbox[]" value="3" />
  <input name="checkbox[]" type="checkbox" id="checkbox[]" value="4" />
  <input name="checkbox[]" type="checkbox" id="checkbox[]" value="5" />
  <br />
  <br />
  <br />
  <input type="submit" name="button" id="button" value="Submit" />
</form>
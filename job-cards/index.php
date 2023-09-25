<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>

  <script type="text/javascript" src="lib/jquery-2.0.3.js"></script>
  <script type="text/javascript" src="jquery.countdownTimer.js"></script>
  <link rel="stylesheet" type="text/css" href="css/jquery.countdownTimer.css" />

</head>

<body>

  <span id="given_date"></span>
  
  <script>
      $(function(){
          $('#given_date').countdowntimer({
              startDate : <?php echo '"'.date('Y-m-d H:i:s').'"'; ?>,
              dateAndTime : "2016-02-11 00:00:00",
              size : "lg"
          });
      });
  </script>
 
</body>
</html>
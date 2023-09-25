<style>
/* $activeColor: #c0392b; //red */
/* $background: url('https://s3-us-west-2.amazonaws.com/s.cdpn.io/13460/dark_wall.png'); */
/* .slideOne */

/* end .slideTwo */
/* .slideThree */
.slideThree {
  width: 80px;
  height: 26px;
  background: #333;
  margin: 20px auto;
  position: relative;
  border-radius: 50px;
  box-shadow: inset 0px 1px 1px rgba(0, 0, 0, 0.5), 0px 1px 0px rgba(255, 255, 255, 0.2);
}
.slideThree:after {
  content: 'REPORT';
  color: #FF0000;
  position: absolute;
  right: 10px;
  z-index: 0;
  font: 12px/26px Arial, sans-serif;
  font-weight: bold;
  text-shadow: 1px 1px 0px rgba(255, 255, 255, 0.15);
}
.slideThree:before {
  content: 'OK';
  color: #27ae60;
  position: absolute;
  left: 10px;
  z-index: 0;
  font: 12px/26px Arial, sans-serif;
  font-weight: bold;
}
.slideThree label {
  display: block;
  width: 34px;
  height: 20px;
  cursor: pointer;
  position: absolute;
  top: 3px;
  left: 3px;
  z-index: 1;
  background: #fcfff4;
  background: -webkit-linear-gradient(top, #fcfff4 0%, #dfe5d7 40%, #b3bead 100%);
  background: linear-gradient(to bottom, #fcfff4 0%, #dfe5d7 40%, #b3bead 100%);
  border-radius: 50px;
  -webkit-transition: all 0.4s ease;
  transition: all 0.4s ease;
  box-shadow: 0px 2px 5px 0px rgba(0, 0, 0, 0.3);
}
.slideThree input[type=checkbox] {
  visibility: hidden;
}
.slideThree input[type=checkbox]:checked + label {
  left: 43px;
}

</style>
<h1>CSS3 Checkbox Styles</h1>
<em>Click any button below</em>


<div class="ondisplay">
    
  <section title=".slideThree">
    <!-- .slideThree -->
    <div class="slideThree">  
    <form method="post">
      <input type="checkbox" value="None" id="slideThree" name="check" checked />
      <label for="slideThree"></label>
      <br />
      <input type="submit" name="button" id="button" value="Submit" />
    </form>
    </div>
    <!-- end .slideThree -->
  </section>
    
</div>

<?php echo $_POST['check']; ?>
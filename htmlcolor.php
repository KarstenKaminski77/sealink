<?php

class PDF_HTMLColor extends FPDF
{
function HTML2RGB($c, &$r, &$g, &$b)
{
	static $colors = array('black'=>'#000000','silver'=>'#C0C0C0','gray'=>'#808080','white'=>'#FFFFFF',
						'maroon'=>'#800000','red'=>'#FF0000','purple'=>'#800080','fuchsia'=>'#FF00FF',
						'green'=>'#008000','lime'=>'#00FF00','olive'=>'#808000','yellow'=>'#FFFF00',
						'navy'=>'#000080','blue'=>'#0000FF','teal'=>'#008080','aqua'=>'#00FFFF');

	$c=strtolower($c);
	if(isset($colors[$c]))
		$c=$colors[$c];
	if($c[0]!='#')
		$this->Error('Incorrect color: '.$c);
	$r=hexdec(substr($c,1,2));
	$g=hexdec(substr($c,3,2));
	$b=hexdec(substr($c,5,2));
}

function SetDrawColor($r, $g=-1, $b=-1)
{
	if(is_string($r))
		$this->HTML2RGB($r,$r,$g,$b);
	parent::SetDrawColor($r,$g,$b);
}

function SetFillColor($r, $g=-1, $b=-1)
{
	if(is_string($r))
		$this->HTML2RGB($r,$r,$g,$b);
	parent::SetFillColor($r,$g,$b);
}

function SetTextColor($r,$g=-1,$b=-1)
{
	if(is_string($r))
		$this->HTML2RGB($r,$r,$g,$b);
	parent::SetTextColor($r,$g,$b);
}
}
?>

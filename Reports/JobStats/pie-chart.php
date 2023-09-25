<?php
 /*
     Example10 : A 3D exploded pie graph
 */

 // Standard inclusions   
 include("../../pChart/pChart/pData.class");
 include("../../pChart/pChart/pChart.class");

 // Dataset definition 
 $DataSet = new pData;
 $DataSet->AddPoint(array(20,20,20,20,20),"Serie1");
 $DataSet->AddPoint(array("January R1099","February","March","April","May"),"Serie2");
 $DataSet->AddAllSeries();
 $DataSet->SetAbsciseLabelSerie("Serie2");

 // Initialise the graph
 $Test = new pChart(520,350);
 $Test->drawFilledRoundedRectangle(7,7,513,343,5,255,255,255);
 $Test->drawRoundedRectangle(5,5,415,245,5,255,255,255);
 $Test->createColorGradientPalette(0,76,127,25,163,255,5);

 // Draw the pie chart
 $Test->setFontProperties("../../pChart/Fonts/tahoma.ttf",8);
 $Test->AntialiasQuality = 0;
 $Test->drawPieGraph($DataSet->GetData(),$DataSet->GetDataDescription(),180,130,110,PIE_PERCENTAGE_LABEL,FALSE,50,20,5);
 $Test->drawPieLegend(330,15,$DataSet->GetData(),$DataSet->GetDataDescription(),255,255,255);

 // Write the title
 $Test->setFontProperties("../../pChart/Fonts/MankSans.ttf",12);
 $Test->drawTitle(13,23,"Sales per month",50,50,50);

 $Test->Render("pie-chart.png");
?>

<img src="pie-chart.png" />


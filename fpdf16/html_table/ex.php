<?php
require('html_table.php');

$pdf=new PDF();
$pdf->AddPage();
$pdf->SetFont('Arial','',12);

$html='<table border="0" width="100%">
<tr>
<td height="30"><b>cell 1</b></td><td width="50%" height="30" bgcolor="#D0D0FF">cell 2</td>
</tr>
<tr>
<td height="30">Lorem ipsum dolor sit amet, in vim congue nominavi instructior, qui putent deseruisse reprehendunt cu, idque disputando sit eu. Vivendum gloriatur ne per, mei assum saperet intellegam no, sed ex nullam accusamus. Has hinc atomorum ut, illum dicit copiosae eos in. An mollis disputationi nec. Per wisi principes ad.

Ad his viris dissentiunt ullamcorper, doming singulis argumentum cum te. Ius graecis mnesarchum consectetuer at, agam euripidis sit an. Eos ex sonet fuisset, in quot eruditi dissentiunt vix. No iudicabit repudiandae nec.

Agam numquam laoreet vix eu. An has justo temporibus, no mel quem torquatos moderatius. Ius ad eruditi placerat lucilius, novum exerci ullamcorper duo in. Animal delenit est ad.

Diam dicit qualisque duo ex. Eu erat feugait evertitur sit, mea cu ullum invidunt. Ad solum nonumy convenire vel, ei vel graeci adipisci, te tale mundi impedit ius. Habeo definitiones qui ei, molestie elaboraret ei ius. Ut est nibh mutat scribentur.</td><td width="50%" height="30">cell 4</td>
</tr>
</table>';

$pdf->WriteHTML($html);
$pdf->Output();
?>

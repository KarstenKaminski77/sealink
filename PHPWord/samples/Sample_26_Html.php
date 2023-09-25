<?php
include_once 'Sample_Header.php';

// New Word Document
echo date('H:i:s') , ' Create new PhpWord object' , EOL;
$phpWord = new \PhpOffice\PhpWord\PhpWord();

$section = $phpWord->addSection();
$html = '<table style="width: 531.9pt; border-collapse: collapse; border: none; mso-border-alt: solid windowtext .5pt; mso-yfti-tbllook: 480; mso-padding-alt: 0cm 5.4pt 0cm 5.4pt;">
<tbody>
<tr style="mso-yfti-irow: 0; mso-yfti-firstrow: yes;">
<td style="width: 239.4pt; border: solid windowtext 1.0pt; mso-border-alt: solid windowtext .5pt; padding: 0cm 5.4pt 0cm 5.4pt;">
<p style="margin-bottom: .0001pt; text-align: justify; line-height: normal;"><span style="font-size: 10.0pt; font-family: 'Arial','sans-serif'; mso-fareast-font-family: \'Times New Roman\'; mso-bidi-font-family: \'Times New Roman\';">Date Inspected:</span><span style="mso-bookmark: Text1;"><span style="font-size: 10.0pt; font-family: \'Arial\',\'sans-serif\'; mso-fareast-font-family: \'Times New Roman\'; mso-bidi-font-family: \'Times New Roman\'; mso-no-proof: yes;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></span></p>
</td>
<td style="width: 292.5pt; border: solid windowtext 1.0pt; border-left: none; mso-border-left-alt: solid windowtext .5pt; mso-border-alt: solid windowtext .5pt; padding: 0cm 5.4pt 0cm 5.4pt;">
<p style="margin-bottom: .0001pt; text-align: justify; line-height: normal;"><span style="font-size: 10.0pt; font-family: \'Arial\',\'sans-serif\'; mso-fareast-font-family: \'Times New Roman\'; mso-bidi-font-family: \'Times New Roman\';">Time: </span><span style="mso-bookmark: Text4;"><span style="font-size: 10.0pt; font-family: \'Arial\',\'sans-serif\'; mso-fareast-font-family: \'Times New Roman\'; mso-bidi-font-family: \'Times New Roman\'; mso-no-proof: yes;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></span></p>
</td>
</tr>
<tr style="mso-yfti-irow: 1;">
<td style="width: 531.9pt; border: solid windowtext 1.0pt; border-top: none; mso-border-top-alt: solid windowtext .5pt; mso-border-alt: solid windowtext .5pt; padding: 0cm 5.4pt 0cm 5.4pt;" colspan="2">
<p style="margin-bottom: .0001pt; text-align: justify; line-height: normal;"><span style="font-size: 10.0pt; font-family: \'Arial\',\'sans-serif\'; mso-fareast-font-family: \'Times New Roman\'; mso-bidi-font-family: \'Times New Roman\';">Location of Scaffold:</span><span style="mso-bookmark: Text2;"><span style="font-size: 10.0pt; font-family: \'Arial\',\'sans-serif\'; mso-fareast-font-family: \'Times New Roman\'; mso-bidi-font-family: \'Times New Roman\'; mso-no-proof: yes;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></span></p>
</td>
</tr>
<tr style="mso-yfti-irow: 2; mso-yfti-lastrow: yes;">
<td style="width: 531.9pt; border: solid windowtext 1.0pt; border-top: none; mso-border-top-alt: solid windowtext .5pt; mso-border-alt: solid windowtext .5pt; padding: 0cm 5.4pt 0cm 5.4pt;" colspan="2">
<p style="margin-bottom: .0001pt; text-align: justify; line-height: normal;"><span style="font-size: 10.0pt; font-family: \'Arial\',\'sans-serif\'; mso-fareast-font-family: \'Times New Roman\'; mso-bidi-font-family: \'Times New Roman\';">Inspected by (designated competent person):<span style="mso-spacerun: yes;">&nbsp; </span></span><span style="mso-bookmark: Text3;"><span style="font-size: 10.0pt; font-family: \'Arial\',\'sans-serif\'; mso-fareast-font-family: \'Times New Roman\'; mso-bidi-font-family: \'Times New Roman\'; mso-no-proof: yes;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></span></p>
</td>
</tr>
</tbody>
</table>
<p style="margin-bottom: .0001pt; line-height: normal; border: none; mso-border-bottom-alt: solid windowtext .5pt; padding: 0cm; mso-padding-alt: 0cm 0cm 1.0pt 0cm;"><strong style="mso-bidi-font-weight: normal;"><span style="font-size: 10.0pt; font-family: \'Arial\',\'sans-serif\'; mso-fareast-font-family: \'Times New Roman\'; mso-bidi-font-family: \'Times New Roman\';">&nbsp;</span></strong></p>
<p style="margin-bottom: .0001pt; line-height: normal;"><strong style="mso-bidi-font-weight: normal;"><span style="font-size: 7.0pt; font-family: \'Arial\',\'sans-serif\'; mso-fareast-font-family: \'Times New Roman\'; mso-bidi-font-family: \'Times New Roman\';">SCAFFOLD SAFETY INSPECTION CHECKLIST &ndash; </span></strong><span style="font-size: 7.0pt; font-family: \'Arial\',\'sans-serif\'; mso-fareast-font-family: \'Times New Roman\'; mso-bidi-font-family: \'Times New Roman\';">Use this list to remind yourself of what to look for in order to prevent accidents.<span style="mso-spacerun: yes;">&nbsp; </span>Check each item as you see them:</span></p>
<p style="margin-bottom: .0001pt; text-align: justify; line-height: normal;"><strong><span style="font-size: 7.0pt; font-family: \'Arial\',\'sans-serif\'; mso-fareast-font-family: \'Times New Roman\'; mso-bidi-font-family: \'Times New Roman\';">BEFORE USING THE SCAFFOLD</span></strong><span style="font-size: 7.0pt; font-family: \'Arial\',\'sans-serif\'; mso-fareast-font-family: \'Times New Roman\'; mso-bidi-font-family: \'Times New Roman\';">- </span></p>
<p style="margin-bottom: .0001pt; text-align: justify; line-height: normal;"><span style="font-size: 7.0pt; font-family: \'Arial\',\'sans-serif\'; mso-fareast-font-family: \'Times New Roman\'; mso-bidi-font-family: \'Times New Roman\';"><span style="mso-spacerun: yes;">&nbsp;&nbsp;</span>Has this work location been examined before the start of work operations and have all the appropriate precautions been taken? e.g. checking for: overhead objects, falling or tripping hazards, uneven ground, opening onto a door.</span></p>
<p style="margin-bottom: .0001pt; text-align: justify; line-height: normal;"><span style="font-size: 7.0pt; font-family: \'Arial\',\'sans-serif\'; mso-fareast-font-family: \'Times New Roman\'; mso-bidi-font-family: \'Times New Roman\';"><span style="mso-spacerun: yes;">&nbsp;&nbsp;</span>Will fall protection be required when using this scaffold?</span></p>
<p style="margin-bottom: .0001pt; text-align: justify; line-height: normal;"><span style="font-size: 7.0pt; font-family: \'Arial\',\'sans-serif\'; mso-fareast-font-family: \'Times New Roman\'; mso-bidi-font-family: \'Times New Roman\';"><span style="mso-spacerun: yes;">&nbsp;&nbsp;</span>Has the scaffold been setup according to manufacturer&rsquo;s instructions?</span></p>
<p style="margin-bottom: .0001pt; text-align: justify; line-height: normal;"><strong><span style="font-size: 10.0pt; font-family: \'Arial\',\'sans-serif\'; mso-fareast-font-family: \'Times New Roman\'; mso-bidi-font-family: \'Times New Roman\';">&nbsp;</span></strong></p>
<table style="border-collapse: collapse; border: none; mso-border-alt: solid windowtext .5pt; mso-yfti-tbllook: 480; mso-padding-alt: 0cm 5.4pt 0cm 5.4pt;">
<tbody>
<tr style="mso-yfti-irow: 0; mso-yfti-firstrow: yes;">
<td style="width: 335.95pt; border: solid windowtext 1.0pt; mso-border-alt: solid windowtext .5pt; background: #D9D9D9; padding: 0cm 5.4pt 0cm 5.4pt;">
<p style="margin-bottom: .0001pt; line-height: normal;"><strong><span style="font-size: 10.0pt; font-family: \'Arial\',\'sans-serif\'; mso-fareast-font-family: \'Times New Roman\'; mso-bidi-font-family: \'Times New Roman\';">General Rules for All Scaffolds</span></strong></p>
</td>
<td style="width: 46.05pt; border: solid windowtext 1.0pt; border-left: none; mso-border-left-alt: solid windowtext .5pt; mso-border-alt: solid windowtext .5pt; background: #D9D9D9; padding: 0cm 5.4pt 0cm 5.4pt;" colspan="2">
<p style="margin-bottom: .0001pt; text-align: center; line-height: normal;"><strong><span style="font-size: 10.0pt; font-family: \'Arial\',\'sans-serif\'; mso-fareast-font-family: \'Times New Roman\'; mso-bidi-font-family: \'Times New Roman\';">YES</span></strong></p>
</td>
<td style="width: 34.1pt; border: solid windowtext 1.0pt; border-left: none; mso-border-left-alt: solid windowtext .5pt; mso-border-alt: solid windowtext .5pt; background: #D9D9D9; padding: 0cm 5.4pt 0cm 5.4pt;" colspan="2">
<p style="margin-bottom: .0001pt; text-align: center; line-height: normal;"><strong><span style="font-size: 10.0pt; font-family: \'Arial\',\'sans-serif\'; mso-fareast-font-family: \'Times New Roman\'; mso-bidi-font-family: \'Times New Roman\';">NO</span></strong></p>
</td>
<td style="width: 115.8pt; border: solid windowtext 1.0pt; border-left: none; mso-border-left-alt: solid windowtext .5pt; mso-border-alt: solid windowtext .5pt; background: #D9D9D9; padding: 0cm 5.4pt 0cm 5.4pt;" colspan="2">
<p style="margin-bottom: .0001pt; text-align: center; line-height: normal;"><strong><span style="font-size: 10.0pt; font-family: \'Arial\',\'sans-serif\'; mso-fareast-font-family: \'Times New Roman\'; mso-bidi-font-family: \'Times New Roman\';">Not Applicable</span></strong></p>
</td>
</tr>
<tr style="mso-yfti-irow: 1; height: 14.35pt;">
<td style="width: 335.95pt; border: solid windowtext 1.0pt; border-top: none; mso-border-top-alt: solid windowtext .5pt; mso-border-alt: solid windowtext .5pt; padding: 0cm 5.4pt 0cm 5.4pt; height: 14.35pt;">
<p style="margin-bottom: .0001pt; line-height: normal;"><span style="font-size: 10.0pt; font-family: \'Arial\',\'sans-serif\'; mso-fareast-font-family: \'Times New Roman\'; mso-bidi-font-family: \'Times New Roman\'; mso-bidi-font-weight: bold;">Scaffold components can support at least four times their maximum intended load.</span></p>
</td>
<td style="width: 46.05pt; border-top: none; border-left: none; border-bottom: solid windowtext 1.0pt; border-right: solid windowtext 1.0pt; mso-border-top-alt: solid windowtext .5pt; mso-border-left-alt: solid windowtext .5pt; mso-border-alt: solid windowtext .5pt; padding: 0cm 5.4pt 0cm 5.4pt; height: 14.35pt;" colspan="2">
<p style="margin-bottom: .0001pt; text-align: center; line-height: normal;"><strong><span style="mso-bidi-font-size: 10.0pt; font-family: \'Arial\',\'sans-serif\'; mso-fareast-font-family: \'Times New Roman\'; mso-bidi-font-family: \'Times New Roman\';">x</span></strong></p>
</td>
<td style="width: 34.1pt; border-top: none; border-left: none; border-bottom: solid windowtext 1.0pt; border-right: solid windowtext 1.0pt; mso-border-top-alt: solid windowtext .5pt; mso-border-left-alt: solid windowtext .5pt; mso-border-alt: solid windowtext .5pt; padding: 0cm 5.4pt 0cm 5.4pt; height: 14.35pt;" colspan="2">
<p style="margin-bottom: .0001pt; text-align: center; line-height: normal;"><strong><span style="mso-bidi-font-size: 10.0pt; font-family: \'Arial\',\'sans-serif\'; mso-fareast-font-family: \'Times New Roman\'; mso-bidi-font-family: \'Times New Roman\';">y</span></strong></p>
</td>
<td style="width: 115.8pt; border-top: none; border-left: none; border-bottom: solid windowtext 1.0pt; border-right: solid windowtext 1.0pt; mso-border-top-alt: solid windowtext .5pt; mso-border-left-alt: solid windowtext .5pt; mso-border-alt: solid windowtext .5pt; padding: 0cm 5.4pt 0cm 5.4pt; height: 14.35pt;" colspan="2">
<p style="margin-bottom: .0001pt; text-align: center; line-height: normal;"><strong><span style="mso-bidi-font-size: 10.0pt; font-family: \'Arial\',\'sans-serif\'; mso-fareast-font-family: \'Times New Roman\'; mso-bidi-font-family: \'Times New Roman\';">z</span></strong></p>
</td>
</tr>
<tr style="mso-yfti-irow: 2; height: 16.15pt;">
<td style="width: 335.95pt; border: solid windowtext 1.0pt; border-top: none; mso-border-top-alt: solid windowtext .5pt; mso-border-alt: solid windowtext .5pt; padding: 0cm 5.4pt 0cm 5.4pt; height: 16.15pt;">
<p style="margin-bottom: .0001pt; line-height: normal;"><span style="font-size: 10.0pt; font-family: \'Arial\',\'sans-serif\'; mso-fareast-font-family: \'Times New Roman\'; mso-bidi-font-family: \'Times New Roman\'; mso-bidi-font-weight: bold;">Scaffold is fully planked- No more that 1&rdquo; gap between planks.</span></p>
</td>
<td style="width: 46.05pt; border-top: none; border-left: none; border-bottom: solid windowtext 1.0pt; border-right: solid windowtext 1.0pt; mso-border-top-alt: solid windowtext .5pt; mso-border-left-alt: solid windowtext .5pt; mso-border-alt: solid windowtext .5pt; padding: 0cm 5.4pt 0cm 5.4pt; height: 16.15pt;" colspan="2">
<p style="margin-bottom: .0001pt; text-align: center; line-height: normal;"><strong><span style="mso-bidi-font-size: 10.0pt; font-family: \'Arial\',\'sans-serif\'; mso-fareast-font-family: \'Times New Roman\'; mso-bidi-font-family: \'Times New Roman\';">x</span></strong></p>
</td>
<td style="width: 34.1pt; border-top: none; border-left: none; border-bottom: solid windowtext 1.0pt; border-right: solid windowtext 1.0pt; mso-border-top-alt: solid windowtext .5pt; mso-border-left-alt: solid windowtext .5pt; mso-border-alt: solid windowtext .5pt; padding: 0cm 5.4pt 0cm 5.4pt; height: 16.15pt;" colspan="2">
<p style="margin-bottom: .0001pt; text-align: center; line-height: normal;"><strong><span style="mso-bidi-font-size: 10.0pt; font-family: \'Arial\',\'sans-serif\'; mso-fareast-font-family: \'Times New Roman\'; mso-bidi-font-family: \'Times New Roman\';">y</span></strong></p>
</td>
<td style="width: 115.8pt; border-top: none; border-left: none; border-bottom: solid windowtext 1.0pt; border-right: solid windowtext 1.0pt; mso-border-top-alt: solid windowtext .5pt; mso-border-left-alt: solid windowtext .5pt; mso-border-alt: solid windowtext .5pt; padding: 0cm 5.4pt 0cm 5.4pt; height: 16.15pt;" colspan="2">
<p style="margin-bottom: .0001pt; text-align: center; line-height: normal;"><strong><span style="mso-bidi-font-size: 10.0pt; font-family: \'Arial\',\'sans-serif\'; mso-fareast-font-family: \'Times New Roman\'; mso-bidi-font-family: \'Times New Roman\';">z</span></strong></p>
</td>
</tr>
<tr style="mso-yfti-irow: 3; height: 18.85pt;">
<td style="width: 335.95pt; border: solid windowtext 1.0pt; border-top: none; mso-border-top-alt: solid windowtext .5pt; mso-border-alt: solid windowtext .5pt; padding: 0cm 5.4pt 0cm 5.4pt; height: 18.85pt;">
<p style="margin-bottom: .0001pt; line-height: normal;"><span style="font-size: 10.0pt; font-family: \'Arial\',\'sans-serif\'; mso-fareast-font-family: \'Times New Roman\'; mso-bidi-font-family: \'Times New Roman\'; mso-bidi-font-weight: bold;">Platform is at least 18 inches wide (12 inches on pump jacks).</span></p>
</td>
<td style="width: 46.05pt; border-top: none; border-left: none; border-bottom: solid windowtext 1.0pt; border-right: solid windowtext 1.0pt; mso-border-top-alt: solid windowtext .5pt; mso-border-left-alt: solid windowtext .5pt; mso-border-alt: solid windowtext .5pt; padding: 0cm 5.4pt 0cm 5.4pt; height: 18.85pt;" colspan="2">
<p style="margin-bottom: .0001pt; text-align: center; line-height: normal;"><strong><span style="mso-bidi-font-size: 10.0pt; font-family: \'Arial\',\'sans-serif\'; mso-fareast-font-family: \'Times New Roman\'; mso-bidi-font-family: \'Times New Roman\';">x</span></strong></p>
</td>
<td style="width: 34.1pt; border-top: none; border-left: none; border-bottom: solid windowtext 1.0pt; border-right: solid windowtext 1.0pt; mso-border-top-alt: solid windowtext .5pt; mso-border-left-alt: solid windowtext .5pt; mso-border-alt: solid windowtext .5pt; padding: 0cm 5.4pt 0cm 5.4pt; height: 18.85pt;" colspan="2">
<p style="margin-bottom: .0001pt; text-align: center; line-height: normal;"><strong><span style="mso-bidi-font-size: 10.0pt; font-family: \'Arial\',\'sans-serif\'; mso-fareast-font-family: \'Times New Roman\'; mso-bidi-font-family: \'Times New Roman\';">y</span></strong></p>
</td>
<td style="width: 115.8pt; border-top: none; border-left: none; border-bottom: solid windowtext 1.0pt; border-right: solid windowtext 1.0pt; mso-border-top-alt: solid windowtext .5pt; mso-border-left-alt: solid windowtext .5pt; mso-border-alt: solid windowtext .5pt; padding: 0cm 5.4pt 0cm 5.4pt; height: 18.85pt;" colspan="2">
<p style="margin-bottom: .0001pt; text-align: center; line-height: normal;"><strong><span style="mso-bidi-font-size: 10.0pt; font-family: \'Arial\',\'sans-serif\'; mso-fareast-font-family: \'Times New Roman\'; mso-bidi-font-family: \'Times New Roman\';">z</span></strong></p>
</td>
</tr>
<tr style="mso-yfti-irow: 4; height: 26.5pt;">
<td style="width: 335.95pt; border: solid windowtext 1.0pt; border-top: none; mso-border-top-alt: solid windowtext .5pt; mso-border-alt: solid windowtext .5pt; padding: 0cm 5.4pt 0cm 5.4pt; height: 26.5pt;">
<p style="margin-bottom: .0001pt; line-height: normal;"><span style="font-size: 10.0pt; font-family: \'Arial\',\'sans-serif\'; mso-fareast-font-family: \'Times New Roman\'; mso-bidi-font-family: \'Times New Roman\'; mso-bidi-font-weight: bold;">Guardrails are used or personal fall arrest system is used, if work height is &gt;6 feet.</span></p>
<p style="margin-bottom: .0001pt; line-height: normal;"><span style="font-size: 10.0pt; font-family: \'Arial\',\'sans-serif\'; mso-fareast-font-family: \'Times New Roman\'; mso-bidi-font-family: \'Times New Roman\'; mso-bidi-font-weight: bold;">Guardrail system: </span><strong><span style="font-size: 10.0pt; font-family: \'Arial\',\'sans-serif\'; mso-fareast-font-family: \'Times New Roman\'; mso-bidi-font-family: \'Times New Roman\';"><span style="mso-spacerun: yes;">&nbsp;</span></span></strong><span style="font-size: 10.0pt; font-family: \'Arial\',\'sans-serif\'; mso-fareast-font-family: \'Times New Roman\'; mso-bidi-font-family: \'Times New Roman\'; mso-bidi-font-weight: bold;">Toprail </span><strong><span style="font-size: 10.0pt; font-family: \'Arial\',\'sans-serif\'; mso-fareast-font-family: \'Times New Roman\'; mso-bidi-font-family: \'Times New Roman\';"><span style="mso-spacerun: yes;">&nbsp;</span></span></strong><span style="font-size: 10.0pt; font-family: \'Arial\',\'sans-serif\'; mso-fareast-font-family: \'Times New Roman\'; mso-bidi-font-family: \'Times New Roman\'; mso-bidi-font-weight: bold;">Midrail </span><strong><span style="font-size: 10.0pt; font-family: \'Arial\',\'sans-serif\'; mso-fareast-font-family: \'Times New Roman\'; mso-bidi-font-family: \'Times New Roman\';"><span style="mso-spacerun: yes;">&nbsp;</span></span></strong><span style="font-size: 10.0pt; font-family: \'Arial\',\'sans-serif\'; mso-fareast-font-family: \'Times New Roman\'; mso-bidi-font-family: \'Times New Roman\'; mso-bidi-font-weight: bold;">Toeboard, </span><strong><span style="font-size: 10.0pt; font-family: \'Arial\',\'sans-serif\'; mso-fareast-font-family: \'Times New Roman\'; mso-bidi-font-family: \'Times New Roman\';"><span style="mso-spacerun: yes;">&nbsp;</span></span></strong><span style="font-size: 10.0pt; font-family: \'Arial\',\'sans-serif\'; mso-fareast-font-family: \'Times New Roman\'; mso-bidi-font-family: \'Times New Roman\'; mso-bidi-font-weight: bold;">Posts</span></p>
</td>
<td style="width: 46.05pt; border-top: none; border-left: none; border-bottom: solid windowtext 1.0pt; border-right: solid windowtext 1.0pt; mso-border-top-alt: solid windowtext .5pt; mso-border-left-alt: solid windowtext .5pt; mso-border-alt: solid windowtext .5pt; padding: 0cm 5.4pt 0cm 5.4pt; height: 26.5pt;" colspan="2">
<p style="margin-bottom: .0001pt; text-align: center; line-height: normal;"><strong><span style="mso-bidi-font-size: 10.0pt; font-family: \'Arial\',\'sans-serif\'; mso-fareast-font-family: \'Times New Roman\'; mso-bidi-font-family: \'Times New Roman\';">x</span></strong></p>
</td>
<td style="width: 34.1pt; border-top: none; border-left: none; border-bottom: solid windowtext 1.0pt; border-right: solid windowtext 1.0pt; mso-border-top-alt: solid windowtext .5pt; mso-border-left-alt: solid windowtext .5pt; mso-border-alt: solid windowtext .5pt; padding: 0cm 5.4pt 0cm 5.4pt; height: 26.5pt;" colspan="2">
<p style="margin-bottom: .0001pt; text-align: center; line-height: normal;"><strong><span style="mso-bidi-font-size: 10.0pt; font-family: \'Arial\',\'sans-serif\'; mso-fareast-font-family: \'Times New Roman\'; mso-bidi-font-family: \'Times New Roman\';">y</span></strong></p>
</td>
<td style="width: 115.8pt; border-top: none; border-left: none; border-bottom: solid windowtext 1.0pt; border-right: solid windowtext 1.0pt; mso-border-top-alt: solid windowtext .5pt; mso-border-left-alt: solid windowtext .5pt; mso-border-alt: solid windowtext .5pt; padding: 0cm 5.4pt 0cm 5.4pt; height: 26.5pt;" colspan="2">
<p style="margin-bottom: .0001pt; text-align: center; line-height: normal;"><strong><span style="mso-bidi-font-size: 10.0pt; font-family: \'Arial\',\'sans-serif\'; mso-fareast-font-family: \'Times New Roman\'; mso-bidi-font-family: \'Times New Roman\';">z</span></strong></p>
</td>
</tr>
<tr style="mso-yfti-irow: 5; height: 25.15pt;">
<td style="width: 335.95pt; border: solid windowtext 1.0pt; border-top: none; mso-border-top-alt: solid windowtext .5pt; mso-border-alt: solid windowtext .5pt; padding: 0cm 5.4pt 0cm 5.4pt; height: 25.15pt;">

</td>
</tr>
</tbody>
</table>
<p>&nbsp;</p>';

\PhpOffice\PhpWord\Shared\Html::addHtml($section, $html);

// Save file
echo write($phpWord, basename(__FILE__, '.php'), $writers);
if (!CLI) {
    include_once 'Sample_Footer.php';
}

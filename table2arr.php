<?php
require_once("includes/table2arr.php");






//$myFile = "test_1.htm";
//$myFile = "http://www.lyngsat.com/packages/aljazeeranile_sid.html";
//$myFile = "http://www.lyngsat.com/packages/digiturk_sid.html";

$url = $_POST['url'];

if ( ! strstr($url, 'packages', TRUE) ) {
    die ('URL is wrong');
}

if ( ! $fh = fopen($url, 'rb') ) {
    die('URL is not there');
}

$myFile = $_POST['url'];
//$fh = fopen($myFile, 'rb');
//$theData = fread($fh, filesize($myFile));
$theData = stream_get_contents($fh);
fclose($fh);


if(preg_match('/<title>(.*)<\/title>/smU', $theData, $matches)) {
    $title = $matches[1];
}

$g= new table2arr($theData,$colspanmode=FALSE);
$cnt=$g->tablecount;

print "<pre>";
$cellcount;
$startcounting;
$myarray;
$startcounting=0;
$cellcount=0;
$services="";

for($i=0;$i<$cnt;$i++)
{
   // echo "<hr />";
   // echo "Table $i";
    $g->getcells($i);

    foreach ($g->cells as $garray) {

        if ( $garray[0] == "SID" || $startcounting == 1 ) {
            
            if (  ! preg_match("/^SID|[0-9]+$/", $garray[0]) ) {
                $startcounting == 0;
                $cellcount ++;
                continue;
                } //end if
            $startcounting = 1;
           if ( $garray[0] <> "SID" && $g->cells[$cellcount][3] <> NULL ) {
                $myarray[] = array($g->cells[$cellcount][0], $g->cells[$cellcount][3]);
           }
            //echo "cell count: $cellcount";
           // print_r($g->cells[$cellcount]);
            } //end if
    $cellcount ++;


        } //end if
        break;
    }
    //echo "<hr />";echo "<hr />";
    //print_r($myarray);

    $services;
    foreach ($myarray as $value) {
        $services .= sprintf("%04X", $value[0]);
        $services .= " = $value[1] ";
        $services .= sprintf("[%04X]", $value[0]);
        $services .= "<br/>";
    }

    include 'includes/header.php';
    echo "### Package Name: ". htmlentities($title)."</br>";
    echo"</br>";
    echo $services;




    print "</pre>";




?>
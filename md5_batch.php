<?php
$numbers = explode(",", $_REQUEST["text"]);
$resut = array();
foreach ($numbers as $number) {
    $resut[] = array(
        "number"=>$number,
        "md5"=>md5($number)
    );
}
header("Content-type: application/json");
echo json_encode($resut);

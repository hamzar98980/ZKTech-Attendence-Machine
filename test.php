<?php
//The socket functions described here are part of an extension to PHP which must be enabled at compile time by giving the --enable-sockets option to configure.
//Add extension=php_sockets.dllextension=php_sockets.dll in php.ini and extension=sockets
//user defined rule 4
//super admin rule 14
//normal user 0
include "zklibrary.php";
echo 'Library Loaded</br>';
$zk = new ZKLibrary('192.168.100.70', 4370, 'UDP');
echo 'Requesting for connection</br>';
$zk->connect();
echo 'Connected</br>';
$zk->disableDevice();
$zk->testVoice();
echo 'disabling device</br>';
$users = $zk->getUser();


$attendace = $zk->getAttendance();

$attendence_array = [];

$no = 0;
// $attendace = array_reverse($attendace);
// print_r($attendace[0]);
foreach ($attendace as $key => $at) {

  $attendence_array[$no]['Uid'] =  $at[0];
  $attendence_array[$no]['Userid'] =  $at[1];
  $attendence_array[$no]['Time'] =  $at[3];
  $no++;
}


// print_r($attendence_array);
$attendence_array = ['data' => $attendence_array];
$attendence_array = json_encode($attendence_array);

// echo $attendence_array;
// exit;


$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'http://127.0.0.1:8000/api/allattencdencedata/1',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS => $attendence_array,
  CURLOPT_HTTPHEADER => array(
    'Content-Type: application/json'
  ),
));

$response = curl_exec($curl);
curl_close($curl);
$returndata = json_decode($response);

// echo '<pre>';
echo $returndata->message;
// echo '</pre>';



$zk->enableDevice();
$zk->disconnect();

<?php

require("phpMQTT.php");

$server  = "192.168.1.54"; 
$port  = 1883;
$username = null;
$password = null;
$client_id = "Client-".rand();

$mqtt = new phpMQTT($server, $port, $client_id);
if( !$mqtt->connect(true, NULL, $username, $password) ) {
 exit(1);
}

$topics['Test/Phyathai'] = array("qos" => 0, "function" => "procmsg");
$mqtt->subscribe($topics, 0);

while($mqtt->proc()){
 
}

$mqtt->close();

function procmsg($topic, $msg){
  echo "Recieved at: " . date("Y-m-d H:i:s", time()) . "\n";
  echo "Topic: {$topic}\n";
  echo "Message: $msg\n\n";
}

?>
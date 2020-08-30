<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL);
set_time_limit(0);

header('Content-Type: application/json');

$headers_Authorization = $_SERVER['HTTP_AUTHORIZATION'];

$START=1;
if($START==1)
{

    if($_SERVER['REQUEST_METHOD']=='POST')
    {
        if($headers_Authorization=='phayathai@freewill')
        {
            $FLAG_GET_ROOM=1;
            if($FLAG_GET_ROOM==1)
            {
                $androidbox_device_json = trim(file_get_contents("json/androidbox.json"));
                $androidbox_device_json = json_decode($androidbox_device_json, true);
                $config_androidbox_device_list = $androidbox_device_json['device'];
                for($getRoom=0;$getRoom<count($config_androidbox_device_list);$getRoom++)
                {
                    $get_nurse_list = [];
                    $device_device_id = $config_androidbox_device_list[$getRoom]['device_id'];
                    $device_title = $config_androidbox_device_list[$getRoom]['title'];
                    $device_ordinal = $config_androidbox_device_list[$getRoom]['ordinal'];
                    $device_device_id_URL = $device_device_id.".json";
                    $device_device_id_URL_path = "json_androidbox/".$device_device_id_URL;

                    //// Get Nurse ////
                    if( file_exists($device_device_id_URL_path) )
                    {

                        $androidbox_json = trim(file_get_contents($device_device_id_URL_path));
                        $androidbox_json = json_decode($androidbox_json, true);
                        $iTAG_list = $androidbox_json['itag']['itag_list'];

                        for($getNurse=0;$getNurse<count($iTAG_list);$getNurse++)
                        {
                            $mac_address = $iTAG_list[$getNurse]['mac_address'];
                            $distance = $iTAG_list[$getNurse]['distance'];
                            $title = "Unknown";
                            $brand = "Unknown";

                            //// Get Title ////
                            if($mac_address!='')
                            {
                                $filename = 'json/itag.json';
                                $itag_device_json = trim(file_get_contents($filename));
                                $itag_device_json = json_decode($itag_device_json, true);

                                $iTAG_config = $itag_device_json['config'];
                                $iTAG_device = $itag_device_json['device'];
                                for($Anum=0;$Anum<count($iTAG_device);$Anum++)
                                {
                                    if($mac_address==$iTAG_device[$Anum]['mac_address'])
                                    {
                                        $title = $iTAG_device[$Anum]['title'];
                                        $is_title = strpos($title," ");
                                        if( $is_title > 0 )
                                        {
                                            $titleW = explode(" ", $title);
                                            $titleF = substr($titleW[0],0,1);
                                            $titleL = substr($titleW[1],0,1);
                                            $titleR = $titleF.$titleL;
                                        }
                                        else
                                        {
                                            $titleF = substr($title,0,1);
                                            $titleR = $titleF;
                                        }
                                        
                                        $brand =  $iTAG_device[$Anum]['brand'];
                                    }
                                }
                            }
                            //// END ////

                            //// Filter Distance ////
                            if($title != "Unknown" && $brand != "Unknown")
                            {
                                for($iTAG_configNum=0;$iTAG_configNum<count($iTAG_config);$iTAG_configNum++)
                                {

                                    $global_distance = $iTAG_config[$iTAG_configNum]["global_distance"];
                                    if($iTAG_config[$iTAG_configNum]["brand"]==$brand)
                                    {
                                        if($global_distance<$distance)
                                        {
                                            $get_nurse_list[] = array(
                                                    "title"=>$titleR,
                                                    "brand"=>$brand,
                                                    "mac_address"=>$mac_address,
                                                    "distance"=>$distance,
                                                    "s"=>"What"
                                            );                                    
                                        }
                                    }
                                }
                            }
                            //// END ////
                        }

                        //Delete
                        unlink($device_device_id_URL_path);

                    }
                    //// END ////
                    $DataRoom[$getRoom] = array(
                        "ordinal"=>$device_ordinal,
                        "device_id"=>$device_device_id,
                        "room_title"=>$device_title,
                        "nurse_list"=>$get_nurse_list
                    );

                    if( file_exists($device_device_id_URL) )
                    {
                        unlink($device_device_id_URL);  
                    }
                    
                }
        
                //Sort
                sort($DataRoom);
                foreach ($DataRoom as $key => $val) {
                    $DataRoom[$key] = array(
                        "ordinal"=>$val['ordinal'],
                        "device_id"=>$val['device_id'],
                        "room_title"=>$val['room_title'],
                        "nurse_list"=>$val['nurse_list']
                    );
                }
                //
        
                if(count($DataRoom)>1)
                {
                    $DataRoom = $DataRoom;
                }
                else
                {
                    $DataRoom = [$DataRoom];
                }
                $data = [
                    "head"=>array("code"=>200,"message"=>"OK"),
                    "body"=>array("room"=>$DataRoom)
                ]; 
            }
        }
    }
    else
    {
        $code = 400;
        $message = "METHOD WHAT => KICK KICK!!!";
        $version = 'xxxx2020xxxxx';
        $data = [
            "head"=>array("code"=>$code,"message"=>$message,"version"=>$version),
            "body"=>[]
        ];
    }

    $json =  json_encode($data,JSON_PRETTY_PRINT);

}

require("../mqtt/phpMQTT.php");

$server  = "broker.emqx.io"; 
$port  = 1883;
$username = null;
$password = null;
$client_id = "Client-".rand();
$ward = "Ward 1";

$mqtt = new phpMQTT($server, $port, $client_id);
if ($mqtt->connect(true, NULL, $username, $password)) {
    $mqtt->publish("PhyathaiIPD/".$ward, $json, 0);
    $mqtt->close();
   } else {
       echo "Time out!\n";
   }

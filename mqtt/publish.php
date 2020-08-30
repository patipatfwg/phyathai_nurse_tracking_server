<?php

require("phpMQTT.php");

$server  = "broker.emqx.io"; 
$port  = 1883;
$username = null;
$password = null;
$client_id = "Client-".rand();

$mqtt = new phpMQTT($server, $port, $client_id);

$json = '
{
    "head": {
        "code": 200,
        "message": "OK"
    },
    "body": {
        "room": [
            {
                "ordinal": 1,
                "device_id": "7e49d38c03225ea4",
                "room_title": "101",
                "nurse_list": []
            },
            {
                "ordinal": 2,
                "device_id": "8eb9c6c70d3a9477",
                "room_title": "102",
                "nurse_list": [
                    {
                        "title": "NK",
                        "brand": "iTAG",
                        "mac_address": "FF:FF:B1:25:6D:80",
                        "distance": -62,
                        "s": "What"
                    },
                    {
                        "title": "PC",
                        "brand": "devdev",
                        "mac_address": "F6:74:96:54:8E:B6",
                        "distance": -71,
                        "s": "What"
                    }
                ]
            },
            {
                "ordinal": 3,
                "device_id": "50321a567585340f",
                "room_title": "103",
                "nurse_list": []
            },
            {
                "ordinal": 4,
                "device_id": "postman",
                "room_title": "104",
                "nurse_list": []
            },
            {
                "ordinal": 5,
                "device_id": "2fb18a659638ba02",
                "room_title": "105",
                "nurse_list": []
            },
            {
                "ordinal": 6,
                "device_id": "2fb18a659638ba02a",
                "room_title": "106",
                "nurse_list": []
            },
            {
                "ordinal": 7,
                "device_id": "2fb18a659638ba02]",
                "room_title": "107",
                "nurse_list": []
            },
            {
                "ordinal": 8,
                "device_id": "2fb18a659638ba02[",
                "room_title": "108",
                "nurse_list": []
            },
            {
                "ordinal": 9,
                "device_id": "2fb18a659638ba02p",
                "room_title": "109",
                "nurse_list": []
            },
            {
                "ordinal": 10,
                "device_id": "2fb18a659638ba02o",
                "room_title": "110",
                "nurse_list": []
            },
            {
                "ordinal": 11,
                "device_id": "2fb18a659638ba02i",
                "room_title": "111",
                "nurse_list": []
            },
            {
                "ordinal": 12,
                "device_id": "2fb18a659638ba02u",
                "room_title": "112",
                "nurse_list": []
            },
            {
                "ordinal": 13,
                "device_id": "2fb18a659638ba02y",
                "room_title": "113",
                "nurse_list": []
            },
            {
                "ordinal": 14,
                "device_id": "2fb18a659638ba02t",
                "room_title": "114",
                "nurse_list": []
            },
            {
                "ordinal": 15,
                "device_id": "2fb18a659638ba02r",
                "room_title": "115",
                "nurse_list": []
            },
            {
                "ordinal": 16,
                "device_id": "2fb18a659638ba02e",
                "room_title": "116",
                "nurse_list": []
            },
            {
                "ordinal": 17,
                "device_id": "2fb18a659638ba02w",
                "room_title": "117",
                "nurse_list": []
            },
            {
                "ordinal": 18,
                "device_id": "2fb18a659638ba02q",
                "room_title": "118",
                "nurse_list": []
            }
        ]
    }
}
';



if ($mqtt->connect(true, NULL, $username, $password)) {
 $mqtt->publish("test/soso", $json, 0);
 $mqtt->close();
} else {
    echo "Time out!\n";
}

?>
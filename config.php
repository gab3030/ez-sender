<?php

// multi smtp user duplikasi line sesuai kebutuhan, smtp host harus sama
$smtp[] = array( "host" => "smtp.mailtrap.io", "port" => "587", "username" => "3feeb83591b7de", "password" => "07b9ba4e5caf5f");
// $smtp[] = array( "host" => "smtp.mailtrap.io", "port" => "587", "username" => "hehe", "password" => "hehe");

// config 
$config = array(

    "send_delay" => "5",

    "message_subject" => "Magic Happen !! ##randoom_ip## - ##random_device## - ##random_negara##",
    "message_sender_email" => "service@apple.com",
    "message_sender_name" => "Apple",
    "message_html" => "letter.html"

);

// custom header
$headers = array(

    'X-Originating-IP' => 'xx',
    'Authentication-Results' => 'xx',
    'Received' => 'xxx',

);
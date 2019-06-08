<?php

// multi smtp user duplikasi line sesuai kebutuhan, smtp host harus sama
$smtp[] = array( "host" => "smtp.mailtrap.io", "port" => "587", "username" => "3feeb83591b7de", "password" => "07b9ba4e5caf5f");
$smtp[] = array( "host" => "smtp.mailtrap.io", "port" => "587", "username" => "3feeb83591b7de", "password" => "07b9ba4e5caf5f");

// config 
$config = array(

    "message_subject" => "Magic Happen !! ##date## - ##random_device##",
    "message_sender_email" => "service@apple.com",
    "message_sender_name" => "Apple",

    "send_delay" => "5",
    "message_html" => "letter.html",
    "message_attach" => "test.pdf", // file asli
    "message_attach_rename" => "helo.pdf" // rename file

);

// custom header
$headers = array(

    'X-Originating-IP' => 'xx',
    'Authentication-Results' => 'xx',
    'Received' => 'xxx',

);
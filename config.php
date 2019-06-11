<?php

// multi smtp
$smtp[] = array( "host" => "smtp.example.com", "port" => "587", "security" => "tls", "username" => "hehe", "password" => "hehe");
$smtp[] = array( "host" => "smtp.example.com", "port" => "587", "security" => "tls", "username" => "hehe", "password" => "hehe");

// message 
$messages[] = array("subject" => "subjek 1 ##random_link##", "email" => "email1@example.com", "name" => "name 1",);
$messages[] = array("subject" => "subjek 2 ##random_link##", "email" => "email2@example.com", "name" => "name 2",);
$messages[] = array("subject" => "subjek 3 ##random_link##", "email" => "email3@example.com", "name" => "name 3",);

// config 
$config = array(
    "delay_after" => "1", // emails
    "delay" => "5", // detik
    "message_html" => "letter.html",
    "message_attach" => "test.pdf", // file asli
    "message_attach_rename" => "helo.pdf", // rename file
);

// random links
$links = array(
    "http://google.com",
    "http://yahoo.com",
    "http://youtube.com",
);

// custom header
$headers = array(
    'X-Originating-IP' => 'xx',
    'Authentication-Results' => 'xx',
    'Received' => 'xxx',
);
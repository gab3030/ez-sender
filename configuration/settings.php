<?php

// setting all needs here

$settings['multy']['smtp'][]    = [ "host" => "smtp-relay.gmail.com", "port" => "587", "security" => "tls", "username" => "xxxxxxxxxxxx", "password" => "xxxxxxx" ];
$settings['multy']['smtp'][]    = [ "host" => "smtp-relay.gmail.com", "port" => "587", "security" => "tls", "username" => "xxxxxxxxxxxx", "password" => "xxxxxxx" ];

$settings['multy']['message'][] = [ "subject" => "sub one ##random_mix_10##", "email" => "##random_string_low_10##@##random_string_low_10##.com", "name" => "name one" ];
$settings['multy']['message'][] = [ "subject" => "sub two ##random_mix_10##", "email" => "##random_string_low_10##@##random_string_low_10##.com", "name" => "name two" ];

$settings['main'] = [

        "delay_after"       => "1",
        "delay"             => "5",
        "html_file"         => "letter.html",
        "mailist_file"      => "mailist.txt",
        "attachment"        => "test_file.pdf",
        "attachment_rename" => "new_name.pdf",
        "charset"           => "iso-8859-1",
        "encoding"          => "8bit",
        
];

$settings['headers'] = [

        'X-Originating-IP' => 'xx',
        'Authentication-Results' => 'xx',
        'Received' => 'xxx',

];

$settings['links'] = [

        "http://google.com",
        "http://yahoo.com",
        "http://youtube.com",

];
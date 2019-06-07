<?php

// require all file
require_once 'vendor/autoload.php';
require_once 'config.php';
require_once 'engine/replace.php';
require_once 'engine/random.php';

// climate 
$climate = new \League\CLImate\CLImate;

// set timezone
date_default_timezone_set("Asia/Jakarta");

$handle = @fopen("list.txt", "r"); // read list.txt
$total = count(file('list.txt')); // count list.txt
$i=0;
if ($handle) {
    while (($email = fgets($handle, 4096)) !== false) {

		$i<=$total;$i++; // set progress
		$email = trim($email); // remove spaces

		// set all 
		$smtp_host = random_array_value($smtp)['host'];
		$smtp_port = random_array_value($smtp)['port'];
		$smtp_username = random_array_value($smtp)['username'];
		$smtp_password = random_array_value($smtp)['password'];
		$message_subject = replace($config['message_subject'],$email);
		$message_sender_email = replace($config['message_sender_email'],$email);
		$message_sender_name = replace($config['message_sender_name'],$email);
		$html_file = replace(file_get_contents($config['message_html']),$email);
		$html_file_name = $config['message_html'];
		$delay = $config['send_delay'];

		// set smtp
		$transport = (new Swift_SmtpTransport($smtp_host, $smtp_port, 'tls'))
			->setUsername($smtp_username)
			->setPassword($smtp_password)
		;
		$mailer = new Swift_Mailer($transport);

		// set message
		$message = (new Swift_Message($message_subject))
			->setFrom([$message_sender_email => $message_sender_name])
			->setTo([$email])
			->setBody($html_file, 'text/html')
		;

		// add headers
		foreach (@$headers as $key => $value) {
			$message->getHeaders()->addTextHeader($key, $value);
		};
		
		// begin send
		$result = $mailer->send($message);

		// print progress
		$climate->clear();
		echo $art;
		$climate->white("Current Config:")->br();
		$climate->error("-> SMTP Host: ".$smtp_host)->br();
		$climate->error("-> SMTP Username: ".$smtp_username)->br();
		$climate->error("-> Letter File: ".$html_file_name)->br();
		$climate->error("-> Delay: ".$delay." Second")->br();
		$climate->white("Current Progress:")->br();
		$climate->comment("-> ".$message->getHeaders()->get('Message-ID')->toString());
		$climate->comment("-> ".$message->getHeaders()->get('Date')->toString());
		$climate->comment("-> ".$message->getHeaders()->get('From')->toString());
		$climate->comment("-> ".$message->getHeaders()->get('Subject')->toString());
		$climate->info("-> ".$message->getHeaders()->get('To')->toString());
		$climate->progress()->total($total)->current($i);
		sleep($delay);

    }
    if (!feof($handle)) {
        echo "Error: unexpected fgets() fail\n";
    }
    fclose($handle);
}

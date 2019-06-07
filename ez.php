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
		$message_html_value = replace(file_get_contents($config['message_html']),$email);
		$message_html_name = $config['message_html'];
		$message_attach = $config['message_attach'];
		$message_attach_rename = $config['message_attach_rename'];
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
			->setBody($message_html_value, 'text/html')
		;

		// add headers
		if (!empty($headers)) {
			foreach ($headers as $key => $value) {
				$message->getHeaders()->addTextHeader($key, $value);
			};
		}

		// attach file
		if (!empty($message_attach && $message_attach_rename)) {
			$message->attach(
				Swift_Attachment::fromPath($message_attach)->setFilename($message_attach_rename)
			);
		}	
		
		// begin send
		$result = $mailer->send($message);
		
		// set kolom
		$columns = [
			["- SMTP Host : ".$smtp_host, 			"- Message html: ".$message_html_name],
			["- SMTP Username : ".$smtp_username, 	"- Send Delay: ".$delay." Second"],
			["- SMTP Port : ".$smtp_port, 			"- Message Attach: ".$message_attach],
			["- SMTP Password : ***", 				"- Custom Header : ".berisi($headers)]
		];

		// print progress
		$climate->clear();
		$climate->error($art);
		$climate->lightCyan("Current Config:")->br();
		$climate->columns($columns);
		$climate->br();
		$climate->lightCyan("Current Progress:")->br();
		$climate->comment("-> ".trim($message->getHeaders()->get('Message-ID')->toString()));
		$climate->comment("-> ".trim($message->getHeaders()->get('Date')->toString()));
		$climate->comment("-> ".trim($message->getHeaders()->get('From')->toString()));
		$climate->comment("-> ".trim($message->getHeaders()->get('Subject')->toString()));
		$climate->comment("-> ".$message->getHeaders()->get('To')->toString());
		$climate->progress()->total($total)->current($i);
		sleep($delay);

    }
    if (!feof($handle)) {
        echo "Error: unexpected fgets() fail\n";
    }
    fclose($handle);
}

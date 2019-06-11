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
		$smtp_multy = random_array_value($smtp);
		$message_multy = random_array_value($messages);
		$message_subject_replaced = replace($message_multy['subject'],$email,$links);
		$message_email_replaced = replace($message_multy['email'],$email,$links);
		$message_name_replaced = replace($message_multy['name'],$email,$links);
		$message_html_value_replaced = replace(file_get_contents($config['message_html']),$email,$links);
		$message_html_name = $config['message_html'];
		$message_attach = $config['message_attach'];
		$message_attach_rename = $config['message_attach_rename'];
		$delay_after = $config['delay_after'];
		$delay = $config['delay'];

		// set smtp
		$transport = (new Swift_SmtpTransport($smtp_multy['host'], $smtp_multy['port'], $smtp_multy['security']))
			->setUsername($smtp_multy['username'])
			->setPassword($smtp_multy['password'])
		;
		$mailer = new Swift_Mailer($transport);

		// set message
		$message = (new Swift_Message($message_subject_replaced))
			->setFrom([$message_email_replaced => $message_name_replaced])
			->setTo([$email])
			->setBody($message_html_value_replaced, 'text/html')
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

		$climate->clear();
		$climate->error($art);

		// print config
		$climate->table([
			[	"- SMTP Host : ".$smtp_multy['host'], 								"- Message HTML: ".$message_html_name,			],
			[	"- SMTP Username : ".$smtp_multy['username'], 						"- Message Attach: ".$message_attach_rename,	],
			[	"- SMTP Port : ".$smtp_multy['port'], 								"- Random Links : ".berisi($links),	 			],
			[	"- Send Ratio : ".$delay_after." emails / delay ".$delay." sec", 	"- Custom Header : ".berisi($headers),			],
		]);
		$climate->br();

		// print progress
		$climate->table([
			["- ".trim($message->getHeaders()->get('Message-ID')->toString())],
			["- ".trim($message->getHeaders()->get('Date')->toString())],
			["- ".trim($message->getHeaders()->get('From')->toString())],
			["- ".trim($message->getHeaders()->get('Subject')->toString())],
			["- ".trim($message->getHeaders()->get('To')->toString())],
		]);
		$climate->br();
		$climate->progress()->total($total)->current($i);

		if ( $i % $delay_after === 0) {
			sleep($delay);
		} 
    }
    if (!feof($handle)) {
        echo "Error: unexpected fgets() fail\n";
    }
    fclose($handle);
}

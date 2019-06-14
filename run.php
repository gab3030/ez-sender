<?php

// don't change anything

require_once('vendor/autoload.php');
require_once('configuration/settings.php');
require_once('modules/replace.php');
require_once('modules/random.php');

$climate = new \League\CLImate\CLImate;

date_default_timezone_set("Asia/Jakarta");

$handle = @fopen("configuration/".$settings['main']['mailist_file'], "r");
$total = count(file("configuration/".$settings['main']['mailist_file']));
$i=0;
if ($handle) {
    while (($email = fgets($handle, 4096)) !== false) {

		$i<=$total;$i++;
		$email = trim($email);

		$multy_smtp 					= random_array_value($settings['multy']['smtp']);
		$multy_message 					= random_array_value($settings['multy']['message']);
		$replaced_subject 				= replace($multy_message['subject'], $email, $settings['links']);
		$replaced_from_mail 			= replace($multy_message['email'], $email, $settings['links']);
		$replaced_from_name 			= replace($multy_message['name'], $email, $settings['links']);

		$html_file		 				= $settings['main']['html_file'];
		$replaced_html_file_contents	= replace(file_get_contents("configuration/".$html_file), $email, $settings['links']);
		$attachment 					= $settings['main']['attachment'];
		$attachment_rename 				= $settings['main']['attachment_rename'];
		$delay_after 					= $settings['main']['delay_after'];
		$delay 							= $settings['main']['delay'];
		$charset						= $settings['main']['charset'];
		$encoding						= $settings['main']['encoding'];
		

		$mailer = new Swift_Mailer(
			(new Swift_SmtpTransport($multy_smtp['host'], $multy_smtp['port'], $multy_smtp['security']))
				->setUsername($multy_smtp['username'])
				->setPassword($multy_smtp['password'])
		);

		$message = (new Swift_Message($replaced_subject))
			->setFrom([$replaced_from_mail => $replaced_from_name])
			->setTo([$email])
			->setBody($replaced_html_file_contents, 'text/html')
		;

		if (!empty($settings['headers'])) {
			foreach ($settings['headers'] as $key => $value) {
				$message->getHeaders()->addTextHeader($key, $value);
			};
		}
		
		if (!empty($encoding && $charset)) {
			$message->getHeaders()->get('Content-Type')->setParameter('charset', $charset);
			$message->getHeaders()->get('Content-Transfer-Encoding')->setValue($encoding);
		}

		if (!empty($attachment && $attachment_rename)) {
			$message->attach(Swift_Attachment::fromPath("configuration/".$attachment)->setFilename($attachment_rename));
		}	

			
		
		$mailer->send($message);

		$climate->clear();
		$climate->lightGreen($art);

		$climate->columns([
			[	"[-] Host: ".$multy_smtp['host'], 									"[-] HTML File: ".$html_file,									],
			[	"[-] Username: ".$multy_smtp['username'], 							"[-] Mailist File: ".$settings['main']['mailist_file'],		],
			[	"[-] Port: ".$multy_smtp['port'], 									"[-] Attachment: ".$attachment_rename,							],
			[	"[-] Send Ratio: ".$delay_after." emails / delay ".$delay." sec", 	"[-] Custom Header: ".count($settings['headers']),				],
		]);
		$climate->br();

		$climate->lightMagenta("[+] Date: ".date("F j, Y, g:i a"));
		$climate->lightMagenta("[+] From: ".$replaced_from_name." <".$replaced_from_mail.">");
		$climate->lightMagenta("[+] Subject: ".$replaced_subject);
		$climate->lightMagenta("[+] To: ".$email);
		$climate->br();

		$persen = round(($i/$total)*100);
		$climate->lightCyan("[x] Progress: ".$i."/".$total." (".$persen."%)");
		$climate->br();

		if ( $i % $delay_after === 0) {
			sleep($delay);
		} 
    }
    if (!feof($handle)) {
        echo "Error: unexpected fgets() fail\n";
    }
    fclose($handle);
}

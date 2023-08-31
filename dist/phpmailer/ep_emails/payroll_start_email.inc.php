<?php
	// function send_email_now($fullname, $email_add, $ul_type, $ul_status, $ul_adate){
	// 	$arr_ul_type = array("Error", "Payroll", "OTKS");
	// 	$arr_ul_status = array("Cancelled due to error", "Started", "Completed");
	// 	$arr_color = array("Red","Gold","Green");
	// 	$hour = date("G"); 
	// 	$minute = date("i"); 
	// 	$second = date("s");
	// 	$greet = "Hi,";
	// 	if ( (int)$hour == 0 && (int)$hour <= 9 ) { $greet = "Good Morning,"; } 
	// 	else if ( (int)$hour >= 10 && (int)$hour <= 11 ) { $greet = "Good Day,"; } 
	// 	else if ( (int)$hour >= 12 && (int)$hour <= 17 ) { $greet = "Good Afternoon,"; } 
	// 	else if ( (int)$hour >= 18 && (int)$hour <= 23 ) { $greet = "Good Evening,"; } 
	// 	else { $greet = "Welcome,"; }
	// 	if($ul_status==1||$ul_status==2){
	// 		$msg = 'Please be informed that the e-Portal Data Synchronization has been '.strtolower($arr_ul_status[$ul_status]).'.';
	// 	}
	// 	else{
	// 		$msg = 'Please be informed that there was an error occured during the e-Portal Data Synchronization.';			
	// 	}

	// 	require_once ('../PHPMailerAutoload.php');

	// 	$mail = new PHPMailer;

	// 	// $mail->SMTPDebug = 3;                               // Enable verbose debug output

	// 	$mail->isSMTP();                                      // Set mailer to use SMTP
	// 	$mail->Host = '192.168.3.254';  // Specify main and backup SMTP servers
	// 	$mail->SMTPAuth = true;                               // Enable SMTP authentication
	// 	$mail->Username = 'issinfoservice';                 // SMTP username
	// 	$mail->Password = 'pmi1234';                           // SMTP password
	// 	$mail->SMTPSecure = '';                            // Enable TLS encryption, `ssl` also accepted
	// 	$mail->Port = 25;                                    // TCP port to connect to

	// 	$mail->setFrom('nouvelarts@gmail.com', 'e-Portal Notification');

	// 	// $mail->addAddress($email_add, $fullname);     // Add a recipient email & fullname
	// 	$mail->addAddress('nouvel.christian.aveno.ayes@gmail.com', 'NC');     // Add a recipient group
	// 	$mail->addAddress('nouvel.christian.aveno.ayes@gmail.com', 'Finance Group');     // Add a recipient group
	// 	// $mail->addAddress('ralatie@pricon.ph', 'Joe User');     // Add a recipient email & fullname
	// 	// $mail->addAddress('ralatie@pricon.ph', 'Finance Group');     // Add a recipient group
	// 	// $mail->addAddress('ellen@example.com');               // Name is optional
	// 	$mail->addReplyTo('info@example.com', 'Information');
	// 	// $mail->addCC('nouvelarts@gmail.com');
	// 	// $mail->addBCC('bcc@example.com');

	// 	// $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
	// 	// $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
	// 	$mail->isHTML(true);                                  // Set email format to HTML

	// 	$mail->Subject = $arr_ul_type[$ul_type].' sync '.strtolower($arr_ul_status[$ul_status]);
		

	// 	$mail->Body    = '<div style="font-family:seoge ui;font-size:12px;color:#444;">'.
	// 						'<div style="font-size:15px;">'.$greet.'</div>'.
	// 						'<br>'.
	// 						'<br>'.
	// 						'<br>'.$msg.
	// 						'<br>'.
	// 						'<br><b>Synchronization details:</b>'.
	// 						'<br>Sync type : '.$arr_ul_type[$ul_type].
	// 						'<br>Sync status : <b style="color:'.$arr_color[$ul_status].';">'.$arr_ul_status[$ul_status].'</b>'.
	// 						'<br>User : <a href="mailto:'.$email_add.'">'.$fullname.' ('.$email_add.')'.'</a>'.
	// 						'<br>Date and time of sync : '.$ul_adate.
	// 					'</div>';

	// 	$mail->AltBody = ' '.$greet.
	// 					' '.
	// 					' '.
	// 					' '.$msg.
	// 					' '.
	// 					' Synchronization details: '.
	// 					' Sync type : '.$arr_ul_type[$ul_type]. 
	// 					' Sync status: '.$arr_ul_status[$ul_status].
	// 					' User: '.$fullname.' ('.$email_add.')'.
	// 					' Date and time of sync '.$ul_adate;

	// 	$return = "";
	// 	if(!$mail->send()) {
	// 	    $return .=  'Message could not be sent.';
	// 	    $return .= 'Mailer Error: ' . $mail->ErrorInfo;
	// 	} else {
	// 	    $return .= 'Message has been sent';
	// 	}
	// 	return $return;
	// }

// function send_email_now($fullname, $email_add, $ul_type, $ul_status, $ul_adate){
// 	$arr_ul_type = array("Error", "Payroll", "OTKS");
// 	$arr_ul_status = array("Error", "Started", "Completed");

// 	require ('../PHPMailerAutoload.php');

// 	$mail = new PHPMailer;

// 	$mail->SMTPDebug = 3;                               // Enable verbose debug output

// 	$mail->isSMTP();                                      // Set mailer to use SMTP
// 	$mail->Host = '192.168.3.254';  // Specify main and backup SMTP servers
// 	$mail->SMTPAuth = true;                               // Enable SMTP authentication
// 	$mail->Username = 'issinfoservice';                 // SMTP username
// 	$mail->Password = 'pmi1234';                           // SMTP password
// 	$mail->SMTPSecure = '';                            // Enable TLS encryption, `ssl` also accepted
// 	$mail->Port = 25;                                    // TCP port to connect to

// 	$mail->setFrom('nouvel_christian_aveno_ayes@gmail.com', 'e-Portal Notification');

// 	// $mail->addAddress($email_add, $fullname);     // Add a recipient email & fullname
// 	$mail->addAddress('ralatie@pricon.ph', 'Joe User');     // Add a recipient email & fullname
// 	$mail->addAddress('ralatie@pricon.ph', 'Finance Group');     // Add a recipient group
// 	$mail->addAddress('nouvel_christian_aveno_ayes@gmail.com', 'NC');     // Add a recipient group
// 	// $mail->addAddress('ellen@example.com');               // Name is optional
// 	$mail->addReplyTo('info@example.com', 'Information');
// 	$mail->addCC('nouvelarts@gmail.com');
// 	// $mail->addBCC('bcc@example.com');

// 	// $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
// 	// $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
// 	$mail->isHTML(true);                                  // Set email format to HTML

// 	$mail->Subject = $arr_ul_type[$ul_type].' sync '.strtolower($arr_ul_status[$ul_status]).' e-Portal Notification';
// 	$mail->Body    = $arr_ul_type[$ul_type].' sync staus: '.$arr_ul_status[$ul_status].
// 					'<br>User: '.$fullname.' ('.$email_add.')'.
// 					'<br>Date: '.$ul_adate;
// 	$mail->AltBody = $arr_ul_type[$ul_type].' sync staus: '.$arr_ul_status[$ul_status].
// 					' User: '.$fullname.' ('.$email_add.')'.
// 					' Date: '.$ul_adate;

// 	$return = "";
// 	if(!$mail->send()) {
// 	    $return .=  'Message could not be sent.';
// 	    $return .= 'Mailer Error: ' . $mail->ErrorInfo;
// 	} else {
// 	    $return .= 'Message has been sent';
// 	}
// 	return $return;
// }
?>
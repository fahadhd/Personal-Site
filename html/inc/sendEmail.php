<?php

require 'sendgrid-google-php-master/SendGrid_loader.php';
// Replace this with your own email address
$toEmail = 'fha423@gmail.com';
$fromEmail = 'fha0423@gmail.com';

// Connect to your SendGrid account
$sendgrid = new SendGrid\SendGrid('fha423', 'west0423');

// Make a message object
$mail = new SendGrid\Mail();


if($_POST) {

   $name = trim(stripslashes($_POST['contactName']));
   $formEmail = trim(stripslashes($_POST['contactEmail']));
   $subject = trim(stripslashes($_POST['contactSubject']));
   $contact_message = trim(stripslashes($_POST['contactMessage']));

   // Check Name
	if (strlen($name) < 2) {
		$error['name'] = "Please enter your name.";
	}
	// Check Email
	if (!preg_match('/^[a-z0-9&\'\.\-_\+]+@[a-z0-9\-]+\.([a-z0-9\-]+\.)*+[a-z]{2}/is', $formEmail)) {
		$error['email'] = "Please enter a valid email address.";
	}
	// Check Message
	if (strlen($contact_message) < 15) {
		$error['message'] = "Please enter your message. It should have at least 15 characters.";
	}
   // Subject
	if ($subject == '') { $subject = "Contact Form Submission"; }


   // Set Message
   $message .= "Email from: " . $name . "<br />";
   $message .= "Email address: " . $formEmail . "<br />";
   $message .= "Message: <br />";
   $message .= $contact_message;
   $message .= "<br /> ----- <br /> This email was sent from your site's contact form. <br />";


   // Set From: header
   $from =  $name . " <" . $formEmail . ">";

   // Email Headers
	$headers = "From: " . $from . "\r\n";
	$headers .= "Reply-To: ". $formEmail . "\r\n";
 	$headers .= "MIME-Version: 1.0\r\n";
	$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";


   if (!$error) {

	// Adding recipients and other message details
	$mail->
       addTo($toEmail)->
       setFrom($fromEmail)->
       setSubject($subject)->
       setText($message);

	// Use the Web API to send message
	$sendgrid->send($mail);
           
	if ($sendgrid) { echo "OK"; }
      else { echo "Something went wrong. Please try again."; }
		
	} # end if - no validation error

	else {

		$response = (isset($error['name'])) ? $error['name'] . "<br /> \n" : null;
		$response .= (isset($error['email'])) ? $error['email'] . "<br /> \n" : null;
		$response .= (isset($error['message'])) ? $error['message'] . "<br />" : null;
		
		echo $response;

	} # end if - there was a validation error

}

?>
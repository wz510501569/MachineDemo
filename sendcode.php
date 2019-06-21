<?php

$address = isset($_POST['email']) ? $_POST['email'] : '';
if(!$address) {
	echo json_encode(['msg'=>"Email is empty!"]);die;
}
if(strlen($address)>100){
	echo json_encode(['msg'=>"Email is too long!"]);die;
}

$randcode = mt_rand( 100000 , 999999);
$to = $address;
$subject = "This is a Test Email From PHP server";
$message = "Hello! The Verification Code is " . $randcode ;
$from = "wz_student@126.com";
$headers = "From: ".$from;
$result = mail($to,$subject,$message,$headers); // mail function need your self email server. you should open your port 25. the Mercury in xampp is my choice. should configure php.ini file and sendemail.ini file in xampp path

if($result){

	//insert the email and verifiction code into mysql table  "code" , so that we can know whether the code from the user is correct or not .   
	try {
		$dbh = new PDO('mysql:host=127.0.0.1;dbname=test', 'root', '123546'); // change hostname to your own hostname
		$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$dbh->exec('set names utf8');
		$sql = "SELECT * FROM code where email=:email";
		$st= $dbh->prepare($sql);
		$res = $st->execute(array(':email'=>$address));
		$row = $st->fetch(PDO::FETCH_ASSOC);
		if($row){
			$sql = "update code set code=:code where email=:email";  // write like this , can block sql injection Effectively.
		}else{
			$sql = "insert into code values( :email , :code )";
		}
			$st= $dbh->prepare($sql);
			$st->execute(array(':email'=>"$address" , ':code'=>"$randcode"));
			
	} catch (PDOException $e) {
	    die ("Error! : " . $e->getMessage() . "<br/>");
	}


	echo json_encode(['msg'=>"Mail Sent Successfully"]);die;
}else{
	echo json_encode(['msg'=>"Mail Sent Fail,Please Try Again"]);die;
}


//If you get the error message "SMTP server response: 553 We do not relay non-local mail, sorry."
// while sending from PHP go to Mercury under MercuryS -> Connection Control -> "Uncheck Do not Permit SMTP relaying to non-local mail" 
//an check this option. Should fix the problem.



//table code  sql 
/*

CREATE TABLE `code` (
  `email` varchar(100) NOT NULL,
  `code` char(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8


*/
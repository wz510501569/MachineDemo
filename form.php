<?php

//In order to save time , the interviewer ask me to choose some field from the form field , then insert into mysql db.
$firstname = trim($_POST['first-name']);
$lastname = trim($_POST['last-name']);
$email = trim($_POST['email']);
$code = trim($_POST['code']);
$country = trim($_POST['country']);
$password = trim($_POST['password']);
$brief = trim($_POST['brief']);


if(!$firstname) die('First Name can not be empty');
if(!$lastname) die('Last Name can not be empty');
if(!$email) die('Email can not be empty');
if(!$code) die('Code can not be empty');
if(!$country) die('Country can not be empty');
if(!$password) die('Password can not be empty');
if(!$brief) die('Brief can not be empty');

if(strlen($firstname)>200) die('First Name is too long');
if(strlen($lastname)>200) die('First Name is too long');
if(strlen($email>100)) die('Email is too long');
if(!preg_match('/^[\w\-]+@[\w\-]+(\.[a\w\-]+)+$/' , $email)) { die('Email format is incorrect'); }
if(strlen($code)!=6) die('verifiction code format is incorrect');
if(strlen($country)!=1) die('country format is incorrect');
if(strlen($password)>255) die('password is too long'); 

//var_dump($firstname  , $lastname , $email , $code , $country , $password , $brief);die;

try {
	$dbh = new PDO('mysql:host=127.0.0.1;dbname=test', 'root', '123456'); // change hostname to your own hostname
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbh->exec('set names utf8');
	$sql = "SELECT * FROM code where email=:email";    // write like this , can block sql injection Effectively.
	$st= $dbh->prepare($sql);
	$res = $st->execute(array(':email'=>"$email"));
	$row = $st->fetch(PDO::FETCH_ASSOC);
         //var_dump($row);die;
	if($row){
            //check the verification code is correct or not
            if($code !== $row['code'])  die('the verification code is incorrect');  
            //$sql = "delete from code where email=:email"; 
	   //$st= $dbh->prepare($sql);
	   //$st->execute(array(':email'=>"$email"));
	}else{
            die('the verification code is incorrect');
	}
         

         $sql2 = "insert into user (first_name,last_name,email,country,password,brief) VALUES(:firstname,:lastname,:email,:country,:password,:brief)"; 
	$st= $dbh->prepare($sql2);
        $encryptedpwd = substr(md5(md5($password)) , 5,16);// the password should be Encrypt.

        $rt = $st->execute(array( ':firstname'=>"$firstname" ,':lastname'=> "$lastname" ,  ':email'=>"$email" ,':country'=>$country ,':password'=> "$encryptedpwd" ,':brief'=>"$brief"));
        if($rt){
            echo 'success';        
        }else{
            echo 'fail';
        }
		
} catch (PDOException $e) {
    die ("Error! : " . $e->getMessage() . "<br/>");
}










/****
    the table user 

    CREATE TABLE `user` (
      `id` int(10) NOT NULL AUTO_INCREMENT,
      `first_name` varchar(200)  NOT NULL,
      `last_name` varchar(200)  NOT NULL,
      `email` varchar(100)  NOT NULL,
      `country` tinyint(1) NOT NULL,
      `password` varchar(255)  NOT NULL,
      `brief` text  NOT NULL,
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;


****/

?>
<?php
$first=$last=$email=$pass='';
$errfirst=$errlast=$erremail=$errpass=$sucessmessage=$green='';
if($_SERVER["REQUEST_METHOD"]=='POST'){
	//first name
	if(empty($_POST["first"])){
		$errfirst= "First Name Required";
	}elseif(preg_match("/^[a-zA-Z]*$/",clean($_POST["first"]))){
		$first = clean($_POST["first"]);
	}elseif(preg_match("/\\s/",clean($_POST["first"]))){
		$errfirst= "Only first name";
	}else{
		$errfirst= "No numbers and symbols";
	}
	//last name
	if(empty($_POST["last"])){
		$errlast= "Last Name Required";
	}elseif(preg_match("/^[a-zA-Z ]*$/",clean($_POST["last"]))){
		$last = clean($_POST["last"]);
	}else{
		$errlast= "No numbers and symbols";
	}
	//email address
	if(filter_var(clean($_POST["email"]),FILTER_VALIDATE_EMAIL)){
		$email = clean($_POST["email"]);
	}elseif(empty($_POST["email"])){
		$erremail= "Email Required";
	}else{
		$erremail= "Invalid Email Address";
	}

	//password
	if(empty($_POST["password"])){
		$errpass= "Password Required";
	}elseif(preg_match("/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%])[0-9A-Za-z!@#$%]{8,20}$/", clean($_POST["password"]))){
			$green = "Password is good";
			$password = clean($_POST["password"]);
	}else{
		$errpass= "Invalid Password !";
	}
	//form submitted and database connected
	if($first&&$last&&$email&&$password){
		$host='localhost';
		$user='root';
		$pass='';
		try{
			$connect = new PDO("mysql:host=$host;dbname=mysql",$user,$pass);
			$connect->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
			$connect->query("CREATE DATABASE IF NOT EXISTS formdata");
			$connect->query("USE formdata");
			$connect->query('CREATE TABLE formtable(
			first_name VARCHAR(30) NULL,
			last_name VARCHAR(30) NULL,
			email VARCHAR(50) NOT NULL,
			pass VARCHAR(40) NOT NULL,
			id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
			reg_date TIMESTAMP
			)');
			echo "Created the Database and Table !"."<br>";
			$conn = $connect->prepare("INSERT INTO formtable (first_name,last_name,email,pass) 
				VALUES (:first_name,:last_name,:email,:pass)");
			$conn->bindParam(':first_name',$first);
			$conn->bindParam(':last_name',$last);
			$conn->bindParam(':email',$email);
			$passhash = password_hash($password, PASSWORD_DEFAULT);
			$conn->bindParam(':pass',$passhash);
			$conn->execute();
			$sucessmessage = "Form Submitted SuccessFully !"."<br>";
		}catch(PDOException $e){
			die("My Error : ".$e->getMessage());
		}
		$connect=null;
	}	
}
function clean($data){
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Form</title>
</head>
<body>
	<h3><u>REGISTER</u></h3>
	<form action="<?php htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post">
		<input type="text" placeholder="First name" name="first" <?php if($errfirst)echo 'style="border:1px solid red";' ?>>
		<span><font color="red">*<?php echo $errfirst; ?></font></span><br>
		<input type="text" placeholder="Last name" name="last" <?php if($errlast)echo 'style="border:1px solid red";' ?>>
		<span><font color="red">*<?php echo $errlast; ?></font></span><br>
		<input type="text" placeholder="Email Address" name="email" <?php if($erremail)echo 'style="border:1px solid red";' ?>>		
		<span><font color="red">*<?php echo $erremail; ?></font></span><br>
		<input type="text" placeholder="Password" name="password" <?php if($errpass)echo 'style="border:1px solid red";' ?>>
		<span><font color="red">*<?php echo $errpass; ?></font></span><br>
		<em>8-20 Characters of 2 types: Upper, Lower, Numeric, Special</em>	<br>
		<pre><font color="green"><?php echo $green; ?></font></pre>
		<button>Register</button>
		<h1><font color="red"><?php echo $sucessmessage; ?></font></h1>
	</form>
	<a href="">Login with an existing account</a>
	<?php

?>
</body>
</html>

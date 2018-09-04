<?php
$first=$last=$email=$password=$loginsuccess='';
$errfirst=$errlast=$erremail=$errpass=$sucessmessage=$green=$red='';
$host='localhost';
$user='root';
$pass='';
if(isset($_SESSION["name"])||isset($_SESSION["user"])){
	header("location:product.php");
}
if(isset($_POST["Register"])){
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
		$red= "Password is Bad";
	}
	//form submitted and database connected
	if($first&&$last&&$email&&$password){
		try{
			$connect = new PDO("mysql:host=$host;dbname=mysql",$user,$pass);
			$connect->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
			$connect->query("CREATE DATABASE IF NOT EXISTS jsondata");
			$connect->query("USE jsondata");
			$connect->query('CREATE TABLE IF NOT EXISTS formtable(
			first_name VARCHAR(30) NULL,
			last_name VARCHAR(30) NULL,
			email VARCHAR(50) NOT NULL,
			pass VARCHAR(60) NOT NULL,
			id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
			reg_date TIMESTAMP
			)');
			//echo "Created the Database and Table !"."<br>";
			$conne = $connect->prepare('SELECT * FROM formtable WHERE email=:email');
			$conne->execute(array(':email'=>$email));
			$full= $conne->fetch(PDO::FETCH_ASSOC);
			if($conne->rowCount()>0){
				$sucessmessage = "You are already registered";
			}else{
				$conn = $connect->prepare("INSERT INTO formtable (first_name,last_name,email,pass) 
				VALUES (:first_name,:last_name,:email,:pass)");
				$conn->bindParam(':first_name',$first);
				$conn->bindParam(':last_name',$last);
				$conn->bindParam(':email',$email);
				$passhash = password_hash($password, PASSWORD_DEFAULT);
				$conn->bindParam(':pass',$passhash);
				$conn->execute();
				$sucessmessage = "Form Submitted SuccessFully !"."<br>";
			}
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
//login form starts
if(isset($_POST["Login"])){
	if(isset($_SESSION["name"])||isset($_SESSION["user"])){
		header("location:product.php");
	}else{
	session_start();
	$email = isset($_POST['email'])?$_POST['email']:'';
	$password = isset($_POST['password'])?$_POST['password']:'';
	try{
		$connect=new PDO("mysql:host=$host;dbname=jsondata",$user,$pass);
		$connect->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
		$conn = $connect->prepare('SELECT * FROM formtable WHERE email=:email');
		$conn->execute(array(':email'=>$email));
		$full= $conn->fetch(PDO::FETCH_ASSOC);
	if($conn->rowCount()>0){	
		if(password_verify($password,$full['pass'])){
			session_regenerate_id();
			$_SESSION["authorized"]=true;
			$_SESSION["email"] = $full["email"];
			$_SESSION["name"]= $full["first_name"];
			$_SESSION["second"]=$_full["last_name"];
			$_SESSION["date"]=$_full["reg_date"];
			session_write_close();
			$loginsuccess =  $_SESSION['name'] ." ! Login Successful";
			header("location:product.php");

		}else{
			$loginsuccess =  "Login Failed";
		}
	}elseif($_POST['email']=='admin'){
		session_regenerate_id();
		$_SESSION["user"] = 'admin';
		session_write_close();
		$loginsuccess = 'Welcome Admin!';
		header("location:pushdatabase.php");
	}else{
		$loginsuccess =  "Not an existing User";
	}
	}catch(PDOException $e){
		die("My Error : ".$e->getMessage());
	}
	$connect=null;
}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Form</title>
</head>
<body>
	<?php
	if(isset($_GET["action"])=="login")
	{
	?>
	<h3><u>LOGIN</u></h3>
	<form action="<?php $_SERVER['PHP_SELF'] ?>" method="post">
		<input type="text" name="email" placeholder="Email Address">
		<input type="text" name="password" placeholder="Password">
		<button name="Login">Login</button>
	</form>
	<a href="index.php">New User? Register Now</a>
	<h1><font color="green"><?php echo $loginsuccess; ?></font></h1>
	<?php
	}
	else
	{
	?>
	<h3><u>REGISTER</u></h3>
	<form action="<?php htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post">
		<input type="text" placeholder="First name" name="first" <?php if($errfirst)echo 'style="border:1px solid red";' ?> value="<?php echo $first; ?>">
		<span><font color="red">*<?php echo $errfirst; ?></font></span><br>
		<input type="text" placeholder="Last name" name="last" <?php if($errlast)echo 'style="border:1px solid red";' ?> value="<?php echo $last; ?>">
		<span><font color="red">*<?php echo $errlast; ?></font></span><br>
		<input type="text" placeholder="Email Address" name="email" <?php if($erremail)echo 'style="border:1px solid red";' ?> value="<?php echo $email; ?>">		
		<span><font color="red">*<?php echo $erremail; ?></font></span><br>
		<input type="text" placeholder="Password" name="password" <?php if($errpass)echo 'style="border:1px solid red";' ?> value="<?php echo $password; ?>">
		<span><font color="red">*<?php echo $errpass; ?></font></span><br>
		<em>8-20 Characters of 2 types: Upper, Lower, Numeric, Special</em>	<br>
		<pre><font color="green"><?php echo $green; ?></font></pre>
		<pre><font color="red"><?php echo $red; ?></font></pre>
		<button name="Register">Register</button>
		<h1><font color="red"><?php echo $sucessmessage; ?></font></h1>
	</form>
	<a href="index.php?action=login">Login with an existing account</a>
	<?php
	}
	?>
</body>
</html>
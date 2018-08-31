<?php
session_start();
if(!isset($_SESSION["name"])){
	header("location:index.php?action=login");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
</head>
<body>
	<?php
		echo "Welcome ! ".$_SESSION["name"];
		if(isset($_GET['logout'])==1){
			session_destroy();
			header("location:index.php?action=login");
		}
	?>
	<a href="?logout=1">Logout</a>
	<div class="products">
		<ul>
			<li>One</li>
			<li>Two</li>
		</ul>
	</div>
</body>
</html>

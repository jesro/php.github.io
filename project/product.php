<?php
session_start();
if(!isset($_SESSION["name"])&&!isset($_SESSION["user"])){
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
		if(isset($_SESSION["name"])){
		echo "<center><h1><font color='brown'>Welcome ".$_SESSION["name"]."!</font></h1></center>";
		}elseif(isset($_SESSION["user"])){
			echo "<center><h1><font color='brown'>Welcome ".$_SESSION["user"]."!</font></h1></center>";
		}
		if(isset($_GET['logout'])==1){
			session_destroy();
			header("location:index.php?action=login");
		}
	?>
	<div class="products">
		<?php
		if((isset($_GET["action"])=='account')&&isset($_SESSION["name"]))
		{
		?>
		<center><h3>Account Dashboard</h3></center>
		<p><b>Name          : </b><?php echo $_SESSION["name"]." ".$_SESSION["second"] ?></p>
		<p><b>Email Address : </b><?php echo $_SESSION["email"] ?></p>
		<p><b>Account Created On : </b><?php echo $_SESSION["date"] ?></p>
		<ul>
			<li><a href="product.php?act=shopping">My Shopping Lists</a></li>
		</ul>
		<?php
		}elseif((isset($_GET["act"])=='shopping')&&isset($_SESSION["name"]))
		{
		?>
		<center><h3>My Shopping List</h3></center>
		<pre>You have no items in your Shopping List</pre>
		<ul>
			<li><a href="product.php?action=account">Account Dashboard</a></li>
		</ul>
		<?php
		}else{
		?>
		<ul>
			<li><a href="product.php?action=account">Account Dashboard</a></li>
			<li><a href="product.php?act=shopping">My Shopping Lists</a></li>
		</ul>
		<?php
		}
		?>
		<center><a href="?logout=1">Logout</a></center>
	</div>
</body>
</html>
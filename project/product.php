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
		<?php
		$host='localhost';
		$user='root';
		$pass='';
		try{
			$shopconnect = new PDO("mysql:host=$host;dbname=jsondata",$user,$pass);
			$shopconnect->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
			$shopconne = $shopconnect->prepare('SELECT * FROM shoppinglist ORDER BY shopid ASC');
			$shopconne->execute();
			$shopfull= $shopconne->fetchAll(PDO::FETCH_ASSOC);
		if(($shopfull>0)&&($_SESSION["name"]=='jesro')){
			foreach ($shopfull as $row) { 
		?>
		<table><tr>
			<td><img src="<?php echo $row['shopimage'] ?>" alt="product_image" height="50" width="50"></td> 
			<td><pre>SKU: <?php echo $row['shopsku'] ?></pre></td>
			<td><h5><?php echo $row['shopname'] ?></h5></td>
			<td><strong><?php echo $row['shopprice'] ?></strong></td>
		</tr></table>
		<?php }
		}else{
		?>
		<pre>You have no items in your Shopping List</pre>
		<?php
		}
		}catch(PDOException $e){
			die("My Error : ".$e->getMessage());
		}
		$shopconnect=null;
		?>
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
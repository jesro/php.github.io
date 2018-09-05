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

	include("header.html");

	if(isset($_SESSION["name"])){
	
		echo "<center><h1><font color='brown'>Welcome ".$_SESSION["name"]."!</font></h1></center>";
	
	}elseif(isset($_SESSION["user"])){
	
		echo "<center><h1><font color='brown'>Welcome ".$_SESSION["user"]."!</font></h1></center>";
	
	}
	if(isset($_GET['logout'])==1){
	
		session_destroy();
		header("location:index.php?action=login");
	
	}
	
		echo "<div class=\"products\">";
	
	if((isset($_GET["action"])=='account')&&isset($_SESSION["name"])){
	
		echo "<center><h3>Account Dashboard</h3></center>
				<p><b>Name          : </b>". $_SESSION['name']." ".$_SESSION['second'] ."</p>
				<p><b>Email Address : </b>".$_SESSION['email'] ."</p>
				<p><b>Account Created On : </b>". $_SESSION['date'] ."</p>
			<ul>
				<li><a href=\"product.php?act=shopping\">My Shopping Lists</a></li>
			</ul>";
	
	}elseif((isset($_GET["act"])=='shopping')&&isset($_SESSION["name"])){
	
		echo "<center><h3>My Shopping List</h3></center>";
	
		$host='localhost';
		$user='root';
		$pass='';
		try{
				$shopconnect = new PDO("mysql:host=$host;dbname=jsondata",$user,$pass);
				$shopconnect->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
				$shopconnect->query('CREATE TABLE IF NOT EXISTS '. $_SESSION["name"] .' (
				shopimage VARCHAR(255) NOT NULL,
				shopname VARCHAR(255) NOT NULL,
				shopsku VARCHAR(255) NOT NULL,
				shopprice VARCHAR(255) NOT NULL,
				shopid INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
				reg_date TIMESTAMP
				)');
				$shopconne = $shopconnect->prepare('SELECT * FROM '.$_SESSION["name"].' ORDER BY shopid ASC');
				$shopconne->execute();
				$shopfull= $shopconne->fetchAll(PDO::FETCH_ASSOC);
	
			if(($shopfull>0)&&($_SESSION["name"])){
	
				foreach ($shopfull as $row) { 
	
					echo "<table><tr>
							<td class=\"col-lg-3\"><img src=\"".$row['shopimage'] ."\" alt=\"product_image\" height=\"50\" width=\"50\"></td> 
							<td class=\"col-lg-3\"><pre>SKU: ". $row['shopsku']." </pre></td>
							<td class=\"col-lg-4\"><h5>". $row['shopname'] ."</h5></td>
							<td class=\"col-lg-2\"><strong>". $row['shopprice']." </strong></td>
						</tr></table>";
	
				}
	
			}else{
	
			echo "<pre>You have no items in your Shopping List</pre>";
	
			}
	
		}catch(PDOException $e){
	
				die("My Error : ".$e->getMessage());
	
		}
	
			$shopconnect=null;
	
			echo "<ul>
					<li><a href=\"product.php?action=account\">Account Dashboard</a></li>
				</ul>";
	
	}else{
	
			echo "<ul>
					<li><a href=\"product.php?action=account\">Account Dashboard</a></li>
					<li><a href=\"product.php?act=shopping\">My Shopping Lists</a></li>
				</ul>";
	
	}

?>

		<center><a href="?logout=1">Logout</a></center>
	</div>
</body>
</html>
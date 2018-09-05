<?php

	session_start();
	if(isset($_GET['logout'])==1){
		session_destroy();
		header("location:index.php?action=login");
	}

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Push Database</title>
	<link rel="stylesheet" href="style.css">
	<script data-main="libs/config" src="libs/require.js"></script>
</head>
<body>

	<?php include("header.html"); ?>
	
	<nav class="col-lg-2">
  <button class="btn btn-default" data-bind="click:previouspage,enable:prevproduct">Previous</a>
  <button class="btn btn-default" data-bind="click:nextpage,enable:nextproduct">Next</a>
</nav>
	<table>
		<td class="col-lg-2">
			<select class="btn btn-primary dropdown-toggle" data-bind="options:optionz ,value: mychoosepage"></select>
		</td>
	<td class="col-lg-2">
		<label><strong>Filter By</strong></label>
	</td>
	<td class="col-lg-2">
         <select class="btn btn-primary dropdown-toggle" data-bind='options: dropProducts, optionsText: "first", optionsCaption: "Type", value: newlist'> </select>
        </td>
        <td data-bind="with: newlist">
        <select class="btn btn-primary dropdown-toggle" data-bind='options: dropProducts, optionsText: "second", optionsCaption: "Diameter", value: newanotherlist'> </select> 
    </td>

<?php

	if(!isset($_SESSION["email"])&&!isset($_SESSION["user"])){
	
		echo "<td class=\"col-lg-2\"><font color=\"blue\"><a href=\"index.php\">Login/Register</a></font></td>";
	
	}elseif(isset($_SESSION["email"])&&!isset($_SESSION["user"])){
	
		echo "<td class=\"col-lg-2\"><font color=\"blue\">". 'Welcome '.$_SESSION['name'].'!'."</a></font>
		<font color=\"red\"><a href=\"?logout=1\">LOGOUT</a></font></td>";
	
	}elseif((isset($_SESSION["user"])=='admin')&&!isset($_SESSION["email"])){
	
		echo "<td class=\"col-lg-2\"><font color=\"blue\">Welcome Admin !</font>
				<font color=\"red\"><a href=\"?logout=1\">LOGOUT</a></font>
			</td>
				</table>
				<center>
					<h1  class=\"col-lg-12\">Push Product Into Database</h1>
				<form action=\"\" method=\"post\">
					<table>
					<tr>
						<th class=\"col-lg-3\"><label for=\"image\">Image</label></th>
						<th class=\"col-lg-3\"><label for=\"name\">Name</label></th>
						<th class=\"col-lg-3\"><label for=\"sku\">SKU</label></th>
						<th class=\"col-lg-3\"><label for=\"price\">Price</label></th>
					</tr>
					<tr>
						<td class=\"col-lg-3\"><input type=\"text\" name=\"image\" id=\"image\"></td>
						<td class=\"col-lg-3\"><input type=\"text\" name=\"name\" id=\"name\"></td>
						<td class=\"col-lg-3\"><input type=\"text\" name=\"sku\" id=\"sku\"></td>
						<td class=\"col-lg-3\"><input type=\"text\" name=\"price\" id=\"price\"></td>
						<td class=\"col-lg-3\"><input type=\"submit\" class=\"btn btn-default\"></td>
					</tr>
					</table>
				</form>
				</center>";
	
		$image=isset($_POST["image"])?$_POST["image"]:"";
		$name=isset($_POST["name"])?$_POST["name"]:"";
		$sku=isset($_POST["sku"])?$_POST["sku"]:"";
		$price=isset($_POST["price"])?$_POST["price"]:"";
		$host='localhost';
		$user='root';
		$pass='';
		try{
	
				$connect = new PDO("mysql:host=$host;dbname=mysql",$user,$pass);
				$connect->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
				$connect->query("CREATE DATABASE IF NOT EXISTS jsondata");
				$connect->query("USE jsondata");
				$connect->query('CREATE TABLE IF NOT EXISTS jsontable(
				image VARCHAR(255) NOT NULL,
				name VARCHAR(255) NOT NULL,
				sku VARCHAR(255) NOT NULL,
				price VARCHAR(255) NOT NULL,
				id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
				reg_date TIMESTAMP
				)');


	
			if(($image&&$name&&$sku&&$price)!=""){
	
				$conne = $connect->prepare('SELECT * FROM jsontable WHERE image=:image AND name=:name 
					AND sku=:sku AND price=:price');
				$conne->execute(array(':image'=>$image,':name'=>$name,':sku'=>$sku,':price'=>$price));
				$full= $conne->fetch(PDO::FETCH_ASSOC);
	
				if($conne->rowCount()>0){
	
					echo "You are already Stored this Product";
	
				}else{
	
					$conn = $connect->prepare("INSERT INTO jsontable (image,name,sku,price) 
					VALUES (:image,:name,:sku,:price)");
					$conn->bindParam(':image',$image);
					$conn->bindParam(':name',$name);
					$conn->bindParam(':sku',$sku);
					$conn->bindParam(':price',$price);
					$conn->execute();
					echo "<tr><td><center><h4>Products Stored SuccessFully !</h4></center><td></tr>";
	
				}
	
			}else{
	
				echo "<tr><td><center><h4>Nothing Entered!</h4></center><td></tr>";
	
			}
	
		}catch(PDOException $e){
	
				die("My Error : ".$e->getMessage());
	
		}
	
		$connect=null;
	
		echo "<br><hr>";
	}

	if (isset($_GET['delete'])) {
	
			try{
	
				$connect = new PDO("mysql:host=$host;dbname=jsondata",$user,$pass);
				$connect->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
	
	    		$jesid = $_GET['delete'];
	    		$sql = $connect->prepare("DELETE FROM jsontable WHERE id=:id");
	    		$sql->bindParam(':id',$jesid);
	    		$sql->execute();
	   
			}catch(PDOException $e){
	
				die("My Error : ".$e->getMessage());
	
			}
			$connect=null;
	}
	
			$host='localhost';
			$user='root';
			$pass='';
			try{
	
				$connect = new PDO("mysql:host=$host;dbname=jsondata",$user,$pass);
				$connect->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
	
				//json data
				$conn = $connect->prepare("SELECT * FROM jsontable");
				$conn->execute();
				$alldata = $conn->fetchAll(PDO::FETCH_ASSOC);
				$json = json_encode($alldata);
				$jsonfile = fopen("data.json","w") or die("Cannot open!");
				fwrite($jsonfile,$json);
				fclose($jsonfile);
				//end json data
	
				//dropdown data
				$dropconn = $connect->prepare("SELECT * FROM dropdowntable");
				$dropconn->execute();
				$jsons = json_encode($dropconn->fetchAll(PDO::FETCH_ASSOC));
				$jsondrop = fopen("dropdata.json","w") or die("Cannot open!");
				fwrite($jsondrop,$jsons);
				fclose($jsondrop);
				//end dropdown data	
	
			}catch(PDOException $e){
	
				die("My Error : ".$e->getMessage());
	
			}
	
			$connect=null;
		 

	
	
	if(((!isset($_SESSION["user"])=='admin')&&(isset($_SESSION["email"])))||
				((!isset($_SESSION["user"])=='admin')&&(!isset($_SESSION["email"])))){
	
		echo "<table data-bind=\"foreach:pagineation\" id=\"mytable\"  class=\"table table-striped\">	
			<tr>
				<td class=\"col-lg-2\"><img data-bind=\"attr:{src:image}\" alt=\"product_image\" height=\"50\" width=\"50\"></td>
				<td class=\"col-lg-2\"><pre data-bind=\"text:'SKU: '+sku\"></pre></td>
				<td class=\"col-lg-6\"><h5 data-bind=\"text:name\"></h5></td>
				<td class=\"col-lg-2\"><strong data-bind=\"text:price\"></strong></td>";
				
				
		if(isset($_SESSION["email"])){ 
	
			echo "<td class=\"col-lg-2\"><form method=\"get\"><input type=\"button\" class=\"btn btn-default\" data-bind=\"click:shopme\" name=\"shoppinglist\" value=\"ADD TO MY LIST	\"></input></form>
			</td></tr></table>";
	
		}
	}elseif((isset($_SESSION["user"])=='admin')&&!isset($_SESSION["email"])){
	
					foreach ($alldata as $row) { 
	
			echo "
				<table class=\"table table-striped\"><tr>
				<td class=\"col-lg-2\"><img src=\"". $row['image'] ."\" alt=\"product_image\" height=\"50\" width=\"50\"></td>
				<td class=\"col-lg-2\"><pre>SKU: ".  $row['sku'] ."</pre></td>
				<td class=\"col-lg-3\"><h5>". $row['name'] ."</h5></td>
				<td class=\"col-lg-3\"><strong>". $row['price'] ."</strong></td>
				<td class=\"col-lg-1\"><a href=\" edit.php?id=".$row['id'] ."\" >EDIT</a></td>
				<td class=\"col-lg-1\"><a href=\" ?delete=".$row['id'] ."\">DELETE</a></td>
					</tr></table>";
	
				}
	}
			
				
	
		
	
	
	if(isset($_GET["shopname"])&&isset($_SESSION["name"])){
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
	
				$shopimage = $_GET['shopimage'];
				$shopname = $_GET['shopname'];
				$shopsku = $_GET['shopsku'];
				$shopprice = $_GET['shopprice'];
				$shopconne = $shopconnect->prepare('SELECT * FROM '.$_SESSION["name"].' WHERE shopimage=:shopimage AND shopname=:shopname 
					AND shopsku=:shopsku AND shopprice=:shopprice');
				$shopconne->execute(array(':shopimage'=>$shopimage,':shopname'=>$shopname,':shopsku'=>$shopsku,':shopprice'=>$shopprice));
				$shopfull= $shopconne->fetch(PDO::FETCH_ASSOC);
	
				if($shopconne->rowCount()>0){
	
					//echo "<script>alert('YOU ALREADY ADDED THIS PRODUCT')</script>";
	
				}else{
	
	   				$shopconn = $shopconnect->prepare("INSERT INTO ".$_SESSION["name"]." (shopimage,shopname,shopsku,shopprice) 
					VALUES (:shopimage,:shopname,:shopsku,:shopprice)");
					$shopconn->bindParam(':shopimage',$shopimage);
					$shopconn->bindParam(':shopname',$shopname);
					$shopconn->bindParam(':shopsku',$shopsku);
					$shopconn->bindParam(':shopprice',$shopprice);
					$shopconn->execute();
					//echo "<script>alert('PRODUCT ADDED TO YOUR LIST')</script>";
	
				}
	
			}catch(PDOException $e){
	
				die("My Error : ".$e->getMessage());
	
			}
	
			$shopconnect=null;
			echo "<script>window.open('product.php?act=shopping')</script>";
	
	} 

?>

<script>

	require(['config'],function(){

		require(['product']);
		
	});

</script>

</body>
</html>

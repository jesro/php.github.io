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
	<script data-main="libs/config" src="libs/require.js"></script>
</head>
<body>
	<table>
		<td>
			<select data-bind="options:optionz ,value: pagesize"></select>
		</td>
	<td>
		<label><strong>Filter By</strong></label>
	</td>
	<td>
         <select data-bind='options: dropProducts, optionsText: "first", optionsCaption: "Type", value: newlist'> </select>
        </td>
        <td data-bind="with: newlist">
        <select data-bind='options: dropProducts, optionsText: "second", optionsCaption: "Diameter", value: newanotherlist'> </select> 
    </td>
	<?php
	if(!isset($_SESSION["email"])&&!isset($_SESSION["user"])){
		?>
	<td><font color="blue"><a href="index.php">Login/Register</a></font></td>
	<?php
	}elseif(isset($_SESSION["email"])&&!isset($_SESSION["user"])){
	?>
	<td><font color="blue"><?php echo 'Welcome '.$_SESSION["name"]."!"?></a></font>
	<font color="red"><a href="?logout=1">LOGOUT</a></font></td>
	<?php
	}elseif((isset($_SESSION["user"])=='admin')&&!isset($_SESSION["email"])){
	?>
	<td><font color="blue">Welcome Admin !</font>
	<font color="red"><a href="?logout=1">LOGOUT</a></font></td>
	</table>
	<center>
		<h1>Push Product Into Database</h1>
	<form action="" method="post">
		<table>
		<tr>
			<th><label for="image">Image</label></th>
			<th><label for="name">Name</label></th>
			<th><label for="sku">SKU</label></th>
			<th><label for="price">Price</label></th>
		</tr>
		<tr>
			<td><input type="text" name="image" id="image"></td>
			<td><input type="text" name="name" id="name"></td>
			<td><input type="text" name="sku" id="sku"></td>
			<td><input type="text" name="price" id="price"></td>
		</tr>
		<tr>
			<td></td><td></td>
			<td><input type="submit"></td>
		</tr>
		</table>
	</form>
	</center>
	<?php
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
				echo "Products Stored SuccessFully !"."<br>";
			}
		}else{
			echo "Nothing Entered!";
		}
		}catch(PDOException $e){
			die("My Error : ".$e->getMessage());
		}
		$connect=null;
	?>
	<br><hr>
	<?php
	}
	?>
	<?php
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
	 
	?>
	<?php
		if(((!isset($_SESSION["user"])=='admin')&&(isset($_SESSION["email"])))||
			((!isset($_SESSION["user"])=='admin')&&(!isset($_SESSION["email"])))){
	?>
	<table data-bind="foreach:pagineation" id="mytable">	
		<tr>
			<td><img data-bind="attr:{src:image}" alt="product_image" height="50" width="50"></td>
			<td><pre data-bind="text:'SKU: '+sku"></pre></td>
			<td><h5 data-bind="text:name"></h5></td>
			<td><strong data-bind="text:price"></strong></td>
			<!-- <td><span>+<input type="number"><span>-</span></span></td>  -->
			
	<?php if(isset($_SESSION["email"])){ ?>
		<td><button>ADD TO MY SHOPPING LIST</button></td></tr></table>
		<?php }
			}elseif((isset($_SESSION["user"])=='admin')&&!isset($_SESSION["email"])){
				foreach ($alldata as $row) { 
			?>
			<table><tr>
			<td><img src="<?php echo $row['image'] ?>" alt="product_image" height="50" width="50"></td> 
			<td><pre>SKU: <?php echo $row['sku'] ?></pre></td>
			<td><h5><?php echo $row['name'] ?></h5></td>
			<td><strong><?php echo $row['price'] ?></strong></td>
			<td><a href="<?php echo 'edit.php?id='.$row['id']; ?>" target="_blank">EDIT</a></td>
			<td><a href="<?php echo '?delete='.$row['id']; ?>">DELETE</a></td>
			<td><em></em></td>
				</tr></table>
			<?php 
			}
			}
			?>		
			
	<?php
	
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

	?>
	<script>
		require(['config'],function(){
			require(['product']);
		});
	</script>
</body>
</html>

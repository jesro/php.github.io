<?php 
	
	//Open Excel File
	$uploadedfile = $_FILES["fileToUpload"]["name"];
	$file = fopen($uploadedfile,"r");
	$fileread = fread($file,filesize($uploadedfile));
	//print_r(file_get_contents($fileread));
	$array = explode(",",$fileread);
	print_r($array);
	/*foreach($array as $data)
		echo $data;*/
	//echo gettype($fileread);
	//End Excel File


	/*$image=isset($_POST["image"])?$_POST["image"]:"";
	$name=isset($_POST["name"])?$_POST["name"]:"";
	$sku=isset($_POST["sku"])?$_POST["sku"]:"";
	$price=isset($_POST["price"])?$_POST["price"]:"";


	$host='localhost';
	$user='root';
	$pass='';
	
		try{
	
				$connect = new PDO("mysql:host=$host;dbname=jsondata",$user,$pass);
				$connect->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
	
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
	
		$connect=null;*/

?>



<?php
	
	$host='localhost';
	$user='root';
	$pass='';

	$uploadedfilename = isset($_FILES["fileToUpload"]["tmp_name"])?$_FILES["fileToUpload"]["tmp_name"]:"";
	if(isset($_FILES["fileToUpload"]["size"])?$_FILES["fileToUpload"]["size"]:"">0){
		$file = fopen($uploadedfilename,"r");
		while(($fileread = fgetcsv($file,20))!==FALSE){
			try{
  			
  			$connect = new PDO("mysql:host=$host;dbname=jsondata",$user,$pass);
  			$connect->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        	$conn = $connect->prepare("INSERT INTO jsontable (image,name,sku,price) 
				VALUES (:image,:name,:sku,:price)");
				$conn->bindParam(':image',$fileread[0]);
				$conn->bindParam(':name',$fileread[1]);
				$conn->bindParam(':sku',$fileread[2]);
				$conn->bindParam(':price',$fileread[3]);
  				$conn->execute();
  			
    		}catch(PDOException $e){
  
  				die("My Error : ".$e->getMessage());
  
    		}
		}
		echo "File uploaded Succesfully !";
		fclose($file);
	}

			
?>
<html>
<form enctype="multipart/form-data" method="post">
    Select image to upload:
    <input type="file" name="fileToUpload" id="fileToUpload">
    <input type="submit" > 
</form>
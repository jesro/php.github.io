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
				
				//dropdown data
				$dropconn = $connect->prepare("SELECT * FROM dropdowntable");
				$dropconn->execute();
				$jsons = json_encode($dropconn->fetchAll(PDO::FETCH_ASSOC));
				echo json_encode($alldata);
				//end dropdown data	
	echo $json;
			}catch(PDOException $e){
	
				die("My Error : ".$e->getMessage());
	
			}
	
			$connect=null;
?>


var ajax = new XMLHttpRequest();
	var method="POST";
	var url = "get.php";
	var asynchronous = true;

	ajax.open(method, url, asynchronous);
	ajax.send();
	ajax.onreadystatechange = function()
	{
		if(this.readyState = 4 && this.status == 200)
		{
			window.datas = this.responseText;
			
		}
	}
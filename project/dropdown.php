<?php
		$host='localhost';
		$user='root';
		$pass=''; 

		try{

			$connect = new PDO("mysql:host=$host;dbname=jsondata",$user,$pass);
			$connect->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
			$connect->query('CREATE TABLE IF NOT EXISTS dropdowntable(
			first VARCHAR(255) NOT NULL,
			second VARCHAR(255) NOT NULL,
			id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
			reg_date TIMESTAMP
			)');

			$conn = $connect->prepare("INSERT INTO dropdowntable (first,second) 
				VALUES (:first,:second)");
				$conn->bindParam(':first',$one);
				$conn->bindParam(':second',$two);
				$one="Leviton";
				$two="120 Volt";
				$conn->execute();
				$one="3M";
				$two="24 Volt";
				$conn->execute();
				$one="Panasonic";
				$two="6 Inch";
				$conn->execute();
				$one="Hubbell";
				$two="277 Volt";
				$conn->execute();		
				

		}catch(PDOException $e){

			die("My Error : ".$e->getMessage());

		}

		$connect=null;
		
?>
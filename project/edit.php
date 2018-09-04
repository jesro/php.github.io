<?php 
$host='localhost';
		$user='root';
		$pass='';
if (isset($_POST['submit'])) {
  try{
			$connect = new PDO("mysql:host=$host;dbname=jsondata",$user,$pass);
			$connect->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    $sql = $connect->prepare("UPDATE jsontable 
            SET image = :image, 
              sku = :sku, 
              name = :name, 
              price = :price, 
              id = :id,
              reg_date = :reg_date
            WHERE id = :id");
    $sql->execute(array(':image'=>$_POST['image'],':name'=>$_POST['name'],':sku'=>$_POST['sku'],':price'=>$_POST['price'],':id'=>$_POST['id'],':reg_date'=>$_POST['reg_date']));
  }catch(PDOException $e){
			die("My Error : ".$e->getMessage());
  }
  $connect=null;
  echo "<script>window.close();</script>";
}


		  



	if (isset($_GET['id'])) {
		try{
			$connect = new PDO("mysql:host=$host;dbname=jsondata",$user,$pass);
			$connect->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
		//$sql = $connect->prepare("DELETE FROM jsontable WHERE id= :id");
    	//$sql->bindParam(':id',$_GET['delete_id']);
    	$jesid = $_GET['id'];
    	$sql = $connect->prepare("SELECT * FROM jsontable WHERE id=:id");
    	$sql->bindParam(':id',$jesid);
    	$sql->execute();
    	$jesro = $sql->fetch(PDO::FETCH_ASSOC);
   
		}catch(PDOException $e){
			die("My Error : ".$e->getMessage());
		}
		$connect=null;
	 } 
?>
<form method="post">
    <?php foreach ($jesro as $key => $value) : ?>
      <label for="<?php echo $key; ?>"><?php echo ucfirst($key); ?></label>
	    <input type="text" name="<?php echo $key; ?>" id="<?php echo $key; ?>" value="<?php echo $value; ?>" <?php echo ($key === 'id' ? 'readonly' : null); ?>>
    <?php endforeach; ?> 
    <input type="submit" name="submit" value="Submit">
</form>

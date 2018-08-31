<html>
<body>
<? php
$arr=array(
  "firstStudent"=>array(
    "Maths"=>190,
    "Chemistry"=>90,
    "Physics"=>178
  ),
  "secondStudent"=>array(
    "Maths"=>178,
    "Chemistry"=>89,
    "Physics"=>189
  )
);
echo $arr['firstStudent']['Chemistry']."<br>".$arr['secondStudent']['Maths'];
?>
</body>
</html>

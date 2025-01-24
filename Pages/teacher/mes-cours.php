<?php

session_start();
require('../../Classe/Teacher.php');
require('../../config/db.php'); 
$db = new Database();
$conn = $db->getConnection();

$teacher = new Teacher($conn) ;
 $_SESSION['id']=3;
$mes_cours = $teacher->consulterCatalogue();




var_dump($mes_cours) ;




?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
</body>
</html>
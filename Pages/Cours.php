<?php

require('../Classe/Cours.php');
// require('../Classe/Student.php');
// require('../Classe/Teacher.php');
require('../config/db.php');
$db = new Database();
$conn = $db->getConnection();

$coursObject = new Cours($conn) ;
$cours = $coursObject->getALLCours();
// $student = new Student($conn);





echo $cours[0]??'walo' ;




?>
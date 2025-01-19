<?php
 require_once __DIR__ .'/User.php';
class Student extends User
{
    


    public function __construct($pdo)
    {
       $this->pdo = $pdo;
    }



    public function consulterCatalogue() {
        $stmt = $this->pdo->prepare("SELECT * FROM users");
        $stmt->execute();
        $cours = $stmt->fetchAll(PDO::FETCH_ASSOC); 
        return $cours;
    }


    
    



}
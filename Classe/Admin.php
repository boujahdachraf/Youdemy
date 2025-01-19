<?php

require_once __DIR__ . '/User.php';
    class Admin extends User {

        public function __construct($pdo){
            $this->pdo = $pdo;
        }

        public function getAllusers(){
            $stmt = $this->pdo->prepare("SELECT * FROM users");
            $stmt->execute();
            $users = $stmt->fetchAll(PDO::FETCH_ASSOC); 
            return $users;
        }
        public function addCours(){}
        
    }
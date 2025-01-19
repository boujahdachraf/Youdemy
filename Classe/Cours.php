<?php
class Cours
{
    protected $id;
    protected $titre;
    protected $description;
    protected $contenus = [];
    protected $tags = [];
    protected $categorie;



    protected $pdo;


    public function __construct($pdo)
    {
        $this->pdo = $pdo;
        // $this->titre = $titre;
        // $this->description = $description;
        // $this->categorie = $categorie;
    }
    public function getALLCours(){
        $stmt = $this->pdo->prepare("SELECT * FROM Courses");
        $stmt->execute();
        $cours = $stmt->fetchAll(PDO::FETCH_ASSOC); 
        return $cours;
    }
    



}
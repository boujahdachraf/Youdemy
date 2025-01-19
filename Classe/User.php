<?php

abstract class User {
    protected $id;
    protected $username;
    protected $email;
    protected $role;
    protected $password;
    protected $pdo;

    public function __construct($pdo ,$id = null, $username = null, $email = null, $role = null, $password = null) {
        $this->pdo = $pdo;
        $this->id = $id;
        $this->username = $username;
        $this->email = $email;
        $this->role = $role;
        $this->password = $password;
    }

    public function seDéconnecter() {
        session_start();
        session_unset();
        session_destroy();
    }

    public  function login( $email, $password) {
        $stmt = $this->pdo->prepare("SELECT * FROM User WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['mot_de_passe'])) {
            session_start();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_role'] = $user['role'];
            $_SESSION['user_email'] = $user['email'];
            return true;
        } else {
            throw new Exception("Échec de la connexion. Vérifiez vos identifiants.");
        }
    }

    public function afficherProfil() {
        echo "username: $this->username<br>";
        echo "Email: $this->email<br>";
        echo "role: $this->role<br>";
    }

    public function consulterCatalogue() {
        $stmt = $this->pdo->prepare("SELECT * FROM Cours");
        $stmt->execute();
    }

    public function rechercherCours($motCle) {
        $stmt = $this->pdo->prepare("SELECT * FROM Cours WHERE titre LIKE :motCle OR description LIKE :motCle");
        $stmt->execute(['motCle' => "%$motCle%"]);
        while ($row = $stmt->fetch()) {
            echo "Titre: " . $row["titre"] . " - Description: " . $row["description"] . "<br>";
        }
    }
    public function changeUserStatu($id,$status){
        $stmt = $this->pdo->prepare("UPDATE users SET status = :status WHERE id = :id");
        $stmt->execute([
            ':status' => $status, 
            ':id' => $id 
        ]);
    }

    public function getId(): int {
        return $this->id;
    }

    public function getusername(): string {
        return $this->username;
    }

    public function getEmail(): string {
        return $this->email;
    }

    public function getRole(): string {
        return $this->role;
    }

    public function setusername(string $username): void {
        $this->username = $username;
    }

    public function setEmail(string $email): void {
        $this->email = $email;
    }

    public function setMotDePasse(string $motDePasse): void {
        $this->password = $motDePasse;
    }

    public function setRole(string $role): void {
        $this->role = $role;
    }

    public static function create($conn, $username, $email, $role, $password, $status) {
        try {
            $passwordHash = password_hash($password, PASSWORD_BCRYPT);
            
            $checkQuery = "SELECT * FROM Users WHERE email = :email";
            $stmt = $conn->prepare($checkQuery);
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
            if ($result) {
                return "User already exist.";
            }
    
            $insertQuery = "INSERT INTO Users (username, email, role, password, status) VALUES (:username, :email, :role, :password, :status)";
            $stmt = $conn->prepare($insertQuery);
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':role', $role);
            $stmt->bindParam(':password', $passwordHash);
            $stmt->bindParam(':status', $status);
    
            if ($stmt->execute()) {
                return true;
            } else {    
                throw new Exception("Erreur  " . $stmt->errorInfo()[2]);
            }
        } catch (Exception $e) {
            throw new Exception("Erreur " . $e->getMessage());
        }
    }
}
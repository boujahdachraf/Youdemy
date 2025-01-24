<?php

abstract class Users {
    protected $db;
    protected $id;
    protected $username;
    protected $email;
    protected $role;
    protected $is_active;
    protected $status;

    public function __construct($db, $userData = null) {
        $this->db = $db;
        if ($userData && isset($userData['user_id'])) {
            $this->id = $userData['user_id'];
            $this->username = $userData['username'] ?? null;
            $this->email = $userData['email'] ?? null;
            $this->is_active = $userData['is_active'] ?? 0;
            $this->status = $userData['status'] ?? 'pending';
    }
    }
    public function create($data) {
        $query = "INSERT INTO users (username, email, password, role, is_active, status) 
                  VALUES (:username, :email, :password, :role, :is_active, :status)";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':username', $data['username']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':password', password_hash($data['password'], PASSWORD_BCRYPT));
        $stmt->bindParam(':role', $data['role']);
        $stmt->bindParam(':is_active', $data['is_active']);
        $stmt->bindParam(':status', $data['status']);
        
        return $stmt->execute();
    }

    public function read($id) {
        $query = "SELECT * FROM users WHERE user_id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($id, $data) {
        if (!is_array($data)) {
            $data = json_decode($data, true); // conv JSON to a table if necessary
        }
        if (!is_array($data)) {
            die("Erreur : \$data n'est pas un tableau valide.");
        }
    
        $updateFields = [];
        $params = [];
    
        if (isset($data['username'])) {
            $updateFields[] = "username = :username";
            $params[':username'] = $data['username'];
        }
        if (isset($data['email'])) {
            $updateFields[] = "email = :email";
            $params[':email'] = $data['email'];
        }
        if (isset($data['is_active'])) {
            $updateFields[] = "is_active = :is_active";
            $params[':is_active'] = $data['is_active'];
        }
        if (isset($data['status'])) {
            $updateFields[] = "status = :status";
            $params[':status'] = $data['status'];
        }
    
        if (empty($updateFields)) {
            return true;
        }
    
        $query = "UPDATE users SET " . implode(', ', $updateFields) . " WHERE user_id = :id";
        $params[':id'] = $id;
    
        $stmt = $this->db->prepare($query);
        return $stmt->execute($params);
    }
    

    public function delete($id) {
        $query = "DELETE FROM users WHERE user_id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function getAll() {
        $query = "SELECT * FROM users WHERE role = :role";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':role', $this->role);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getRole() { return $this->role; }
    public function getId() { return $this->id; }
    public function getUsername() { return $this->username; }
    public function getEmail() { return $this->email; }
    public function getStatus() { return $this->status; }
    public function isActive() { return $this->is_active; }

    abstract public function getData();
}

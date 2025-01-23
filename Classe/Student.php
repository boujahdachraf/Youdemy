<?php
class Student extends Users {
    public function __construct($db, $userData = null) {
        parent::__construct($db, $userData);
        $this->role = 'student';
    }

    // Getting enrolled courses here
    public function getData() {
        $query = "SELECT 
            c.course_id,
            c.title,
            c.description,
            c.created_at,
            c.category_id,
            c.teacher_id,
            e.enrolled_at,
            u.username as teacher_name,
            cat.name as category_name,
            GROUP_CONCAT(t.name) as tags
        FROM courses c 
        LEFT JOIN categories cat ON c.category_id = cat.category_id 
        JOIN enrollments e ON c.course_id = e.course_id 
        LEFT JOIN users u ON c.teacher_id = u.user_id
        LEFT JOIN course_tags ct ON c.course_id = ct.course_id
        LEFT JOIN tags t ON ct.tag_id = t.tag_id
        WHERE e.student_id = :student_id
        GROUP BY c.course_id, c.title, c.description,
                 c.created_at, c.category_id, c.teacher_id, 
                 e.enrolled_at, u.username, cat.name";
            
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':student_id', $this->id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get available courses (not enrolled and accepted by admin)
    public function getAvailableCourses($search = '') {
        $query = "SELECT c.*, u.username as teacher_name, 
                  cat.name as category_name,
                  GROUP_CONCAT(t.name) as tags
                  FROM courses c 
                  LEFT JOIN categories cat ON c.category_id = cat.category_id
                  LEFT JOIN users u ON c.teacher_id = u.user_id
                  LEFT JOIN course_tags ct ON c.course_id = ct.course_id
                  LEFT JOIN tags t ON ct.tag_id = t.tag_id
                  WHERE c.status = 'accepted' 
                  AND c.course_id NOT IN (
                    SELECT course_id FROM enrollments WHERE student_id = :student_id
                  )";
        
        if (!empty($search)) {
            $query .= " AND (c.description LIKE :search 
                       OR c.title LIKE :search 
                       OR u.username LIKE :search 
                       OR t.name LIKE :search
                       OR cat.name LIKE :search)";
        }
        
        $query .= " GROUP BY c.course_id";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':student_id', $this->id);
        
        if (!empty($search)) {
            $searchTerm = "%$search%";
            $stmt->bindParam(':search', $searchTerm);
        }
        
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Enroll in a course
    public function viewEnrolledCourses($courseId) {
        $query = "INSERT INTO enrollments (student_id, course_id) VALUES (:student_id, :course_id)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':student_id', $this->id);
        $stmt->bindParam(':course_id', $courseId);
        return $stmt->execute();
    }

    // Get course content (only iiiiif enrolled)
    public function getCourses($courseId) {
        $query = "SELECT c.* FROM courses c
                  JOIN enrollments e ON c.course_id = e.course_id
                  WHERE e.student_id = :student_id AND c.course_id = :course_id";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':student_id', $this->id);
        $stmt->bindParam(':course_id', $courseId);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Check if enrolled in a course para
    public function enrolled($courseId) {
        $query = "SELECT COUNT(*) FROM enrollments 
                  WHERE student_id = :student_id AND course_id = :course_id";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':student_id', $this->id);
        $stmt->bindParam(':course_id', $courseId);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }
}
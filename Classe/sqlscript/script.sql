CREATE TABLE users (
  user_id int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
  username varchar(50) NOT NULL,
  email varchar(100) NOT NULL,
  password varchar(255) NOT NULL,
  role enum('student','teacher','admin') NOT NULL,
  is_active tinyint(1) DEFAULT 1,
  created_at timestamp NOT NULL DEFAULT current_timestamp(),
  status enum('in_progress','accepted','refused') DEFAULT 'in_progress'
);

CREATE TABLE categories (
  category_id int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
  name varchar(50) NOT NULL,
  created_at timestamp NOT NULL DEFAULT current_timestamp()
);

CREATE TABLE tags (
  tag_id int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
  name varchar(50) NOT NULL,
  created_at timestamp NOT NULL DEFAULT current_timestamp()
);

CREATE TABLE courses (
  course_id int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
  title varchar(100) NOT NULL,
  description text DEFAULT NULL,
  thumbnail_url varchar(255) DEFAULT NULL,
  difficulty_level enum('beginner','intermediate','experienced') NOT NULL,
  duration_type enum('hours','minutes','days','weeks') NOT NULL,
  duration_value int(11) NOT NULL,
  content_type enum('document','video') NOT NULL,
  teacher_id int(11) NOT NULL,
  category_id int(11) NOT NULL,
  document_pages int(11) DEFAULT NULL,
  video_length int(11) DEFAULT NULL,
  video_url varchar(255) DEFAULT NULL,
  status enum('in_progress','accepted','refused') DEFAULT 'in_progress',
  created_at timestamp NOT NULL DEFAULT current_timestamp(),
  updated_at timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  document_url varchar(255) DEFAULT NULL,
  tag_id int(11) NOT NULL,
  FOREIGN KEY (teacher_id) REFERENCES users(user_id),
  FOREIGN KEY (category_id) REFERENCES categories(category_id),
  FOREIGN KEY (tag_id) REFERENCES tags(tag_id)
);

CREATE TABLE course_tags (
  course_id int(11) NOT NULL,
  tag_id int(11) NOT NULL,
  FOREIGN KEY (course_id) REFERENCES courses(course_id),
  FOREIGN KEY (tag_id) REFERENCES tags(tag_id)
);

CREATE TABLE enrollments (
  enrollment_id int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
  student_id int(11) NOT NULL,
  course_id int(11) NOT NULL,
  enrolled_at timestamp NOT NULL DEFAULT current_timestamp(),
  last_accessed timestamp NULL DEFAULT NULL,
  FOREIGN KEY (course_id) REFERENCES courses(course_id),
  FOREIGN KEY (student_id) REFERENCES users(user_id)
);
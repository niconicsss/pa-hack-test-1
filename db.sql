CREATE DATABASE digital_training;
USE digital_training;


CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('learner', 'admin') DEFAULT 'learner',
    company_id INT,  -- NEW: Associate user with company
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (company_id) REFERENCES companies(id) ON DELETE SET NULL
);

CREATE TABLE courses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    category VARCHAR(100),
    level ENUM('beginner', 'intermediate', 'advanced'),
    video_url VARCHAR(255),        -- Link to the course video
    image_url VARCHAR(255),        -- Image representing the course
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
CREATE TABLE quizzes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    course_id INT NOT NULL,
    question TEXT NOT NULL,
    option_1 VARCHAR(255) NOT NULL,
    option_2 VARCHAR(255) NOT NULL,
    option_3 VARCHAR(255),
    option_4 VARCHAR(255),
    correct_option INT NOT NULL,   -- Refers to the correct answer (1, 2, 3, or 4)
    FOREIGN KEY (course_id) REFERENCES courses(id)
);
CREATE TABLE quiz_responses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    quiz_id INT NOT NULL,
    selected_option INT NOT NULL,
    is_correct BOOLEAN,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (quiz_id) REFERENCES quizzes(id)
);
CREATE TABLE progress (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    course_id INT NOT NULL,
    status ENUM('in-progress', 'completed') DEFAULT 'in-progress',
    last_accessed TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (course_id) REFERENCES courses(id)
);
CREATE TABLE certifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    course_id INT NOT NULL,
    certificate_url VARCHAR(255),  -- URL or file path for the digital certificate
    awarded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (course_id) REFERENCES courses(id)
);

CREATE TABLE forum_threads (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE forum_replies (
    id INT AUTO_INCREMENT PRIMARY KEY,
    thread_id INT NOT NULL,
    user_id INT NOT NULL,
    content TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (thread_id) REFERENCES forum_threads(id),
    FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE companies (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL UNIQUE
);

CREATE TABLE company_courses (
    company_id INT NOT NULL,
    course_id INT NOT NULL,
    PRIMARY KEY (company_id, course_id),
    FOREIGN KEY (company_id) REFERENCES companies(id) ON DELETE CASCADE,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE
);

INSERT INTO courses (title, description, category, level, video_url, image_url) VALUES
('Logistics 101', 'Introduction to logistics and supply chain management', 'Logistics', 'beginner', 'http://example.com/video1', 'http://example.com/image1'),
('Advanced Shipping Techniques', 'Learn advanced shipping and delivery techniques', 'Shipping', 'intermediate', 'http://example.com/video2', 'http://example.com/image2'),
('Warehousing Essentials', 'Fundamentals of warehouse management and operations', 'Warehousing', 'beginner', 'http://example.com/video3', 'http://example.com/image3');
INSERT INTO courses (title, description, category, level, video_url, image_url) VALUES
('Customs Procedures', 'Learn the essentials of customs processes and documentation', 'Logistics', 'intermediate', 'http://example.com/video4', 'http://example.com/image4'),
('Cold Chain Logistics', 'Managing temperature-sensitive logistics', 'Logistics', 'advanced', 'http://example.com/video5', 'http://example.com/image5'),
('E-commerce Shipping Solutions', 'Learn to handle logistics for e-commerce businesses', 'Logistics', 'beginner', 'http://example.com/video6', 'http://example.com/image6');

-- 2GO Group, Inc. (company_id = 1)
INSERT INTO company_courses (company_id, course_id) VALUES
(1, 1),  -- Logistics 101
(1, 2),  -- Advanced Shipping Techniques
(1, 3);  -- Warehousing Essentials

-- LBC Express, Inc. (company_id = 2)
INSERT INTO company_courses (company_id, course_id) VALUES
(2, 1),  -- Logistics 101
(2, 4),  -- Customs Procedures
(2, 5);  -- Cold Chain Logistics

-- JRS Express (company_id = 3)
INSERT INTO company_courses (company_id, course_id) VALUES
(3, 2),  -- Advanced Shipping Techniques
(3, 6);  -- E-commerce Shipping Solutions

-- Airspeed International Corporation (company_id = 4)
INSERT INTO company_courses (company_id, course_id) VALUES
(4, 3),  -- Warehousing Essentials
(4, 5);  -- Cold Chain Logistics

-- F2 Logistics Philippines, Inc. (company_id = 5)
INSERT INTO company_courses (company_id, course_id) VALUES
(5, 1),  -- Logistics 101
(5, 4);  -- Customs Procedures

-- Gothong Southern (company_id = 6)
INSERT INTO company_courses (company_id, course_id) VALUES
(6, 2),  -- Advanced Shipping Techniques
(6, 3);  -- Warehousing Essentials


INSERT INTO companies (name) VALUES
('2GO Group, Inc.'),
('LBC Express, Inc.'),
('JRS Express'),
('Airspeed International Corporation'),
('F2 Logistics Philippines, Inc.'),
('Gothong Southern');

INSERT INTO companies (name, email_domain) VALUES
('2GO Group, Inc.', '2go.com'),
('LBC Express, Inc.', 'lbc.com'),
('JRS Express', 'jrs.com'),
('Airspeed International Corporation', 'air.ph'),
('F2 Logistics Philippines, Inc.', 'f2.com'),
('Gothong Southern', 'gothong.com');


drop table companies;
drop database digital_training;
select * from users;
select * from companies;
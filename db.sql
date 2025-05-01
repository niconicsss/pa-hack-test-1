CREATE DATABASE digital_training;
USE digital_training;

CREATE TABLE companies (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL UNIQUE,
    email_domain varchar (255) not null
);

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

INSERT INTO companies (name, email_domain) VALUES
('2GO Group, Inc.', '2go.com'),
('LBC Express, Inc.', 'lbc.com'),
('JRS Express', 'jrs.com'),
('Airspeed International Corporation', 'air.ph'),
('F2 Logistics Philippines, Inc.', 'f2.com'),
('Gothong Southern', 'gothong.com');

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

-- questions--
INSERT INTO quizzes (course_id, question, option_1, option_2, option_3, option_4, correct_option) 
VALUES
(1, 'What is the primary goal of logistics?', 'Efficient product delivery', 'Minimizing production cost', 'Maximizing stock levels', 'Reducing employee numbers', 1),
(1, 'Which of the following is a key function of logistics?', 'Handling customer complaints', 'Managing transportation and storage of goods', 'Marketing products', 'Manufacturing products', 2),
(1, 'What does "supply chain" refer to?', 'A network of organizations involved in the production and delivery of goods', 'A marketing strategy', 'A method of customer service', 'A logistics cost strategy', 1),
(1, 'Which of the following is NOT part of the logistics process?', 'Production', 'Inventory management', 'Warehousing', 'Transportation', 1),
(1, 'What is the term for the flow of goods from the point of origin to the final customer?', 'Logistics', 'Production', 'Reverse logistics', 'Inventory management', 1);

INSERT INTO quizzes (course_id, question, option_1, option_2, option_3, option_4, correct_option) 
VALUES
(2, 'What is a key challenge in advanced shipping techniques?', 'Cost reduction in production', 'Timely and efficient delivery', 'Finding warehouse space', 'Marketing strategies for logistics', 2),
(2, 'Which shipping method is most suitable for perishable goods?', 'Air freight', 'Ground transport', 'Ocean freight', 'Rail transport', 1),
(2, 'What does "shipping lane" refer to?', 'The path taken by trucks within a warehouse', 'A specific route used by ships for international trade', 'The location where goods are stored', 'A guideline for efficient logistics', 2),
(2, 'What is a major factor in choosing a shipping method?', 'The distance to the destination', 'The type of product being shipped', 'The size of the shipment', 'All of the above', 4),
(2, 'Which shipping term refers to the cost of shipping goods from one location to another?', 'Freight charge', 'Tariff', 'Customs fee', 'Handling fee', 1);

INSERT INTO quizzes (course_id, question, option_1, option_2, option_3, option_4, correct_option) 
VALUES
(3, 'What is the main purpose of a warehouse?', 'To store inventory until it is needed for production or distribution', 'To handle customer service calls', 'To create promotional materials', 'To manage marketing campaigns', 1),
(3, 'Which of the following is an essential component of warehouse management?', 'Understanding customer demographics', 'Inventory control and management', 'Managing employee vacations', 'Writing product descriptions', 2),
(3, 'What does "FIFO" stand for in warehouse management?', 'First In, First Out', 'Fully Integrated Freight Operations', 'Fully Independent Freight Organization', 'First In, Fully Out', 1),
(3, 'What is the process of organizing inventory to ensure easy access called?', 'Inventory rotation', 'Stock taking', 'Picking', 'Warehousing', 3),
(3, 'What type of warehouse is most suitable for high-demand, low-cost items?', 'Automated warehouse', 'Manual warehouse', 'Distribution center', 'Cold storage warehouse', 3);

INSERT INTO quizzes (course_id, question, option_1, option_2, option_3, option_4, correct_option) 
VALUES
(4, 'What is the primary function of customs procedures?', 'To inspect goods for quality', 'To ensure that goods are taxed properly when crossing borders', 'To store products in warehouses', 'To handle product marketing', 2),
(4, 'Which document is required for international shipping?', 'Sales receipt', 'Commercial invoice', 'Marketing flyer', 'Warehouse inventory', 2),
(4, 'What does "tariff" refer to in customs?', 'A shipping method', 'A fee imposed on goods crossing borders', 'A warehouse storage cost', 'A type of packaging', 2),
(4, 'Which of the following is required for importation?', 'Customs declaration', 'Product catalog', 'Employee list', 'Business cards', 1),
(4, 'What does "import quota" refer to?', 'The maximum amount of goods that can be imported', 'The minimum cost of shipping', 'The total number of items in a warehouse', 'The price of international goods', 1);

INSERT INTO quizzes (course_id, question, option_1, option_2, option_3, option_4, correct_option) 
VALUES
(5, 'What is cold chain logistics used for?', 'Storing frozen foods only', 'Maintaining the temperature of perishable goods throughout transportation', 'Managing inventory in warehouses', 'Creating promotional materials for products', 2),
(5, 'What is a key consideration in cold chain logistics?', 'Temperature control during transit', 'Product branding', 'Marketing of frozen goods', 'Optimizing shipping routes', 1),
(5, 'What is the consequence of breaking the cold chain during transportation?', 'Faster delivery time', 'Increased customer satisfaction', 'Spoilage of perishable goods', 'Lower transportation costs', 3),
(5, 'Which technology is most important in cold chain logistics?', 'GPS tracking', 'Temperature sensors', 'Radio frequency identification (RFID)', 'Warehouse management systems', 2),
(5, 'What is the main challenge of cold chain logistics?', 'Expensive packaging', 'Temperature fluctuations during transportation', 'Long delivery times', 'Lack of transportation vehicles', 2);

INSERT INTO quizzes (course_id, question, option_1, option_2, option_3, option_4, correct_option) 
VALUES
(6, 'What is one key challenge in e-commerce shipping?', 'Managing customer complaints', 'Timely and cost-efficient delivery', 'Handling warehouse staffing', 'Managing online orders', 2),
(6, 'Which shipping method is commonly used in e-commerce for small packages?', 'Ocean freight', 'Air freight', 'Parcel delivery services (e.g., UPS, FedEx)', 'Rail transport', 3),
(6, 'How can e-commerce businesses reduce shipping costs?', 'By using a single carrier for all shipments', 'By increasing the size of orders', 'By using ground transportation for faster deliveries', 'By optimizing packaging to reduce weight and size', 4),
(6, 'What does "last mile delivery" refer to?', 'The first step in shipping', 'The final step in delivering goods to customers', 'The middle stage of shipping', 'The process of storing goods in warehouses', 2),
(6, 'Which of the following is NOT a benefit of e-commerce shipping solutions?', 'Faster delivery times', 'Lower shipping costs', 'Better customer service', 'Limited delivery options', 4);



drop table companies;
drop database digital_training;
select * from users;
select * from companies;
select * from quizzes;
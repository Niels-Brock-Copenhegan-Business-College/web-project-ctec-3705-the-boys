-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 27, 2026 at 08:37 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `student_course_hub`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(10) UNSIGNED NOT NULL,
  `username` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `secret_code_hash` varchar(255) DEFAULT NULL,
  `secret_code_set_at` datetime DEFAULT NULL,
  `login_attempts` tinyint(4) NOT NULL DEFAULT 0,
  `locked_until` datetime DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password_hash`, `avatar`, `secret_code_hash`, `secret_code_set_at`, `login_attempts`, `locked_until`, `is_active`) VALUES
(2, 'Chitraranjan', '$2y$10$ictXo3/apKjwJpT7SiFwD.E5FR2fAtsZicZNCGvWsY/K.xqyANaj2', NULL, NULL, NULL, 0, NULL, 1),
(7, 'Chitra', '$2y$10$NwgU.BVukLuqNnixZif5rO/MXheKMhKxOD0DXhhravqMA4Vmlql22', 'uploads/admins/admin_7_1779827018.jpg', '$2y$10$vYAjWHnQSf2myroBLP8rZ.OSi6DZ7kbRIUZmlfe/DrTFhA7/syJKy', '2026-05-26 12:51:38', 0, NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `admin_password_resets`
--

CREATE TABLE `admin_password_resets` (
  `id` int(10) UNSIGNED NOT NULL,
  `admin_id` int(10) UNSIGNED NOT NULL,
  `token_hash` char(64) NOT NULL,
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `expires_at` datetime NOT NULL,
  `used_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admin_password_resets`
--

INSERT INTO `admin_password_resets` (`id`, `admin_id`, `token_hash`, `created_by`, `created_at`, `expires_at`, `used_at`) VALUES
(4, 7, '92ea7cbb64819540340137146541b3a853b3d6679eb4554abdd85519beb87ede', 1, '2026-05-26 12:51:00', '2026-05-26 14:51:00', '2026-05-26 12:51:38');

-- --------------------------------------------------------

--
-- Table structure for table `audit_logs`
--

CREATE TABLE `audit_logs` (
  `id` int(10) UNSIGNED NOT NULL,
  `created_at` datetime NOT NULL,
  `level` varchar(20) NOT NULL,
  `message` text NOT NULL,
  `context` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`context`)),
  `ip` varchar(45) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `audit_logs`
--

INSERT INTO `audit_logs` (`id`, `created_at`, `level`, `message`, `context`, `ip`, `created_by`) VALUES
(2, '2026-05-24 22:35:18', 'warning', 'Failed login attempt', '{\"area\":\"/Student_Course_Hub/web-project-ctec-3705-the-boys/public\",\"username\":\"Arvind\",\"ip\":\"::1\",\"method\":\"POST\",\"path\":\"/Student_Course_Hub/web-project-ctec-3705-the-boys/public/login\"}', '::1', NULL),
(3, '2026-05-24 22:38:42', 'warning', 'Wrong admin secret code for module delete', '{\"admin_id\":3,\"module_id\":0,\"ip\":\"::1\"}', '::1', 3),
(4, '2026-05-24 22:38:51', 'warning', 'Wrong admin secret code for module delete', '{\"admin_id\":3,\"module_id\":0,\"ip\":\"::1\"}', '::1', 3),
(5, '2026-05-24 22:38:59', 'warning', 'Admin deleted programme', '{\"admin_id\":3,\"programme_id\":13,\"ip\":\"::1\"}', '::1', 3),
(6, '2026-05-24 22:39:30', 'warning', 'Wrong admin secret code for module delete', '{\"admin_id\":3,\"module_id\":0,\"ip\":\"::1\"}', '::1', 3),
(7, '2026-05-24 22:39:36', 'warning', 'Admin deleted module', '{\"admin_id\":3,\"module_id\":47,\"ip\":\"::1\"}', '::1', 3),
(8, '2026-05-25 00:12:10', 'warning', 'Admin deleted module', '{\"admin_id\":3,\"module_id\":48,\"ip\":\"::1\"}', '::1', 3),
(9, '2026-05-25 11:03:51', 'warning', 'Super admin changed admin active status', '{\"superadmin_id\":1,\"admin_id\":2,\"username\":\"Chitraranjan\",\"new_status\":0,\"ip\":\"::1\"}', '::1', 2),
(10, '2026-05-26 11:13:15', 'warning', 'Admin deleted module', '{\"admin_id\":3,\"module_id\":49,\"ip\":\"::1\"}', '::1', 3),
(12, '2026-05-26 11:27:35', 'warning', 'Admin deleted module', '{\"admin_id\":3,\"module_id\":50,\"ip\":\"::1\"}', '::1', 3),
(13, '2026-05-26 11:30:43', 'warning', 'Admin deleted module', '{\"admin_id\":3,\"module_id\":52,\"ip\":\"::1\"}', '::1', 3),
(14, '2026-05-26 11:40:54', 'warning', 'Wrong admin secret code for module delete', '{\"admin_id\":3,\"module_id\":0,\"ip\":\"::1\"}', '::1', 3),
(15, '2026-05-26 11:41:33', 'warning', 'Admin deleted module', '{\"admin_id\":3,\"module_id\":53,\"ip\":\"::1\"}', '::1', 3),
(16, '2026-05-26 11:42:31', 'warning', 'Super admin hard deleted admin', '{\"superadmin_id\":1,\"admin_id\":1,\"username\":\"admin\",\"ip\":\"::1\"}', '::1', 1),
(17, '2026-05-26 11:58:48', 'warning', 'Super admin hard deleted admin', '{\"superadmin_id\":1,\"admin_id\":4,\"username\":\"Niranjan\",\"ip\":\"::1\"}', '::1', 4),
(18, '2026-05-26 12:01:01', 'warning', 'Super admin hard deleted admin', '{\"superadmin_id\":1,\"admin_id\":5,\"username\":\"Niranjan\",\"ip\":\"::1\"}', '::1', 5),
(19, '2026-05-26 12:10:19', 'warning', 'Super admin changed admin active status', '{\"superadmin_id\":1,\"admin_id\":2,\"username\":\"Chitraranjan\",\"new_status\":1,\"ip\":\"::1\"}', '::1', 2),
(20, '2026-05-26 12:48:00', 'warning', 'Super admin hard deleted admin', '{\"superadmin_id\":1,\"admin_id\":3,\"username\":\"Arvind\",\"ip\":\"::1\"}', '::1', 3),
(21, '2026-05-27 01:10:11', 'warning', 'Failed login attempt', '{\"area\":\"/Student_Course_Hub/web-project-ctec-3705-the-boys/public\",\"username\":\"Niranjan\",\"staff_id\":1,\"ip\":\"::1\",\"method\":\"POST\",\"path\":\"/Student_Course_Hub/web-project-ctec-3705-the-boys/public/login\"}', '::1', 1),
(22, '2026-05-27 01:13:09', 'warning', 'Failed login attempt', '{\"area\":\"/Student_Course_Hub/web-project-ctec-3705-the-boys/public\",\"username\":\"Chitra\",\"admin_id\":7,\"ip\":\"::1\",\"method\":\"POST\",\"path\":\"/Student_Course_Hub/web-project-ctec-3705-the-boys/public/login\"}', '::1', 7),
(23, '2026-05-27 01:33:39', 'warning', 'Failed login attempt', '{\"area\":\"/Student_Course_Hub/web-project-ctec-3705-the-boys/public\",\"username\":\"Chitra\",\"admin_id\":7,\"ip\":\"::1\",\"method\":\"POST\",\"path\":\"/Student_Course_Hub/web-project-ctec-3705-the-boys/public/login\"}', '::1', 7);

-- --------------------------------------------------------

--
-- Table structure for table `interest_registrations`
--

CREATE TABLE `interest_registrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `programme_id` int(10) UNSIGNED NOT NULL,
  `withdraw_token` char(64) NOT NULL,
  `registered_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `interest_registrations`
--

INSERT INTO `interest_registrations` (`id`, `first_name`, `last_name`, `email`, `programme_id`, `withdraw_token`, `registered_at`) VALUES
(1, 'Emily', 'Carter', 'emily.carter@example.com', 1, '6f3e2893fbdc9052a8e17c463644351c751234a25b2e7c184d1a5198438ebd94', '2026-05-13 20:21:42'),
(2, 'James', 'Walker', 'james.walker@example.com', 3, '52680d0d71dc488cb4178578c4c3c3fc35d756590cb61bbb4e6e103b4ede8894', '2026-05-13 20:21:42'),
(3, 'Aisha', 'Khan', 'aisha.khan@example.com', 5, 'e2ac95bfe381b8eb49a3dc977536de6e6814e2cd036182394bde226fb16b4dad', '2026-05-13 20:21:42'),
(4, 'Daniel', 'Smith', 'daniel.smith@example.com', 9, '53c788b75a9f30e93def369717fb07a20d4adaf336570f95bb738dca11b15483', '2026-05-13 20:21:42'),
(5, 'Sofia', 'Ali', 'sofia.ali@example.com', 12, '8a038a3083bdc338b774a4e5e9a56b6b84e9bfdb85bd06c10f37fa9678f7beee', '2026-05-13 20:21:42'),
(6, 'Chitraranjan', 'Yadav', 'chitra123@gmail.com', 1, 'd4b86886b0c7561c010613997f1a27b39dfc1e5cf2cabd01f2b89518d53f6599', '2026-05-13 21:23:42');

-- --------------------------------------------------------

--
-- Table structure for table `modules`
--

CREATE TABLE `modules` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `credits` tinyint(3) UNSIGNED NOT NULL DEFAULT 20,
  `photo` varchar(255) DEFAULT NULL,
  `year_of_study` tinyint(3) UNSIGNED DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `modules`
--

INSERT INTO `modules` (`id`, `title`, `description`, `credits`, `photo`, `year_of_study`, `created_at`) VALUES
(1, 'Introduction to Programming', 'This module introduces students to the foundations of programming using Python. It covers essential concepts such as variables, data types, control flow, functions, and basic data structures. Students learn how to write simple programs, solve computational problems, and understand how programming logic works. The module builds confidence in coding and prepares learners for more advanced software development topics.', 20, NULL, 1, '2026-05-13 20:06:26'),
(2, 'Mathematics for Computing', 'This module provides the mathematical foundations required in computer science. Students explore logic, sets, discrete structures, and introductory calculus. The focus is on developing analytical thinking and understanding how mathematical principles support algorithms, data structures, and computational models. The module strengthens problem‑solving skills essential for technical subjects and prepares learners for more advanced computing modules.', 20, NULL, 1, '2026-05-13 20:06:26'),
(3, 'Data Structures & Algorithms', 'Students learn core data structures such as arrays, linked lists, stacks, queues, trees, and graphs, along with fundamental algorithms for sorting, searching, and traversal. The module emphasises algorithmic thinking and complexity analysis, helping students evaluate performance and efficiency. Practical exercises reinforce understanding and prepare learners for advanced programming, system design, and technical interviews.', 20, NULL, 2, '2026-05-13 20:06:26'),
(4, 'Software Engineering', 'This module introduces professional software development practices, including agile methodologies, design patterns, version control, and testing strategies. Students learn how to plan, design, build, and maintain software systems collaboratively. The module emphasises quality, maintainability, documentation, and real‑world workflows used in modern engineering teams, preparing learners for industry‑level development environments.', 20, NULL, 2, '2026-05-13 20:06:26'),
(5, 'Final Year Project', 'Students undertake an independent research and development project supervised by academic staff. The module encourages creativity, critical thinking, and technical depth. Learners define a problem, conduct research, design a solution, and produce a final deliverable demonstrating their skills and knowledge. It represents a major capstone experience and showcases the student’s academic and practical abilities.', 20, NULL, 3, '2026-05-13 20:06:26'),
(6, 'Principles of Management', 'This module introduces key management concepts including organisational behaviour, leadership styles, motivation, and decision‑making. Students explore how managers coordinate people and resources to achieve organisational goals. The module builds understanding of workplace dynamics, communication, and effective leadership practices essential for business environments.', 20, NULL, 1, '2026-05-13 20:06:26'),
(7, 'Marketing Fundamentals', 'Students learn core marketing principles such as the marketing mix, segmentation, consumer behaviour, and digital marketing. The module explains how organisations create value, communicate with audiences, and build effective marketing strategies across different channels. It provides a strong foundation for further study in marketing and business.', 20, NULL, 1, '2026-05-13 20:06:26'),
(8, 'Financial Accounting', 'This module covers the fundamentals of financial accounting, including balance sheets, income statements, and ratio analysis. Students learn how organisations record, summarise, and interpret financial information to support decision‑making and communicate performance. The module builds essential skills for business, finance, and accounting roles.', 20, NULL, 2, '2026-05-13 20:06:26'),
(9, 'Machine Learning', 'Students explore supervised and unsupervised learning, neural networks, model evaluation, and practical machine learning using tools such as scikit‑learn and TensorFlow. The module focuses on building, training, and assessing predictive models for real‑world applications. Learners gain hands‑on experience with modern machine learning workflows.', 20, NULL, 1, '2026-05-13 20:06:26'),
(10, 'Big Data Technologies', 'This module introduces large‑scale data processing using Hadoop, Spark, and cloud‑based data pipelines. Students learn how distributed systems handle massive datasets and how to design workflows for analytics and processing at scale. The module prepares learners for working with modern big data technologies.', 20, NULL, 1, '2026-05-13 20:06:26'),
(11, 'Statistical Methods', 'Students learn probability, hypothesis testing, regression analysis, and Bayesian inference. The module focuses on applying statistical techniques to real datasets and interpreting results to support data‑driven decisions. It builds strong analytical and quantitative reasoning skills essential for data‑focused roles.', 20, NULL, 1, '2026-05-13 20:06:26'),
(12, 'Network Security Fundamentals', 'This module covers essential security concepts including TCP/IP security, firewalls, intrusion detection systems, VPNs, and basic cryptography. Students learn how networks are protected, how attacks occur, and how common threats are mitigated. It provides a strong foundation for further study in cybersecurity.', 20, NULL, 1, '2026-05-13 20:06:26'),
(13, 'Ethical Hacking & Penetration Testing', 'Students gain hands‑on experience with penetration testing tools and methodologies, including Metasploit and Burp Suite. The module covers vulnerability assessment, exploitation techniques, reporting, and responsible disclosure. It prepares learners for ethical hacking roles and strengthens practical cybersecurity skills.', 20, NULL, 2, '2026-05-13 20:06:26'),
(14, 'Web Development', 'This module teaches frontend and backend development using HTML, CSS, JavaScript, PHP, APIs, and responsive design. Students learn how to build functional, user‑friendly websites and web applications. The module emphasises practical development skills and modern web standards.', 20, 'module_1779134128_d2640a2b.jpg', 1, '2026-05-13 20:18:53'),
(15, 'Object-Oriented Programming', 'Students learn object‑oriented principles including classes, inheritance, abstraction, and encapsulation. The module focuses on designing modular, reusable, and maintainable software systems. Learners develop strong programming habits and gain experience applying OOP concepts to real projects.', 20, NULL, 1, '2026-05-13 20:18:53'),
(16, 'Database Systems', 'This module covers relational database design, SQL querying, normalization, indexing, and transaction management. Students learn how to build efficient and reliable database‑driven applications. The module emphasises practical SQL skills and sound database architecture.', 20, NULL, 2, '2026-05-13 20:18:53'),
(17, 'Requirements Engineering', 'Students learn techniques for eliciting, analysing, documenting, and validating software requirements. The module emphasises communication with stakeholders, producing high‑quality requirement specifications, and ensuring that systems meet user needs. It builds strong analytical and documentation skills.', 20, NULL, 2, '2026-05-13 20:18:53'),
(18, 'DevOps and Deployment', 'This module introduces DevOps practices including continuous integration, delivery pipelines, containerisation, monitoring, and automated deployment. Students learn modern workflows used in cloud‑native development and how to streamline software delivery processes.', 20, NULL, 3, '2026-05-13 20:18:53'),
(19, 'Corporate Finance', 'Students explore capital structure, investment appraisal, financial planning, and organisational financial decision‑making. The module explains how financial strategies support business objectives and how organisations manage financial resources effectively.', 20, NULL, 2, '2026-05-13 20:18:53'),
(20, 'Taxation Principles', 'This module covers personal and corporate taxation, including compliance, tax calculations, and legal responsibilities. Students learn how tax systems operate and how organisations meet regulatory requirements. It builds practical understanding of tax processes.', 20, NULL, 2, '2026-05-13 20:18:53'),
(21, 'Auditing', 'Students learn internal and external auditing concepts, including assurance, audit evidence, controls, and ethical considerations. The module explains how auditors evaluate financial accuracy and organisational integrity, preparing learners for auditing roles.', 20, NULL, 3, '2026-05-13 20:18:53'),
(22, 'Digital Content Strategy', 'This module teaches how to plan, create, and manage digital content for websites, campaigns, email, and social platforms. Students learn how to align content with audience needs and organisational goals, improving digital communication effectiveness.', 20, NULL, 1, '2026-05-13 20:18:53'),
(23, 'Social Media Analytics', 'Students learn how to measure social media performance using engagement, reach, conversion, and reporting metrics. The module focuses on analysing and optimising digital campaigns to improve results and audience impact.', 20, NULL, 2, '2026-05-13 20:18:53'),
(24, 'Consumer Behaviour', 'This module explores how consumers think, decide, and respond to marketing messages across digital and traditional channels. Students learn psychological and behavioural influences on purchasing decisions and how marketers use these insights.', 20, NULL, 2, '2026-05-13 20:18:53'),
(25, 'Campaign Planning', 'Students learn how to design and execute integrated marketing campaigns, including budgeting, scheduling, and performance evaluation. The module emphasises strategic planning and multi‑channel coordination to achieve marketing objectives.', 20, NULL, 3, '2026-05-13 20:18:53'),
(26, 'Engineering Mathematics', 'This module covers applied mathematics for engineering, including calculus, matrices, differential equations, and numerical methods. Students develop problem‑solving skills essential for engineering analysis and design.', 20, NULL, 1, '2026-05-13 20:18:53'),
(27, 'Statics and Dynamics', 'Students learn principles of forces, motion, equilibrium, and mechanical system behaviour. The module focuses on analysing static and dynamic engineering problems and applying physics to real‑world systems.', 20, NULL, 1, '2026-05-13 20:18:53'),
(28, 'Thermodynamics', 'This module introduces thermodynamic principles including heat, energy, work, and system behaviour. Students apply these concepts to engineering scenarios and problem‑solving in mechanical and thermal systems.', 20, NULL, 2, '2026-05-13 20:18:53'),
(29, 'Materials and Manufacturing', 'Students explore engineering materials, their properties, manufacturing processes, and production systems. The module explains how material selection affects design, performance, and manufacturing efficiency.', 20, NULL, 2, '2026-05-13 20:18:53'),
(30, 'Machine Design', 'This module covers mechanical component design, stress analysis, fatigue, and safety considerations. Students learn how to design reliable and efficient mechanical systems using engineering principles.', 20, NULL, 3, '2026-05-13 20:18:53'),
(31, 'Deep Learning', 'Students explore neural networks, backpropagation, convolutional networks, transformers, and practical deep learning model development. The module focuses on building advanced AI systems and understanding modern architectures.', 20, NULL, 1, '2026-05-13 20:18:53'),
(32, 'Natural Language Processing', 'This module teaches text preprocessing, language models, classification, and information extraction. Students learn how to build NLP applications for real‑world tasks such as sentiment analysis and text classification.', 20, NULL, 1, '2026-05-13 20:18:53'),
(33, 'Computer Vision', 'Students learn image processing, feature extraction, object detection, segmentation, and visual recognition. The module explains how machines interpret visual data and how computer vision systems are built.', 20, NULL, 1, '2026-05-13 20:18:53'),
(34, 'AI Ethics and Governance', 'This module explores fairness, bias, transparency, accountability, privacy, and regulation in AI systems. Students learn how to design responsible and ethical AI solutions and understand governance frameworks.', 20, NULL, 1, '2026-05-13 20:18:53'),
(35, 'Project Planning and Control', 'Students learn project scheduling, milestones, work breakdown structures, and performance tracking. The module focuses on planning and controlling successful projects using structured project management techniques.', 20, NULL, 1, '2026-05-13 20:18:53'),
(36, 'Risk and Quality Management', 'This module covers identifying project risks, planning mitigation strategies, ensuring quality, and applying governance practices. Students learn how to maintain project reliability and deliver successful outcomes.', 20, NULL, 1, '2026-05-13 20:18:53'),
(37, 'Agile Project Delivery', 'Students learn Scrum, Kanban, sprint planning, and stakeholder communication. The module focuses on iterative, collaborative project delivery and modern agile practices used in industry.', 20, NULL, 1, '2026-05-13 20:18:53'),
(38, 'Procurement and Contracts', 'This module teaches procurement processes, supplier management, contract administration, and legal considerations. Students learn how organisations acquire goods and services effectively and manage supplier relationships.', 20, NULL, 1, '2026-05-13 20:18:53'),
(39, 'International Commercial Contracts', 'Students explore legal principles governing international business agreements and contract enforcement. The module explains how cross‑border contracts are structured, negotiated, and regulated.', 20, NULL, 1, '2026-05-13 20:18:53'),
(40, 'Trade Law and Regulation', 'This module covers regulatory frameworks affecting international trade, market access, and compliance. Students learn how laws shape global business operations and international market participation.', 20, NULL, 1, '2026-05-13 20:18:53'),
(41, 'Corporate Governance', 'Students learn governance principles including board structures, accountability, ethics, and compliance. The module explains how organisations maintain responsible oversight and effective governance.', 20, NULL, 1, '2026-05-13 20:18:53'),
(42, 'International Dispute Resolution', 'This module teaches arbitration, mediation, litigation strategy, and enforcement across jurisdictions. Students learn how international disputes are resolved and how legal processes differ globally.', 20, NULL, 1, '2026-05-13 20:18:53'),
(43, 'Distributed Systems', 'Students explore scalability, consistency, fault tolerance, messaging, and distributed application design. The module explains how large‑scale systems operate reliably across multiple nodes.', 20, NULL, 1, '2026-05-13 20:18:53'),
(44, 'Cloud Infrastructure', 'This module covers compute, storage, networking, cloud service models, and architecture design. Students learn how cloud platforms support modern applications and scalable infrastructure.', 20, NULL, 1, '2026-05-13 20:18:53'),
(45, 'Containers and Kubernetes', 'Students learn containerisation, orchestration, scaling, service discovery, and deployment using Kubernetes. The module focuses on cloud‑native application delivery and modern deployment workflows.', 20, NULL, 1, '2026-05-13 20:18:53'),
(46, 'Cloud Security', 'This module covers identity, access control, encryption, compliance, threat modelling, and cloud‑native security practices. Students learn how to secure cloud environments effectively and manage risks.', 20, NULL, 1, '2026-05-13 20:18:53');

-- --------------------------------------------------------

--
-- Table structure for table `programmes`
--

CREATE TABLE `programmes` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `level` enum('Undergraduate','Postgraduate') NOT NULL,
  `description` text NOT NULL,
  `image_url` varchar(500) DEFAULT NULL,
  `is_published` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `programmes`
--

INSERT INTO `programmes` (`id`, `title`, `level`, `description`, `image_url`, `is_published`, `created_at`) VALUES
(1, 'BSc Computer Science', 'Undergraduate', 'This programme develops strong technical and problem-solving skills through programming, algorithms, databases, software engineering, and systems design. Students learn how modern software is built, tested, and maintained, with practical projects that prepare them for careers in software development, IT, and technology-related roles.', '/uploads/programmes/1778703456_7f45b5a7abe1.jpg', 1, '2026-05-13 20:06:26'),
(2, 'BSc Business Management', 'Undergraduate', 'This programme introduces the main areas of business, including leadership, strategy, marketing, finance, and operations. Students gain an understanding of how organisations work and how managers make decisions in competitive environments, preparing them for careers in management, consultancy, entrepreneurship, and business support roles.', 'uploads/programmes/1778703878_35937bafa98d.jpg', 1, '2026-05-13 20:06:26'),
(3, 'MSc Data Science', 'Postgraduate', 'This programme focuses on collecting, analysing, and interpreting data to support decision-making in business, technology, and research. Students study machine learning, statistics, big data tools, and data visualisation, building the analytical and technical skills needed for careers in data science, analytics, and research-focused roles.', 'uploads/programmes/1778703902_2c6354afea68.jpg', 1, '2026-05-13 20:06:26'),
(4, 'MSc Cyber Security', 'Postgraduate', 'This programme develops practical and theoretical knowledge of protecting systems, networks, and data from digital threats. Students study ethical hacking, network security, digital forensics, and secure system design, preparing for roles in cyber defence, security analysis, and information protection.', 'uploads/programmes/1778703941_226b7b171a74.jpg', 1, '2026-05-13 20:06:26'),
(5, 'BSc Software Engineering', 'Undergraduate', 'This programme concentrates on the full software development lifecycle, from requirements and design through testing, deployment, and maintenance. Students learn modern development practices such as version control, agile methods, DevOps, and scalable system design, preparing them for professional software engineering careers.', 'uploads/programmes/1778703966_bdd4eb9632d6.jpg', 1, '2026-05-13 20:17:54'),
(6, 'BSc Accounting and Finance', 'Undergraduate', 'This programme builds a strong foundation in financial reporting, auditing, taxation, corporate finance, and business analysis. Students develop the knowledge and numerical skills needed to understand financial performance and support decision-making in accounting, banking, finance, and consultancy roles.', 'uploads/programmes/1778703990_73d5ba553293.jpg', 1, '2026-05-13 20:17:54'),
(7, 'BA Digital Marketing', 'Undergraduate', 'This programme explores how businesses promote products and services through digital channels such as social media, content marketing, search, and analytics. Students learn branding, campaign planning, consumer behaviour, and performance analysis, preparing them for careers in digital marketing, advertising, and communications.', 'uploads/programmes/1778704014_efe0d9743a18.jpg', 1, '2026-05-13 20:17:54'),
(8, 'BEng Mechanical Engineering', 'Undergraduate', 'This programme combines engineering theory with practical design and problem-solving in areas such as mechanics, thermodynamics, materials, manufacturing, and machine design. Students gain the technical knowledge and analytical skills needed for careers in engineering, product development, manufacturing, and technical design.', 'uploads/programmes/1778704039_12a274a4e63b.jpg', 0, '2026-05-13 20:17:54'),
(9, 'MSc Artificial Intelligence', 'Postgraduate', 'This programme examines how intelligent systems can learn, reason, and make decisions using data. Students study machine learning, neural networks, computer vision, natural language processing, and AI ethics, preparing them for advanced roles in AI development, research, and applied intelligent systems.', 'uploads/programmes/1778704055_9f3733f4aa7b.jpg', 1, '2026-05-13 20:17:54'),
(10, 'MSc Project Management', 'Postgraduate', 'This programme develops the skills needed to plan, deliver, and control projects in a wide range of industries. Students study project planning, risk management, budgeting, quality, leadership, and agile delivery, preparing them for roles in project coordination, management, and business operations.', 'uploads/programmes/1778704087_ef876b79ea04.jpg', 1, '2026-05-13 20:17:54'),
(11, 'LLM International Business Law', 'Postgraduate', 'This programme explores the legal frameworks that shape global business, including trade law, commercial contracts, dispute resolution, and corporate governance. Students develop advanced legal understanding and analytical skills for careers in international law, legal consultancy, compliance, and business regulation.', 'uploads/programmes/1778704103_657773d3dde4.jpg', 1, '2026-05-13 20:17:54'),
(12, 'MSc Cloud Computing', 'Postgraduate', 'This programme focuses on modern cloud technologies used to build and run scalable digital services. Students study distributed systems, cloud infrastructure, virtualization, containers, cloud security, and deployment practices, preparing them for careers in cloud engineering, infrastructure, and systems administration.', 'uploads/programmes/1778704119_d1cb480ba40c.jpg', 1, '2026-05-13 20:17:54');

-- --------------------------------------------------------

--
-- Table structure for table `programme_modules`
--

CREATE TABLE `programme_modules` (
  `programme_id` int(10) UNSIGNED NOT NULL,
  `module_id` int(10) UNSIGNED NOT NULL,
  `year_of_study` tinyint(4) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `programme_modules`
--

INSERT INTO `programme_modules` (`programme_id`, `module_id`, `year_of_study`) VALUES
(1, 1, 2),
(1, 2, 2),
(1, 3, 1),
(1, 4, 1),
(1, 5, 3),
(2, 6, 1),
(2, 7, 1),
(2, 8, 1),
(2, 11, 3),
(2, 24, 2),
(2, 39, 2),
(2, 40, 3),
(3, 9, 1),
(3, 10, 1),
(3, 11, 1),
(4, 12, 1),
(4, 13, 1),
(5, 3, 2),
(5, 5, 3),
(5, 14, 1),
(5, 15, 2),
(5, 16, 1),
(5, 18, 3),
(6, 19, 1),
(6, 20, 1),
(6, 21, 1),
(6, 41, 3),
(7, 7, 2),
(7, 22, 2),
(7, 23, 3),
(7, 24, 1),
(7, 25, 1),
(8, 26, 1),
(8, 27, 1),
(8, 28, 1),
(8, 29, 1),
(8, 30, 1),
(9, 9, 1),
(9, 31, 1),
(9, 32, 1),
(9, 33, 1),
(9, 34, 1),
(10, 35, 1),
(10, 36, 1),
(10, 37, 1),
(10, 38, 1),
(11, 39, 1),
(11, 40, 1),
(11, 41, 1),
(11, 42, 1),
(12, 16, 1),
(12, 43, 1),
(12, 44, 1),
(12, 45, 1),
(12, 46, 1);

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `id` int(10) UNSIGNED NOT NULL,
  `username` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `role` enum('instructor','coordinator','admin') NOT NULL DEFAULT 'instructor',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_by` int(10) UNSIGNED NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `photo` varchar(255) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `login_attempts` tinyint(4) NOT NULL DEFAULT 0,
  `locked_until` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`id`, `username`, `password_hash`, `email`, `full_name`, `role`, `is_active`, `created_by`, `created_at`, `photo`, `bio`, `login_attempts`, `locked_until`) VALUES
(1, 'Niranjan', '$2y$10$rac3ucVuVNVz9WRP9opkqeqE7F3dk0g46ZdzEqXiiXNTFtaXU2IOG', 'niranjan123@gmail.com', 'Niranjan GC', 'instructor', 1, 2, '2026-05-14 23:23:54', 'staff_1_95fbeb46.png', 'i am a fuckcing genisu', 4, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `staff_modules`
--

CREATE TABLE `staff_modules` (
  `staff_id` int(10) UNSIGNED NOT NULL,
  `module_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `staff_modules`
--

INSERT INTO `staff_modules` (`staff_id`, `module_id`) VALUES
(1, 1),
(1, 5);

-- --------------------------------------------------------

--
-- Table structure for table `staff_password_resets`
--

CREATE TABLE `staff_password_resets` (
  `id` int(10) UNSIGNED NOT NULL,
  `staff_id` int(10) UNSIGNED NOT NULL,
  `token_hash` varchar(64) NOT NULL,
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `expires_at` datetime NOT NULL,
  `used_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `staff_programmes`
--

CREATE TABLE `staff_programmes` (
  `staff_id` int(10) UNSIGNED NOT NULL,
  `programme_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `staff_programmes`
--

INSERT INTO `staff_programmes` (`staff_id`, `programme_id`) VALUES
(1, 3);

-- --------------------------------------------------------

--
-- Table structure for table `super_admins`
--

CREATE TABLE `super_admins` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(191) NOT NULL,
  `password_hash` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `last_login` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `super_admins`
--

INSERT INTO `super_admins` (`id`, `name`, `username`, `email`, `password_hash`, `is_active`, `created_at`, `updated_at`, `last_login`) VALUES
(1, 'Lead Admin', 'Super', 'you@example.com', '$2y$10$0qT0kqcg3LSlSBzhO.sIkejnD2S2wM0teSsYSBDMLBtvcM0tCgp0q', 1, '2026-05-21 00:25:13', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `admin_password_resets`
--
ALTER TABLE `admin_password_resets`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `u_token_hash` (`token_hash`),
  ADD KEY `idx_admin_id` (`admin_id`);

--
-- Indexes for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `interest_registrations`
--
ALTER TABLE `interest_registrations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `withdraw_token` (`withdraw_token`),
  ADD KEY `fk_ir_programme` (`programme_id`);

--
-- Indexes for table `modules`
--
ALTER TABLE `modules`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `programmes`
--
ALTER TABLE `programmes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `programme_modules`
--
ALTER TABLE `programme_modules`
  ADD PRIMARY KEY (`programme_id`,`module_id`),
  ADD KEY `fk_pm_module` (`module_id`);

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `fk_staff_creator` (`created_by`);

--
-- Indexes for table `staff_modules`
--
ALTER TABLE `staff_modules`
  ADD PRIMARY KEY (`staff_id`,`module_id`),
  ADD KEY `fk_sm_module` (`module_id`);

--
-- Indexes for table `staff_password_resets`
--
ALTER TABLE `staff_password_resets`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_staff_password_resets_token_hash` (`token_hash`),
  ADD KEY `idx_staff_password_resets_staff_id` (`staff_id`);

--
-- Indexes for table `staff_programmes`
--
ALTER TABLE `staff_programmes`
  ADD PRIMARY KEY (`staff_id`,`programme_id`),
  ADD KEY `fk_sp_programme` (`programme_id`);

--
-- Indexes for table `super_admins`
--
ALTER TABLE `super_admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `admin_password_resets`
--
ALTER TABLE `admin_password_resets`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `audit_logs`
--
ALTER TABLE `audit_logs`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `interest_registrations`
--
ALTER TABLE `interest_registrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `modules`
--
ALTER TABLE `modules`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT for table `programmes`
--
ALTER TABLE `programmes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `staff`
--
ALTER TABLE `staff`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `staff_password_resets`
--
ALTER TABLE `staff_password_resets`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `super_admins`
--
ALTER TABLE `super_admins`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admin_password_resets`
--
ALTER TABLE `admin_password_resets`
  ADD CONSTRAINT `fk_apr_admin` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `interest_registrations`
--
ALTER TABLE `interest_registrations`
  ADD CONSTRAINT `fk_ir_programme` FOREIGN KEY (`programme_id`) REFERENCES `programmes` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `programme_modules`
--
ALTER TABLE `programme_modules`
  ADD CONSTRAINT `fk_pm_module` FOREIGN KEY (`module_id`) REFERENCES `modules` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_pm_programme` FOREIGN KEY (`programme_id`) REFERENCES `programmes` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `staff`
--
ALTER TABLE `staff`
  ADD CONSTRAINT `fk_staff_creator` FOREIGN KEY (`created_by`) REFERENCES `admins` (`id`);

--
-- Constraints for table `staff_modules`
--
ALTER TABLE `staff_modules`
  ADD CONSTRAINT `fk_sm_module` FOREIGN KEY (`module_id`) REFERENCES `modules` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_sm_staff` FOREIGN KEY (`staff_id`) REFERENCES `staff` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `staff_password_resets`
--
ALTER TABLE `staff_password_resets`
  ADD CONSTRAINT `fk_staff_password_resets_staff` FOREIGN KEY (`staff_id`) REFERENCES `staff` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `staff_programmes`
--
ALTER TABLE `staff_programmes`
  ADD CONSTRAINT `fk_sp_programme` FOREIGN KEY (`programme_id`) REFERENCES `programmes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_sp_staff` FOREIGN KEY (`staff_id`) REFERENCES `staff` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

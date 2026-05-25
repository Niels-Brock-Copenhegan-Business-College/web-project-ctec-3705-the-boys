-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 26, 2026 at 12:48 AM
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
  `secret_code_hash` varchar(255) DEFAULT NULL,
  `secret_code_set_at` datetime DEFAULT NULL,
  `login_attempts` tinyint(4) DEFAULT 0,
  `locked_until` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password_hash`, `secret_code_hash`, `secret_code_set_at`, `login_attempts`, `locked_until`) VALUES
(1, 'admin', '$2y$10$Y5B6dFmHMxqe9JnZBpAZP.nWeTQ1eVv.LKO2KHjQS9a.HV74q9LAm', NULL, NULL, 0, NULL),
(2, 'Chitraranjan', '$2y$10$ictXo3/apKjwJpT7SiFwD.E5FR2fAtsZicZNCGvWsY/K.xqyANaj2', NULL, NULL, 0, NULL),
(3, 'Arvind', '$2y$10$4yN/42VbfGJzDX1fHsrT8ub8YVAeUGUpXv6Z8TvaFfy29lIJYmoIG', '$2y$10$YzJPMqfOoJw3JywkwpTc5OTuUu5mLwEEDJHxwSEtqLHFQsxlQ8xEW', '2026-05-21 00:32:06', 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `admin_action_logs`
--

CREATE TABLE `admin_action_logs` (
  `id` int(10) UNSIGNED NOT NULL,
  `superadmin_id` int(10) UNSIGNED NOT NULL,
  `admin_id` int(10) UNSIGNED DEFAULT NULL,
  `action` varchar(191) NOT NULL,
  `target_table` varchar(100) DEFAULT NULL,
  `target_id` int(11) DEFAULT NULL,
  `details` text DEFAULT NULL,
  `ip` varchar(45) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(1, 3, 'aebde7f8664d9ac9e5df155698c6fcb966b590ae8d14c4908597e8950028111b', 1, '2026-05-21 00:31:17', '2026-05-21 02:31:17', '2026-05-21 00:32:06');

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
(6, 'Chitraranjan', 'Yadav', 'chitra123@gmail.com', 1, 'd4b86886b0c7561c010613997f1a27b39dfc1e5cf2cabd01f2b89518d53f6599', '2026-05-13 21:23:42'),
(10, 'hi', 'lol', 'test123@gmail.com', 5, '6bbe8f23ddcfea7fa28febcdfb781fb637b9be82a92a80415e147dfc0bcb78c9', '2026-05-21 13:09:04'),
(25, 'NIRANJAN', 'GC', 'niranjangc975@gmail.com', 3, '28da37fd57c0dd7eb82875324c03de534475061cf14e6138635017cf3254bc36', '2026-05-26 00:19:56');

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
  `year_of_study` tinyint(3) UNSIGNED NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `modules`
--

INSERT INTO `modules` (`id`, `title`, `description`, `credits`, `photo`, `year_of_study`, `created_at`) VALUES
(1, 'Introduction to Programming', 'This module introduces students to the foundations of programming using Python. You will explore variables, data types, control flow, functions, and core data structures such as lists, dictionaries, and sets. By writing and debugging increasingly complex programs, you develop the computational thinking skills that underpin all further study in computer science. Practical lab sessions reinforce every concept, and by the end of the module you will be able to design, implement, and test small software systems independently.', 20, NULL, 1, '2026-05-13 20:06:26'),
(2, 'Mathematics for Computing', 'This module provides the rigorous mathematical foundation required across all areas of computer science. Topics include propositional and predicate logic, set theory, relations and functions, combinatorics, graph theory, and an introduction to calculus and linear algebra. You will learn to construct formal proofs, reason abstractly about computational problems, and apply mathematical techniques to algorithm analysis. Weekly problem sets build the analytical confidence essential for advanced modules in algorithms, machine learning, and systems design.', 20, NULL, 1, '2026-05-13 20:06:26'),
(3, 'Data Structures & Algorithms', 'Building on your programming foundations, this module takes a deep dive into the data structures and algorithms that power modern software. You will study arrays, linked lists, stacks, queues, hash tables, trees, heaps, and graphs, learning how each is implemented and when to use it. Algorithm design techniques — including divide and conquer, dynamic programming, and greedy strategies — are covered alongside complexity analysis using Big-O notation. By the end, you will be able to select appropriate data structures, implement efficient algorithms, and evaluate their performance rigorously.', 20, NULL, 2, '2026-05-13 20:06:26'),
(4, 'Software Engineering', 'This module introduces the professional practices, processes, and tools used by software engineers in industry. You will study the full software development lifecycle, from requirements gathering and system design through implementation, testing, and maintenance. Topics include agile and Scrum methodologies, version control with Git, design patterns, code review, and continuous integration. Team-based coursework simulates real workplace collaboration, developing your ability to produce high-quality, maintainable software in a professional setting.', 20, NULL, 2, '2026-05-13 20:06:26'),
(5, 'Final Year Project', 'The Final Year Project is the centrepiece of your degree — an extended, independent piece of work that demonstrates the full range of skills and knowledge you have developed. Working under the supervision of an academic staff member, you will identify a significant problem, conduct a thorough literature review, design and implement a solution, and evaluate your results critically. The project may take the form of a software system, a research investigation, or an applied study. It is an opportunity to pursue a topic you are genuinely passionate about and to produce work you can be proud to show to employers.', 40, 'module_1779457818_81bfe4ac.jpg', 3, '2026-05-13 20:06:26'),
(6, 'Principles of Management', 'This module introduces the core principles and practices of management in modern organisations. You will explore classical and contemporary management theories, leadership styles, motivation, team dynamics, and organisational culture. Case studies from diverse industries illustrate how managers navigate complexity, drive performance, and respond to change. By the end of the module, you will understand what effective management looks like in practice and have developed the interpersonal and analytical skills that underpin strong leadership.', 20, NULL, 1, '2026-05-13 20:06:26'),
(7, 'Marketing Fundamentals', 'This module provides a comprehensive introduction to marketing theory and practice. You will study the marketing mix, market segmentation, targeting and positioning, consumer behaviour, branding, and the growing role of digital channels. Drawing on real-world campaigns and brand case studies, you will learn how organisations create value, build relationships with customers, and compete effectively in dynamic markets. The module lays the groundwork for advanced study in digital marketing, consumer behaviour, and campaign strategy.', 20, NULL, 1, '2026-05-13 20:06:26'),
(8, 'Financial Accounting', 'This module develops your understanding of how organisations record, summarise, and report their financial activities. You will study double-entry bookkeeping, the preparation of income statements and balance sheets, depreciation, inventory valuation, and ratio analysis. By working through real financial statements, you learn to assess organisational performance, identify strengths and weaknesses, and communicate financial results to stakeholders. The skills you develop are essential for any career in business, finance, or management.', 20, NULL, 2, '2026-05-13 20:06:26'),
(9, 'Machine Learning', 'This module provides a comprehensive foundation in machine learning theory and practice. You will study the full spectrum of supervised learning — from linear and logistic regression to decision trees, random forests, and support vector machines — as well as unsupervised techniques including k-means clustering and principal component analysis. A significant portion of the module is dedicated to deep learning, covering neural network architectures, backpropagation, and convolutional networks using TensorFlow and Keras. By the end, you will be able to design, train, evaluate, and optimise machine learning models for real-world datasets, applying best practices in model selection and performance benchmarking.', 20, 'module_1779709015_3e018a3d.jpg', 1, '2026-05-13 20:06:26'),
(10, 'Big Data Technologies', 'As data volumes grow exponentially, the ability to process and analyse data at scale has become a critical skill. This module introduces the architecture and tools underpinning modern big data ecosystems, including the Hadoop distributed file system, MapReduce programming, and Apache Spark for in-memory processing. You will design and implement end-to-end data pipelines, work with streaming data using Kafka, and explore cloud-based solutions on AWS and Google Cloud. Case studies drawn from e-commerce, social media, and financial services illustrate how organisations leverage big data infrastructure to gain competitive advantage. Practical lab sessions ensure you graduate with hands-on experience of the tools used daily by data engineers in industry.', 10, 'module_1779742071_a813176a.png', 1, '2026-05-13 20:06:26'),
(11, 'Statistical Methods', 'A rigorous grounding in statistics is essential for any data scientist. This module covers the core methods used to analyse data, test hypotheses, and build evidence-based models. Topics include probability theory, sampling distributions, confidence intervals, A/B testing, multiple regression, logistic regression, and Bayesian inference. You will use R and Python to apply these methods to real datasets, interpreting outputs critically and communicating results clearly. The module places particular emphasis on understanding the assumptions behind statistical tests and knowing when to apply them — skills that distinguish a rigorous data scientist from one who simply runs code.', 20, NULL, 1, '2026-05-13 20:06:26'),
(12, 'Network Security Fundamentals', 'This module provides a thorough grounding in the principles and technologies used to secure modern networks. You will study the TCP/IP protocol stack, common attack vectors, firewalls, intrusion detection and prevention systems, VPNs, and cryptographic fundamentals. Lab sessions give you hands-on experience with real network analysis tools such as Wireshark and Nmap. By the end of the module, you will be able to assess network vulnerabilities, design layered security controls, and articulate how defensive technologies work together to protect organisational infrastructure.', 20, NULL, 1, '2026-05-13 20:06:26'),
(13, 'Ethical Hacking & Penetration Testing', 'This module provides intensive, hands-on training in ethical hacking and penetration testing. You will work through the full penetration testing lifecycle — reconnaissance, scanning, exploitation, post-exploitation, and reporting — using industry tools including Metasploit, Burp Suite, Nmap, and Wireshark. Legal and ethical frameworks governing penetration testing are covered in depth, ensuring you understand the responsibilities of a professional security tester. By the end, you will be able to conduct structured vulnerability assessments, document findings professionally, and recommend remediation strategies.', 20, NULL, 2, '2026-05-13 20:06:26'),
(14, 'Web Development', 'This module teaches the full stack of modern web development, from semantic HTML and responsive CSS through to server-side programming with PHP and RESTful API design. You will build real web applications that interact with databases, handle user authentication, and consume external APIs. Frontend topics include JavaScript, DOM manipulation, and accessible UI design. The module emphasises professional development practices — version control, code review, and deployment — and by the end you will have a portfolio-ready web application demonstrating your skills.', 20, 'module_1779134128_d2640a2b.jpg', 1, '2026-05-13 20:18:53'),
(15, 'Object-Oriented Programming', 'This module develops your understanding of object-oriented design and programming. You will study core OOP principles — encapsulation, inheritance, polymorphism, and abstraction — and learn how to apply them to build modular, maintainable software. Design patterns such as Singleton, Factory, Observer, and Strategy are introduced alongside UML modelling. Through a series of progressively complex projects, you develop the ability to design clean class hierarchies, write reusable components, and reason about software architecture at a system level.', 20, NULL, 1, '2026-05-13 20:18:53'),
(16, 'Database Systems', 'This module covers the design, implementation, and management of relational database systems. You will study entity-relationship modelling, schema design, normalisation, SQL querying, indexing, transactions, and concurrency control. Practical sessions give you hands-on experience with MySQL, building and querying databases that support real applications. The module also introduces NoSQL approaches and discusses when relational and non-relational models are most appropriate. By the end, you will be able to design efficient, reliable database schemas and write complex queries with confidence.', 20, NULL, 2, '2026-05-13 20:18:53'),
(17, 'Requirements Engineering', 'This module focuses on the critical early stages of software development — understanding what a system must do before a line of code is written. You will study elicitation techniques including interviews, workshops, and observation; methods for documenting requirements as use cases, user stories, and formal specifications; and validation approaches to ensure requirements are complete, consistent, and testable. The module develops your ability to communicate effectively with both technical and non-technical stakeholders, a skill that is consistently cited by employers as one of the most valuable a software engineer can have.', 20, NULL, 2, '2026-05-13 20:18:53'),
(18, 'DevOps and Deployment', 'This module introduces the DevOps philosophy and the tools and practices that bring development and operations teams together to deliver software faster and more reliably. Topics include continuous integration and delivery pipelines, infrastructure as code using Terraform, containerisation with Docker, orchestration with Kubernetes, monitoring, and incident response. You will set up end-to-end deployment pipelines for real applications, gaining hands-on experience with the workflows used by engineering teams at leading technology companies.', 20, NULL, 3, '2026-05-13 20:18:53'),
(19, 'Corporate Finance', 'This module develops your understanding of how organisations make major financial decisions. Topics include the time value of money, capital budgeting and investment appraisal using NPV, IRR and payback period, capital structure theory, dividend policy, and working capital management. You will analyse real corporate financial decisions, evaluate the trade-offs between debt and equity financing, and understand how financial markets price risk and return. The module builds strong quantitative skills and prepares you for advanced study in investment and financial management.', 20, NULL, 2, '2026-05-13 20:18:53'),
(20, 'Taxation Principles', 'This module provides a practical and theoretical grounding in personal and corporate taxation. You will study the structure of the UK tax system, income tax computation, corporation tax, capital gains tax, value added tax, and national insurance contributions. The module covers tax planning principles, compliance obligations, and the ethical responsibilities of tax practitioners. Working through realistic case studies, you develop the ability to compute tax liabilities accurately, advise on basic tax planning strategies, and navigate HMRC guidance confidently.', 20, NULL, 2, '2026-05-13 20:18:53'),
(21, 'Auditing', 'This module introduces the theory and practice of auditing from both internal and external perspectives. You will study the audit process from planning and risk assessment through evidence gathering, testing, and reporting. Topics include audit standards, professional ethics, internal control evaluation, audit sampling, and the auditor\'s report. The module also addresses corporate governance and the role of audit committees. Through case studies drawn from real audit failures and successes, you develop critical judgement about what constitutes reliable evidence and sound professional practice.', 20, NULL, 3, '2026-05-13 20:18:53'),
(22, 'Digital Content Strategy', 'This module develops your ability to plan, produce, and manage content that engages audiences and achieves organisational objectives. You will study content marketing theory, audience personas, editorial calendars, SEO fundamentals, email marketing, and social media content planning. Working with real brand briefs, you will create content strategies, write and edit copy for multiple channels, and measure content effectiveness using analytics tools. By the end, you will be able to develop and execute a coherent content strategy that drives audience growth and business results.', 20, NULL, 1, '2026-05-13 20:18:53'),
(23, 'Social Media Analytics', 'This module teaches you to measure, interpret, and act on social media data. You will study key performance metrics across platforms including Instagram, X, LinkedIn, TikTok, and YouTube — covering reach, engagement, conversion, and audience growth. Using tools such as Google Analytics, Meta Business Suite, and Sprout Social, you will analyse real campaign data, identify trends, and produce actionable recommendations. The module develops your ability to turn raw social data into clear strategic insights that drive marketing decisions.', 20, NULL, 2, '2026-05-13 20:18:53'),
(24, 'Consumer Behaviour', 'This module explores the psychological and social factors that shape how consumers discover, evaluate, and purchase products and services. You will study classical and contemporary models of consumer decision-making, the influence of perception, motivation, attitude, culture, and social groups on buying behaviour, and how digital environments have transformed the path to purchase. Practical applications focus on how marketers use behavioural insights to design more effective campaigns, improve user experience, and build lasting brand loyalty.', 20, NULL, 2, '2026-05-13 20:18:53'),
(25, 'Campaign Planning', 'This module develops your ability to plan and execute integrated marketing campaigns from brief to evaluation. You will work through the full campaign planning process — situation analysis, objective setting, audience targeting, channel selection, budgeting, creative briefing, scheduling, and post-campaign measurement. Drawing on real-world campaigns across digital and traditional channels, you will learn how agencies and in-house teams coordinate complex multi-touchpoint activity to achieve measurable business outcomes. The module culminates in a full campaign proposal for a real or simulated client brief.', 20, NULL, 3, '2026-05-13 20:18:53'),
(26, 'Engineering Mathematics', 'This module provides the mathematical toolkit essential for engineering analysis and design. Topics include differential and integral calculus, ordinary differential equations, vectors, matrices, Laplace transforms, and numerical methods. You will learn to apply these techniques to engineering problems — from modelling dynamic systems to solving structural equations — using both analytical approaches and computational tools such as MATLAB. The module develops the mathematical fluency that underpins all subsequent engineering modules and professional practice.', 20, NULL, 1, '2026-05-13 20:18:53'),
(27, 'Statics and Dynamics', 'This module introduces the fundamental principles of classical mechanics as applied to engineering systems. The statics section covers equilibrium of particles and rigid bodies, free body diagrams, centroids, moments of inertia, and structural analysis. The dynamics section addresses kinematics and kinetics of particles and rigid bodies, work and energy methods, impulse-momentum principles, and vibration fundamentals. Through a combination of analytical problem-solving and physical demonstrations, you develop the ability to model and analyse mechanical systems with confidence.', 20, NULL, 1, '2026-05-13 20:18:53'),
(28, 'Thermodynamics', 'This module introduces the fundamental laws of thermodynamics and their application to engineering systems. You will study the zeroth, first, and second laws, thermodynamic properties of pure substances, power cycles including Rankine and Brayton, refrigeration cycles, and heat transfer by conduction, convection, and radiation. Case studies from power generation, HVAC, and automotive engineering illustrate how thermodynamic principles are applied in practice. Laboratory sessions reinforce theoretical understanding through experiments with real thermodynamic systems.', 20, NULL, 2, '2026-05-13 20:18:53'),
(29, 'Materials and Manufacturing', 'This module develops your understanding of engineering materials and the manufacturing processes used to shape them. You will study the structure and properties of metals, polymers, ceramics, and composites — and how material selection affects performance, cost, and sustainability. Manufacturing topics include casting, forming, machining, joining, and additive manufacturing. The module combines materials science theory with practical workshops, giving you the knowledge to specify appropriate materials and processes for real engineering design challenges.', 20, NULL, 2, '2026-05-13 20:18:53'),
(30, 'Machine Design', 'This module develops your ability to design mechanical components and assemblies that are safe, reliable, and efficient. Topics include stress and strain analysis, fatigue and fracture mechanics, shaft design, bearing selection, gear and belt drive design, and bolted joint analysis. You will use industry-standard design codes and apply safety factors to ensure components meet performance requirements under real loading conditions. CAD tools are used throughout to model designs, and case studies from automotive, aerospace, and industrial machinery contexts illustrate professional design practice.', 20, NULL, 3, '2026-05-13 20:18:53'),
(31, 'Deep Learning', 'This module provides a rigorous treatment of deep learning, from the mathematical foundations of neural networks through to state-of-the-art architectures. You will study feedforward networks, convolutional neural networks, recurrent networks, attention mechanisms, and the transformer architecture that underlies modern large language models. Training techniques including optimisation algorithms, regularisation, batch normalisation, and transfer learning are covered in depth. Using PyTorch and TensorFlow, you will implement and train models on real datasets, developing both theoretical understanding and practical engineering skill.', 20, NULL, 1, '2026-05-13 20:18:53'),
(32, 'Natural Language Processing', 'This module provides a thorough grounding in natural language processing, from classical methods through to modern neural approaches. Topics include text preprocessing, tokenisation, word embeddings, language models, sequence-to-sequence architectures, named entity recognition, sentiment analysis, question answering, and text summarisation. You will implement NLP pipelines using Python, spaCy, and the Hugging Face Transformers library, working with real corpora across multiple domains. By the end, you will be able to design, build, and evaluate NLP systems for a broad range of real-world applications.', 20, NULL, 1, '2026-05-13 20:18:53'),
(33, 'Computer Vision', 'This module introduces the theory and practice of computer vision, covering the full pipeline from image acquisition through to high-level scene understanding. You will study image representation, filtering, edge detection, feature extraction, object detection, image segmentation, and visual recognition. Classical approaches using OpenCV are studied alongside modern deep learning methods including convolutional networks and vision transformers. Applications in medical imaging, autonomous vehicles, surveillance, and robotics illustrate the breadth of the field. Lab sessions give you hands-on experience building and evaluating vision systems on real image datasets.', 20, NULL, 1, '2026-05-13 20:18:53'),
(34, 'AI Ethics and Governance', 'This module examines the ethical, social, and governance challenges raised by artificial intelligence systems. Topics include algorithmic fairness and bias, explainability and transparency, privacy and data rights, accountability in automated decision-making, the environmental impact of large AI models, and international regulatory frameworks including the EU AI Act. You will analyse real cases where AI systems have caused harm, debate contested policy questions, and develop frameworks for responsible AI design. The module prepares you to be a reflective practitioner who can navigate the complex ethical landscape of modern AI development.', 20, NULL, 1, '2026-05-13 20:18:53'),
(35, 'Project Planning and Control', 'This module develops your ability to plan and control projects systematically from initiation to closure. You will study project lifecycle frameworks, scope definition, work breakdown structures, network diagrams, critical path analysis, Gantt charts, resource planning, and earned value management. Using industry-standard tools including Microsoft Project and Primavera, you will develop detailed project plans for realistic scenarios. The module builds the structured planning skills that project managers rely on to deliver complex initiatives on time and within budget.', 20, NULL, 1, '2026-05-13 20:18:53'),
(36, 'Risk and Quality Management', 'This module develops your ability to identify, assess, and manage risk throughout the project lifecycle, while maintaining a relentless focus on quality. Risk management topics include risk identification workshops, probability and impact assessment, risk registers, mitigation planning, and contingency management. Quality management covers quality planning, assurance and control processes, ISO 9001, Six Sigma fundamentals, and lessons learned. Working through real project scenarios, you develop the judgement to balance risk appetite with the need to protect project outcomes.', 20, NULL, 1, '2026-05-13 20:18:53'),
(37, 'Agile Project Delivery', 'This module provides comprehensive training in agile project delivery methods. You will study the Agile Manifesto and principles, Scrum roles and ceremonies, Kanban boards and flow management, sprint planning and retrospectives, user story writing, and stakeholder engagement in agile environments. The module also addresses scaling agile using frameworks such as SAFe and LeSS. Through simulated sprints and real team exercises, you develop the facilitation and collaboration skills needed to lead agile teams effectively in complex organisational settings.', 20, NULL, 1, '2026-05-13 20:18:53'),
(38, 'Procurement and Contracts', 'This module develops your understanding of procurement strategy and contract management in project environments. You will study procurement planning, supplier selection and evaluation, tendering processes, contract types and structures, negotiation skills, supplier relationship management, and contract administration. Legal topics include contract formation, variation, claims, and dispute resolution. Drawing on case studies from construction, IT, and government projects, you develop the commercial awareness and contractual knowledge essential for project managers operating in complex supply chains.', 20, NULL, 1, '2026-05-13 20:18:53'),
(39, 'International Commercial Contracts', 'This module examines the legal principles governing commercial contracts in the international context. You will study contract formation, offer and acceptance, consideration, terms and conditions, exclusion clauses, breach and remedies, and force majeure. The UN Convention on Contracts for the International Sale of Goods (CISG) is analysed in depth alongside comparative perspectives from common law and civil law jurisdictions. Through negotiation exercises and contract drafting workshops, you develop practical skills in structuring, reviewing, and advising on international commercial agreements.', 20, NULL, 1, '2026-05-13 20:18:53'),
(40, 'Trade Law and Regulation', 'This module provides a rigorous analysis of the legal frameworks governing international trade. Topics include the WTO dispute settlement system, trade in goods and services, customs law, anti-dumping and countervailing duties, trade remedies, preferential trade agreements, and export controls. Regional trade frameworks including EU trade law and post-Brexit UK trade arrangements are examined. You will analyse landmark trade disputes and develop the ability to advise clients on market access, compliance obligations, and strategic responses to trade regulation.', 20, NULL, 1, '2026-05-13 20:18:53'),
(41, 'Corporate Governance', 'This module examines the principles and practices of corporate governance in the international context. Topics include board structures and duties, shareholder rights, executive remuneration, audit and risk committees, comply-or-explain regimes, ESG reporting, and the role of institutional investors. Comparative governance frameworks from the UK, US, EU, and major emerging markets are analysed. Through case studies of high-profile governance failures and successes, you develop critical judgement about what constitutes effective, ethical, and accountable corporate leadership.', 20, NULL, 1, '2026-05-13 20:18:53'),
(42, 'International Dispute Resolution', 'This module provides a comprehensive treatment of international dispute resolution mechanisms. You will study international commercial arbitration in depth — including arbitral institutions such as the ICC, LCIA, and SIAC, arbitration agreements, tribunal constitution, procedure, and the enforcement of awards under the New York Convention. Mediation and other alternative dispute resolution methods are covered alongside litigation strategy in cross-border disputes. Moot exercises and case simulations develop your practical advocacy and dispute management skills.', 20, NULL, 1, '2026-05-13 20:18:53'),
(43, 'Distributed Systems', 'This module provides a rigorous grounding in the design and operation of distributed systems. You will study the fundamental challenges of distribution — consistency, availability, partition tolerance, and the CAP theorem — alongside practical solutions including consensus algorithms, distributed transactions, eventual consistency, and message-passing architectures. Technologies such as Apache Kafka, Apache ZooKeeper, and gRPC are studied in the context of real system designs. Case studies from large-scale systems at companies such as Google, Amazon, and Netflix illustrate how distributed systems principles are applied to build systems that serve billions of users.', 20, NULL, 1, '2026-05-13 20:18:53'),
(44, 'Cloud Infrastructure', 'This module provides comprehensive coverage of cloud infrastructure concepts and technologies. You will study compute services, object and block storage, virtual networking, load balancing, auto-scaling, serverless computing, and cloud-native database services across AWS, Azure, and Google Cloud Platform. Infrastructure as code using Terraform and AWS CloudFormation is covered alongside cloud cost management and well-architected framework principles. By the end, you will be able to design, deploy, and manage scalable, resilient cloud infrastructure for real-world applications.', 20, NULL, 1, '2026-05-13 20:18:53'),
(45, 'Containers and Kubernetes', 'This module provides in-depth training in containerisation and container orchestration. You will study Docker image creation, multi-stage builds, container registries, Docker Compose, and the full Kubernetes ecosystem — including pods, deployments, services, ingress, ConfigMaps, Secrets, persistent volumes, horizontal pod autoscaling, and rolling updates. Helm charts and GitOps workflows are introduced as tools for managing complex deployments. Lab sessions involve deploying and operating real multi-service applications on Kubernetes clusters, giving you the practical experience demanded by cloud-native engineering roles.', 20, NULL, 1, '2026-05-13 20:18:53'),
(46, 'Cloud Security', 'This module develops your expertise in securing cloud environments against the full range of modern threats. Topics include identity and access management, multi-factor authentication, role-based access control, encryption at rest and in transit, key management, network security groups, Web Application Firewalls, threat detection using cloud-native SIEM tools, compliance frameworks including ISO 27001 and SOC 2, and cloud-native security posture management. You will conduct practical security assessments of real cloud environments, identifying vulnerabilities and implementing remediation controls in line with industry best practices.', 20, NULL, 1, '2026-05-13 20:18:53');

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
(3, 'MSc Data Science', 'Postgraduate', 'The MSc Data Science programme equips graduates with the technical expertise and analytical thinking required to thrive in one of the fastest-growing fields in the world. You will develop deep proficiency in machine learning, statistical modelling, and large-scale data engineering — combining rigorous theory with hands-on practice using industry-standard tools such as Python, TensorFlow, Spark, and Hadoop.\r\n\r\nThroughout the programme, you will work with real-world datasets, tackling complex problems across sectors including finance, healthcare, technology, and government. You will learn to extract meaningful insights from structured and unstructured data, build predictive models, design scalable data pipelines, and communicate findings clearly to both technical and non-technical audiences.\r\n\r\nGraduates of this programme go on to careers as Data Scientists, Machine Learning Engineers, Data Analysts, and Research Scientists at leading organisations worldwide. The programme is ideal for graduates from computing, mathematics, engineering, or related disciplines who wish to specialise in data-driven decision making at a professional level.', 'uploads/programmes/1778703902_2c6354afea68.jpg', 1, '2026-05-13 20:06:26'),
(4, 'MSc Cyber Security', 'Postgraduate', 'This programme develops practical and theoretical knowledge of protecting systems, networks, and data from digital threats. Students study ethical hacking, network security, digital forensics, and secure system design, preparing for roles in cyber defence, security analysis, and information protection.', 'uploads/programmes/1778703941_226b7b171a74.jpg', 1, '2026-05-13 20:06:26'),
(5, 'BSc Software Engineering', 'Undergraduate', 'This programme concentrates on the full software development lifecycle, from requirements and design through testing, deployment, and maintenance. Students learn modern development practices such as version control, agile methods, DevOps, and scalable system design, preparing them for professional software engineering careers.', 'uploads/programmes/1778703966_bdd4eb9632d6.jpg', 1, '2026-05-13 20:17:54'),
(6, 'BSc Accounting and Finance', 'Undergraduate', 'This programme builds a strong foundation in financial reporting, auditing, taxation, corporate finance, and business analysis. Students develop the knowledge and numerical skills needed to understand financial performance and support decision-making in accounting, banking, finance, and consultancy roles.', 'uploads/programmes/1778703990_73d5ba553293.jpg', 1, '2026-05-13 20:17:54'),
(7, 'BA Digital Marketing', 'Undergraduate', 'This programme explores how businesses promote products and services through digital channels such as social media, content marketing, search, and analytics. Students learn branding, campaign planning, consumer behaviour, and performance analysis, preparing them for careers in digital marketing, advertising, and communications.', 'uploads/programmes/1778704014_efe0d9743a18.jpg', 0, '2026-05-13 20:17:54'),
(8, 'BEng Mechanical Engineering', 'Undergraduate', 'This programme combines engineering theory with practical design and problem-solving in areas such as mechanics, thermodynamics, materials, manufacturing, and machine design. Students gain the technical knowledge and analytical skills needed for careers in engineering, product development, manufacturing, and technical design.', 'uploads/programmes/1778704039_12a274a4e63b.jpg', 1, '2026-05-13 20:17:54'),
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
  `bio` text DEFAULT NULL,
  `role` enum('instructor','coordinator','admin') NOT NULL DEFAULT 'instructor',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_by` int(10) UNSIGNED NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `photo` varchar(255) DEFAULT NULL,
  `login_attempts` tinyint(4) DEFAULT 0,
  `locked_until` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`id`, `username`, `password_hash`, `email`, `full_name`, `bio`, `role`, `is_active`, `created_by`, `created_at`, `photo`, `login_attempts`, `locked_until`) VALUES
(1, 'Niranjan', '$2y$10$rac3ucVuVNVz9WRP9opkqeqE7F3dk0g46ZdzEqXiiXNTFtaXU2IOG', 'niranjan123@gmail.com', 'Niranjan GC', 'Hi i am a very good guy', 'instructor', 1, 2, '2026-05-14 23:23:54', 'staff_1_a14d730d.jpg', 0, NULL),
(4, 'dr.chen', '$2y$10$ImGTcEL2CTqdHayDD82dFO3h1F.oxKYIzG36nvAZ.civu3l56v1fC', 'staff@gmail.com', 'dr.chen', 'hiiiiiiiii', 'instructor', 1, 2, '2026-05-25 13:44:39', 'staff_4_d18e9527.jpg', 0, NULL);

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
(1, 5),
(1, 9),
(4, 10);

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
(1, 3),
(4, 4);

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
-- Indexes for table `admin_action_logs`
--
ALTER TABLE `admin_action_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_superadmin` (`superadmin_id`);

--
-- Indexes for table `admin_password_resets`
--
ALTER TABLE `admin_password_resets`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `u_token_hash` (`token_hash`),
  ADD KEY `idx_admin_id` (`admin_id`);

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
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `admin_action_logs`
--
ALTER TABLE `admin_action_logs`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `admin_password_resets`
--
ALTER TABLE `admin_password_resets`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `interest_registrations`
--
ALTER TABLE `interest_registrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `modules`
--
ALTER TABLE `modules`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `programmes`
--
ALTER TABLE `programmes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

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
-- Constraints for table `admin_action_logs`
--
ALTER TABLE `admin_action_logs`
  ADD CONSTRAINT `fk_aal_superadmin` FOREIGN KEY (`superadmin_id`) REFERENCES `super_admins` (`id`) ON DELETE CASCADE;

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

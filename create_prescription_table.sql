-- Create prescription table to track prescribed medicines for each student check-in record
CREATE TABLE `prescriptions` (
  `prescription_id` int(11) NOT NULL AUTO_INCREMENT,
  `record_id` int(11) NOT NULL,
  `student_id` varchar(50) NOT NULL,
  `medicine_id` int(11) NOT NULL,
  `medicine_name` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `prescribed_by` varchar(50) NOT NULL,
  `prescribed_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` enum('active','completed') NOT NULL DEFAULT 'active',
  PRIMARY KEY (`prescription_id`),
  KEY `record_id` (`record_id`),
  KEY `student_id` (`student_id`),
  KEY `medicine_id` (`medicine_id`),
  FOREIGN KEY (`record_id`) REFERENCES `studentcheckins`(`ID`) ON DELETE CASCADE,
  FOREIGN KEY (`medicine_id`) REFERENCES `medicine`(`med_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

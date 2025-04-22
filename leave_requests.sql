CREATE TABLE `leave_requests` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `employee_name` VARCHAR(100) NOT NULL,
  `leave_type` VARCHAR(50) NOT NULL,
  `start_date` DATE NOT NULL,
  `end_date` DATE NOT NULL,
  `status` VARCHAR(20) NOT NULL DEFAULT 'Pending'
);

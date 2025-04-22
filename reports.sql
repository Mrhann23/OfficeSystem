CREATE TABLE reports (
    id INT AUTO_INCREMENT PRIMARY KEY,
    employee_name VARCHAR(100) NOT NULL,
    report_title VARCHAR(255) NOT NULL,
    submission_date DATE NOT NULL,
    status ENUM('Submitted', 'Reviewed', 'Approved', 'Rejected') DEFAULT 'Submitted',
    report_details TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

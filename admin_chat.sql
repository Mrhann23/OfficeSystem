CREATE TABLE community_chat (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_name VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    admin_reply TEXT,
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

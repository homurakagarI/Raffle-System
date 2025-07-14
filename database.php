<?php
/**
 * Database configuration and setup for the Raffle System
 * Uncomment and configure this file if you want to use database storage instead of sessions
 */

/*
// Database configuration
$host = 'localhost';
$dbname = 'raffle_system';
$username = 'your_username';
$password = 'your_password';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Create tables if they don't exist
$createTables = "
    CREATE TABLE IF NOT EXISTS participants (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        email VARCHAR(255) NOT NULL UNIQUE,
        entry_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        is_active BOOLEAN DEFAULT TRUE,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );

    CREATE TABLE IF NOT EXISTS raffle_draws (
        id INT AUTO_INCREMENT PRIMARY KEY,
        participant_id INT NOT NULL,
        draw_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        raffle_name VARCHAR(255) DEFAULT 'General Raffle',
        FOREIGN KEY (participant_id) REFERENCES participants(id)
    );

    CREATE TABLE IF NOT EXISTS raffle_events (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        description TEXT,
        start_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        end_date TIMESTAMP NULL,
        is_active BOOLEAN DEFAULT TRUE,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );
";

try {
    $pdo->exec($createTables);
} catch (PDOException $e) {
    die("Table creation failed: " . $e->getMessage());
}
*/

// Helper functions for database operations
/*
function addParticipant($pdo, $name, $email) {
    $stmt = $pdo->prepare("INSERT INTO participants (name, email) VALUES (?, ?)");
    return $stmt->execute([$name, $email]);
}

function getParticipants($pdo) {
    $stmt = $pdo->query("SELECT * FROM participants WHERE is_active = TRUE ORDER BY entry_time ASC");
    return $stmt->fetchAll();
}

function removeParticipant($pdo, $id) {
    $stmt = $pdo->prepare("UPDATE participants SET is_active = FALSE WHERE id = ?");
    return $stmt->execute([$id]);
}

function drawWinner($pdo) {
    $participants = getParticipants($pdo);
    if (empty($participants)) {
        return null;
    }
    
    $winner = $participants[array_rand($participants)];
    
    // Record the draw
    $stmt = $pdo->prepare("INSERT INTO raffle_draws (participant_id) VALUES (?)");
    $stmt->execute([$winner['id']]);
    
    return $winner;
}

function getLatestWinner($pdo) {
    $stmt = $pdo->query("
        SELECT p.*, rd.draw_time 
        FROM participants p 
        JOIN raffle_draws rd ON p.id = rd.participant_id 
        ORDER BY rd.draw_time DESC 
        LIMIT 1
    ");
    return $stmt->fetch();
}

function clearAllParticipants($pdo) {
    $stmt = $pdo->prepare("UPDATE participants SET is_active = FALSE");
    return $stmt->execute();
}
*/
?>

<?php
// Database connection
require_once('./connection.php');

// Check if the 'author' column exists in the 'books' table
$query = "DESCRIBE books";
$stmt = $pdo->query($query);
$columns = $stmt->fetchAll(PDO::FETCH_ASSOC);

$authorExists = false;
foreach ($columns as $column) {
    if ($column['Field'] === 'author') {
        $authorExists = true;
        break;
    }
}

if (!$authorExists) {
    try {
        // Add the 'author' column to the 'books' table
        $addAuthorColumnQuery = "ALTER TABLE books ADD COLUMN author VARCHAR(255) DEFAULT NULL";
        $pdo->exec($addAuthorColumnQuery);
        echo "The 'author' column has been added to the 'books' table.";
    } catch (Exception $e) {
        echo "Error adding 'author' column: " . $e->getMessage();
    }
} else {
    echo "The 'author' column already exists in the 'books' table.";
}
?>

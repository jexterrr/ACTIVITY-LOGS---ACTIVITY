<?php
try {
    $db = new PDO('mysql:host=localhost;dbname=jexterschoolpurposes', 'root', 'cicto2024');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>

<?php
include ('../db_connect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];

    $query = "INSERT INTO mor_role (name, description) VALUES (:name, :description)";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);

    if ($stmt->execute()) {
        echo "Role added successfully!";
    } else {
        echo "Error adding role.";
    }
}
?>

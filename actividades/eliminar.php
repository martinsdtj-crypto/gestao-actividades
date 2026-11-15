<?php

require_once '../config/database.php';

$id = $_GET['id'];

$sql = "DELETE FROM actividades WHERE id = ?";

$stmt = $conn->prepare($sql);

$stmt->bind_param("i", $id);

$stmt->execute();

header("Location: listar.php");
exit;
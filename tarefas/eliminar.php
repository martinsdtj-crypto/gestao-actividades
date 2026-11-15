<?php
require_once '../config/funcoes.php';
require_once '../config/database.php';


if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID da tarefa não fornecido.");
}

$id = $_GET['id'];

// Buscar tarefa para obter o ID da actividade 
$sql = "SELECT actividade_id
        FROM tarefas
        WHERE id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();

$resultado = $stmt->get_result();
$tarefa = $resultado->fetch_assoc();

if (!$tarefa) {
    die("Tarefa não encontrada.");
}

$actividade_id = $tarefa['actividade_id'];

// Eliminar tarefa 
$sql = "DELETE FROM tarefas WHERE id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {

    header(
        "Location: ../actividades/detalhes.php?id=" .
        $actividade_id
    );

    exit;
}
atualizarEstadoActividade($conn, $actividade_id);

echo "Erro ao eliminar tarefa.";
<?php

require_once '../config/database.php';


if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID da tarefa não fornecido.");
}

$id = $_GET['id'];
$actividade_id = null;
if (isset($_GET['act'])) {
    $actividade_id = $_GET['act'];
}

// Buscar tarefa 
$sql = "SELECT * FROM tarefas WHERE id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();

$resultado = $stmt->get_result();
$tarefa = $resultado->fetch_assoc();

if (!$tarefa) {
    die("Tarefa não encontrada.");
}

// Actualizar 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $titulo = $_POST['titulo'];
    $descricao = $_POST['descricao'];
    $ordem = $_POST['ordem_execucao'];
    $prioridade = $_POST['prioridade'];
    $estado = $_POST['estado'];

    $sql = "UPDATE tarefas
            SET titulo = ?,
                descricao = ?,
                ordem_execucao = ?,
                prioridade = ?,
                estado = ?
            WHERE id = ?";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param(
        "ssissi",
        $titulo,
        $descricao,
        $ordem,
        $prioridade,
        $estado,
        $id
    );

    if ($stmt->execute()) {

        header(
            "Location: ../actividades/detalhes.php?id=" .
            $tarefa['actividade_id']
        );

        exit;
    }
    require_once '../config/funcoes.php';

atualizarEstadoActividade($conn, $tarefa['actividade_id']);
}

?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Editar Tarefa</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<h1>Editar Tarefa</h1>

<form method="POST">

    <label>Título</label><br>
    <input
        type="text"
        name="titulo"
        value="<?= htmlspecialchars($tarefa['titulo']) ?>"
        required
    >
    <br><br>

    <label>Descrição</label><br>
    <textarea name="descricao"><?= htmlspecialchars($tarefa['descricao']) ?></textarea>
    <br><br>

    <label>Ordem de Execução</label><br>
    <input
        type="number"
        name="ordem_execucao"
        value="<?= $tarefa['ordem_execucao'] ?>"
        required
    >
    <br><br>

    <label>Prioridade</label><br>

    <select name="prioridade">

        <option value="Baixa"
            <?php
            if ($tarefa['prioridade'] == 'Baixa') {
                echo 'selected';
            }
            ?>>
            Baixa
        </option>

        <option value="Media"
            <?php
            if ($tarefa['prioridade'] == 'Media') {
                echo 'selected';
            }
            ?>>
            Média
        </option>

        <option value="Alta"
            <?php
            if ($tarefa['prioridade'] == 'Alta') {
                echo 'selected';
            }
            ?>>
            Alta
        </option>

    </select>

    <br><br>

    <label>Estado</label><br>

    <select name="estado">

        <option value="Pendente"
            <?php
            if ($tarefa['estado'] == 'Pendente') {
                echo 'selected';
            }
            ?>>
            Pendente
        </option>

        <option value="Em Andamento"
            <?php
            if ($tarefa['estado'] == 'Em Andamento') {
                echo 'selected';
            }
            ?>>
            Em Andamento
        </option>

        <option value="Concluida"
            <?php
            if ($tarefa['estado'] == 'Concluida') {
                echo 'selected';
            }
            ?>>
            Concluída
        </option>

    </select>

    <br><br>

    <button type="submit">
        Actualizar Tarefa
    </button>

</form>

<br>

<a href="../actividades/detalhes.php?id=<?= $tarefa['actividade_id'] ?>">
    Voltar
</a>

</body>
</html>
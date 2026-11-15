<?php

require_once '../config/database.php';

//Recber ID da act 
if (!isset($_GET['id'])) {
    die("Actividade não especificada.");
} 

$actividade_id = $_GET['id'];

//Guardar tarefa 
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $titulo = $_POST['titulo'];
    $descricao = $_POST['descricao'];
    $ordem = $_POST['ordem_execucao'];
    $prioridade = $_POST['prioridade'];

    $sql = "INSERT INTO tarefas
            (actividade_id, titulo, descricao, ordem_execucao, prioridade)
            VALUES (?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param(
        "issis",
        $actividade_id,
        $titulo,
        $descricao,
        $ordem,
        $prioridade
    );

    $stmt->execute();
    require_once '../config/funcoes.php';

    atualizarEstadoActividade($conn, $actividade_id);

    header("Location: ../actividades/detalhes.php?id=$actividade_id");
    exit;
}

?>
<head>
    <meta charset="UTF-8">
    <title>Criar Tarefa</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<div class="container">

    <h1>Nova Tarefa</h1>

    <form method="POST" class="form">

        <div class="form-group">
            <label>Título</label>
            <input type="text" name="titulo" required>
        </div>

        <div class="form-group">
            <label>Descrição</label>
            <textarea name="descricao"></textarea>
        </div>

        <div class="form-group">
            <label>Ordem de Execução</label>
            <input type="number" name="ordem_execucao" required>
        </div>

        <div class="form-group">
            <label>Prioridade</label>
            <select name="prioridade">
                <option value="Baixa">Baixa</option>
                <option value="Media">Média</option>
                <option value="Alta">Alta</option>
            </select>
        </div>

        <button type="submit" class="btn btn-success">
            Guardar Tarefa
        </button>

    </form>
 <a class="btn btn-primary" href="../actividades/detalhes.php">
        ← Voltar às tarefas
    </a>
</div>
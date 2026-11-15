<?php
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $titulo = $_POST['titulo'];
    $descricao = $_POST['descricao'];
    $data_inicio = $_POST['data_inicio'];
    $prazo = $_POST['prazo'];
    $duracao = $_POST['duracao'];
    $prioridade = $_POST['prioridade'];

    $sql = "INSERT INTO actividades
            (titulo, descricao, data_inicio, prazo, duracao, prioridade)
            VALUES
            (?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "ssssis",
        $titulo,
        $descricao,
        $data_inicio,
        $prazo,
        $duracao,
        $prioridade
    );

    if ($stmt->execute()) {
        header("Location: listar.php");
        exit;
    } else {
        echo "Erro ao guardar actividade.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Nova Actividade</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>

<div class="container">

    <h1>Nova Actividade</h1>

    <form method="POST">

        <div class="form-group">
            <label>Título</label>
            <input type="text" name="titulo" required>
        </div>

        <div class="form-group">
            <label>Descrição</label>
            <textarea name="descricao"></textarea>
        </div>

        <div class="form-row">

            <div class="form-group">
                <label>Data de Início</label>
                <input type="date" name="data_inicio" required>
            </div>

            <div class="form-group">
                <label>Prazo</label>
                <input type="date" name="prazo" required>
            </div>

        </div>

        <div class="form-row">

            <div class="form-group">
                <label>Duração (dias)</label>
                <input type="number" name="duracao" required>
            </div>

            <div class="form-group">
                <label>Prioridade</label>
                <select name="prioridade">
                    <option value="Baixa">Baixa</option>
                    <option value="Media">Média</option>
                    <option value="Alta">Alta</option>
                </select>
            </div>

        </div>

        <button type="submit" class="btn btn-success">
            Guardar Actividade
        </button>

    </form>
 <a class="btn btn-primary" href="listar.php">
        ← Voltar à Lista
    </a>
</div>

</body>
</html>
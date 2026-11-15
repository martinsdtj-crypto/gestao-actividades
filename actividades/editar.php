<?php

require_once '../config/database.php';

$id = $_GET['id'];

$sql = "SELECT * FROM actividades WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();

$resultado = $stmt->get_result();
$actividade = $resultado->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $titulo = $_POST['titulo'];
    $descricao = $_POST['descricao'];
    $data_inicio = $_POST['data_inicio'];
    $prazo = $_POST['prazo'];
    $duracao = $_POST['duracao'];
    $prioridade = $_POST['prioridade'];
    $estado = $_POST['estado'];

    $sql = "UPDATE actividades
            SET titulo=?,
                descricao=?,
                data_inicio=?,
                prazo=?,
                duracao=?,
                prioridade=?,
                estado=?
            WHERE id=?";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param(
        "ssssissi",
        $titulo,
        $descricao,
        $data_inicio,
        $prazo,
        $duracao,
        $prioridade,
        $estado,
        $id
    );

    $stmt->execute();

    header("Location: listar.php");
    exit;
}
?>

<form method="POST" class="form">

    <div class="form-group">
        <label>Título</label>
        <input type="text" name="titulo" value="<?= $actividade['titulo'] ?>" required>
    </div>

    <div class="form-group">
        <label>Descrição</label>
        <textarea name="descricao"><?= $actividade['descricao'] ?></textarea>
    </div>

    <div class="form-row">

        <div class="form-group">
            <label>Data de Início</label>
            <input type="date" name="data_inicio" value="<?= $actividade['data_inicio'] ?>" required>
        </div>

        <div class="form-group">
            <label>Prazo</label>
            <input type="date" name="prazo" value="<?= $actividade['prazo'] ?>" required>
        </div>

    </div>

    <div class="form-group">
        <label>Duração</label>
        <input type="number" name="duracao" value="<?= $actividade['duracao'] ?>" required>
    </div>

    <div class="form-row">

        <div class="form-group">
            <label>Prioridade</label>
            <select name="prioridade">
                <option value="Baixa" <?= $actividade['prioridade']=='Baixa'?'selected':'' ?>>Baixa</option>
                <option value="Media" <?= $actividade['prioridade']=='Media'?'selected':'' ?>>Media</option>
                <option value="Alta" <?= $actividade['prioridade']=='Alta'?'selected':'' ?>>Alta</option>
            </select>
        </div>

        <div class="form-group">
            <label>Estado</label>
            <select name="estado">
                <option value="Pendente" <?= $actividade['estado']=='Pendente'?'selected':'' ?>>Pendente</option>
                <option value="Em Andamento" <?= $actividade['estado']=='Em Andamento'?'selected':'' ?>>Em Andamento</option>
                <option value="Concluida" <?= $actividade['estado']=='Concluida'?'selected':'' ?>>Concluída</option>
            </select>
        </div>

    </div>

    <button type="submit" class="btn btn-primary">
        Actualizar Actividade
    </button>
    <a class="btn btn-primary" href="listar.php">
        ← Voltar à Lista
    </a>
</form>
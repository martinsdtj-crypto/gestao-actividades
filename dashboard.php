<?php
require_once 'config/database.php';

/* TOTAL DE ACTIVIDADES */
$resultadoContagem = $conn->query("SELECT COUNT(*) as total FROM actividades");
$contagem = $resultadoContagem->fetch_assoc();
$totalAct = $contagem['total'];

/* ACTIVIDADES POR ESTADO */
$resultadoContagem = $conn->query("SELECT COUNT(*) as total FROM actividades WHERE estado='Pendente'");
$contagem = $resultadoContagem->fetch_assoc();
$pendentes = $contagem['total'];

$resultadoContagem = $conn->query("SELECT COUNT(*) as total FROM actividades WHERE estado='Em Andamento'");
$contagem = $resultadoContagem->fetch_assoc();
$andamento = $contagem['total'];

$resultadoContagem = $conn->query("SELECT COUNT(*) as total FROM actividades WHERE estado='Concluida'");
$contagem = $resultadoContagem->fetch_assoc();
$concluidas = $contagem['total'];

/* TOTAL DE TAREFAS */
$resultadoContagem = $conn->query("SELECT COUNT(*) as total FROM tarefas");
$contagem = $resultadoContagem->fetch_assoc();
$totalTarefas = $contagem['total'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>

<div class="container">

    <h1>Dashboard</h1>

    <div class="grid">

        <div class="card">
            <h2>Total Actividades</h2>
            <p><?= $totalAct ?></p>
        </div>

        <div class="card">
            <h2>Pendentes</h2>
            <p><?= $pendentes ?></p>
        </div>

        <div class="card">
            <h2>Em Andamento</h2>
            <p><?= $andamento ?></p>
        </div>

        <div class="card">
            <h2>Concluídas</h2>
            <p><?= $concluidas ?></p>
        </div>

        <div class="card">
            <h2>Total Tarefas</h2>
            <p><?= $totalTarefas ?></p>
        </div>

    </div>

    <br>

    <a href="actividades/listar.php" class="btn btn-primary">
        Ir para Actividades
    </a>

</div>

</body>
</html>
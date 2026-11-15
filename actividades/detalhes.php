<?php

    require_once '../config/database.php';

    // Validar ID 
    if (!isset($_GET['id']) || empty($_GET['id'])) {
        die("ID da actividade não fornecido.");
    }

    $id = $_GET['id'];

    // Buscar actividade 
    $sql = "SELECT * FROM actividades WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();

    $resultado = $stmt->get_result();
    $actividade = $resultado->fetch_assoc();

    if (!$actividade) {
        die("Actividade não encontrada.");
    }

    // Buscar tarefas 
    $sqlTarefas = "SELECT * FROM tarefas
                WHERE actividade_id = ?
                ORDER BY ordem_execucao ASC";

    $stmtTarefas = $conn->prepare($sqlTarefas);
    $stmtTarefas->bind_param("i", $id);
    $stmtTarefas->execute();

    $tarefas = $stmtTarefas->get_result();

    // Contar progresso 
    $sqlTotal = "SELECT COUNT(*) AS total
                FROM tarefas
                WHERE actividade_id = ?";

    $stmt1 = $conn->prepare($sqlTotal);
    $stmt1->bind_param("i", $id);
    $stmt1->execute();

    $resultadoPedido = $stmt1->get_result();
    $registo = $resultadoPedido->fetch_assoc();
    $total = $registo['total'];

    $sqlConcluidas = "SELECT COUNT(*) AS total
                    FROM tarefas
                    WHERE actividade_id = ?
                    AND estado = 'Concluida'";

    $stmt2 = $conn->prepare($sqlConcluidas);
    $stmt2->bind_param("i", $id);
    $stmt2->execute();

    $resultadoPedido = $stmt2->get_result();
    $registo = $resultadoPedido->fetch_assoc();
    $concluidas = $registo['total'];

    $percentagem = 0;
    if ($total > 0) {
        $percentagem = $concluidas * 100 / $total;
    }

?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Detalhes da Actividade</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>

<div class="container">

    <h1><?= htmlspecialchars($actividade['titulo']) ?></h1>

    <div class="card">

        <p><strong>Descrição:</strong> <?= htmlspecialchars($actividade['descricao']) ?></p>
        <p><strong>Data de Início:</strong> <?= $actividade['data_inicio'] ?></p>
        <p><strong>Prazo:</strong> <?= $actividade['prazo'] ?></p>
        <p><strong>Estado:</strong> <?= $actividade['estado'] ?></p>

    </div>

    <hr>

    <h2>Tarefas</h2>

    <a class="btn btn-success" href="../tarefas/criar.php?id=<?= $id ?>">
        + Nova Tarefa
    </a>

    <br><br>

    <?php if ($tarefas->num_rows > 0): ?>

        <table>

            <tr>
                <th>Ordem</th>
                <th>Título</th>
                <th>Descrição</th>
                <th>Prioridade</th>
                <th>Estado</th>
                <th>Ações</th>
            </tr>

            <?php while ($tarefa = $tarefas->fetch_assoc()): ?>

            <tr>
                <td><?= $tarefa['ordem_execucao'] ?></td>
                <td><?= htmlspecialchars($tarefa['titulo']) ?></td>
                <td><?= htmlspecialchars($tarefa['descricao']) ?></td>
                <td><?= $tarefa['prioridade'] ?></td>
                <td><?= $tarefa['estado'] ?></td>

                <td>
                    <a class="btn btn-primary btn-sm"
                       href="../tarefas/editar.php?id=<?= $tarefa['id'] ?>&act=<?= $id ?>">
                        Editar
                    </a>

                    <a class="btn btn-danger btn-sm"
                       href="../tarefas/eliminar.php?id=<?= $tarefa['id'] ?>&act=<?= $id ?>"
                       onclick="return confirm('Deseja eliminar esta tarefa?')">
                        Eliminar
                    </a>
                </td>
            </tr>

            <?php endwhile; ?>

        </table>

        <h2>Progresso</h2>

        <div class="progress-bar">
            <div class="progress" style="width: <?= round($percentagem) ?>%;">
                <?= round($percentagem) ?>%
            </div>
        </div>

        <p>
            <?= $concluidas ?> de <?= $total ?> tarefas concluídas
        </p>

    <?php else: ?>

        <p>Nenhuma tarefa registada para esta actividade.</p>

    <?php endif; ?>

    <br>

    <a class="btn btn-primary" href="listar.php">
        ← Voltar à lista
    </a>

    <a class="btn btn-primary" href="../dashboard.php">
        ← Voltar à Dashboard
    </a>
</div>

</body>
</html>
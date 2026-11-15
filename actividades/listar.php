<?php


error_reporting(E_ALL);
ini_set('display_errors', 1);


require_once '../config/database.php';

// Função para atualizar estado da actividade 
function atualizarEstadoActividade($conn, $actividade_id)
{
    $sql = "SELECT estado FROM tarefas WHERE actividade_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $actividade_id);
    $stmt->execute();

    $result = $stmt->get_result();

    $total = 0;
    $concluidas = 0;
    $emAndamento = 0;

    while ($row = $result->fetch_assoc()) {

        $total++;
        $estado = trim($row['estado']);

        if ($estado === 'Concluida') {
            $concluidas++;
        }

        if ($estado === 'Em Andamento') {
            $emAndamento++;
        }
    }

    if ($total > 0 && $concluidas === $total) {
        $estadoFinal = 'Concluida';
    }
    elseif ($emAndamento > 0) {
        $estadoFinal = 'Em Andamento';
    }
    else {
        $estadoFinal = 'Pendente';
    }

    $sql = "UPDATE actividades SET estado = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $estadoFinal, $actividade_id);
    $stmt->execute();
}

// Buscar actividades 
$sql = "SELECT * FROM actividades ORDER BY id DESC";
$resultado = $conn->query($sql);

// Atualizar estados automaticamente ao carregar a lista 
while ($act = $resultado->fetch_assoc()) {
    atualizarEstadoActividade($conn, $act['id']);
}

// Recarregar dados atualizados 
$sql = "SELECT * FROM actividades ORDER BY id DESC";
$resultado = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Actividades</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>

<div class="container">

    <h1>Lista de Actividades</h1>

    <a href="criar.php" class="btn btn-success">
        + Nova Actividade
    </a>

    <br><br>

    <table>

        <tr>
            <th>ID</th>
            <th>Título</th>
            <th>Início</th>
            <th>Prazo</th>
            <th>Estado</th>
            <th>Ações</th>
        </tr>

        <?php while($actividade = $resultado->fetch_assoc()): ?>

        <tr>

            <td><?= $actividade['id'] ?></td>
            <td><?= htmlspecialchars($actividade['titulo']) ?></td>
            <td><?= $actividade['data_inicio'] ?></td>
            <td><?= $actividade['prazo'] ?></td>

            <td>
                <?php
                if($actividade['estado'] == 'Pendente'){
                    echo '<span class="badge badge-pendente">Pendente</span>';
                }
                elseif($actividade['estado'] == 'Em Andamento'){
                    echo '<span class="badge badge-andamento">Em Andamento</span>';
                }
                else{
                    echo '<span class="badge badge-concluida">Concluída</span>';
                }
                ?>
            </td>

            <td>
                <a href="detalhes.php?id=<?= $actividade['id'] ?>"
                   class="btn btn-primary">
                    Detalhes
                </a>
            </td>

        </tr>

        <?php endwhile; ?>

    </table>
 <a class="btn btn-primary" href="../dashboard.php">
        ← Voltar à Dashboard
    </a>
</div>

</body>
</html>
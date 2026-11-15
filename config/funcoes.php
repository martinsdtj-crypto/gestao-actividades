<?php

function atualizarEstadoActividade($conn, $actividade_id)
{
    // contar tarefas
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

        if ($row['estado'] == 'Concluida') {
            $concluidas++;
        }

        if ($row['estado'] == 'Em Andamento') {
            $emAndamento++;
        }
    }

    // definir estado da actividade
    if ($total > 0 && $concluidas == $total) {
        $estado = 'Concluida';
    }
    elseif ($emAndamento > 0) {
        $estado = 'Em Andamento';
    }
    else {
        $estado = 'Pendente';
    }

    // atualizar actividade
    $sql = "UPDATE actividades SET estado = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $estado, $actividade_id);
    $stmt->execute();
}
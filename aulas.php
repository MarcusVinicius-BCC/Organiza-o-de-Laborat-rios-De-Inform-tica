<?php
function load_data($file) {
    $path = __DIR__ . "/data/" . $file;
    if (!file_exists($path)) return [];
    return json_decode(file_get_contents($path), true) ?: [];
}
function save_data($file, $data) {
    $path = __DIR__ . "/data/" . $file;
    file_put_contents($path, json_encode($data, JSON_PRETTY_PRINT));
}
$labs = load_data('laboratorios.json');
$professores = load_data('professores.json');
$aulas = load_data('aulas.json');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $laboratorio_id = intval($_POST['laboratorio_id']);
    $professor_id = intval($_POST['professor_id']);
    $turno = $_POST['turno'];
    $dia_semana = $_POST['dia_semana'];
    $conflito = false;
    foreach ($aulas as $a) {
        if ($a['laboratorio_id'] == $laboratorio_id && $a['turno'] == $turno && $a['dia_semana'] == $dia_semana) {
            $conflito = true;
            break;
        }
    }
    if (!$conflito) {
        $aula = [
            'id' => count($aulas)+1,
            'disciplina' => $_POST['disciplina'],
            'professor_id' => $professor_id,
            'laboratorio_id' => $laboratorio_id,
            'turno' => $turno,
            'dia_semana' => $dia_semana
        ];
        $aulas[] = $aula;
        save_data('aulas.json', $aulas);
    }
    header('Location: aulas.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Aulas</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="header">
        <i class="fas fa-book"></i> Aulas Agendadas
    </div>
    <nav>
        <a href="index.php"><i class="fas fa-chart-bar"></i> Dashboard</a>
        <a href="laboratorios.php"><i class="fas fa-flask"></i> Laboratórios</a>
        <a href="monitores.php"><i class="fas fa-user-astronaut"></i> Monitores</a>
        <a href="professores.php"><i class="fas fa-chalkboard-teacher"></i> Professores</a>
        <a href="aulas.php"><i class="fas fa-book"></i> Aulas</a>
        <a href="horarios.php"><i class="fas fa-calendar-alt"></i> Horários</a>
    </nav>
    <div class="container">
        <h2>Agendar Aula</h2>
        <form method="POST">
            <p>Disciplina: <input type="text" name="disciplina" required></p>
            <p>Professor:
                <select name="professor_id" required>
                    <?php foreach ($professores as $prof): ?>
                    <option value="<?= $prof['id'] ?>"><?= htmlspecialchars($prof['nome']) ?> - <?= htmlspecialchars($prof['disciplina']) ?></option>
                    <?php endforeach; ?>
                </select>
            </p>
            <p>Laboratório:
                <select name="laboratorio_id" required>
                    <?php foreach ($labs as $lab): ?>
                    <option value="<?= $lab['id'] ?>"><?= htmlspecialchars($lab['nome']) ?> (<?= htmlspecialchars($lab['numero']) ?>)</option>
                    <?php endforeach; ?>
                </select>
            </p>
            <p>Turno:
                <select name="turno" required>
                    <option value="manhã">Manhã</option>
                    <option value="tarde">Tarde</option>
                    <option value="noite">Noite</option>
                </select>
            </p>
            <p>Dia da Semana:
                <select name="dia_semana" required>
                    <option value="segunda">Segunda</option>
                    <option value="terça">Terça</option>
                    <option value="quarta">Quarta</option>
                    <option value="quinta">Quinta</option>
                    <option value="sexta">Sexta</option>
                </select>
            </p>
            <p><input type="submit" value="Agendar"></p>
        </form>
        <h3>Aulas Agendadas</h3>
        <div class="dashboard-cards">
            <?php foreach ($aulas as $aula): ?>
            <?php $prof = null; foreach ($professores as $p) { if ($p['id'] == $aula['professor_id']) { $prof = $p; break; } } ?>
            <?php $lab = null; foreach ($labs as $l) { if ($l['id'] == $aula['laboratorio_id']) { $lab = $l; break; } } ?>
            <div class="card">
                <div class="lab-title"><i class="fas fa-book"></i> <?= htmlspecialchars($aula['disciplina']) ?></div>
                <div class="lab-info"><i class="fas fa-chalkboard-teacher"></i> Professor: <?= $prof ? htmlspecialchars($prof['nome']) : '' ?></div>
                <div class="lab-info"><i class="fas fa-flask"></i> Laboratório: <?= $lab ? htmlspecialchars($lab['nome']) : '' ?></div>
                <div class="lab-info"><i class="fas fa-clock"></i> Turno: <?= htmlspecialchars($aula['turno']) ?></div>
                <div class="lab-info"><i class="fas fa-calendar-alt"></i> Dia: <?= htmlspecialchars($aula['dia_semana']) ?></div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>

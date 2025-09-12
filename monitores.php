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
$monitores = load_data('monitores.json');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $laboratorio_id = intval($_POST['laboratorio_id']);
    $turno = $_POST['turno'];
    $existe = false;
    foreach ($monitores as $m) {
        if ($m['laboratorio_id'] == $laboratorio_id && $m['turno'] == $turno) {
            $existe = true;
            break;
        }
    }
    if (!$existe) {
        $monitor = [
            'id' => count($monitores)+1,
            'nome' => $_POST['nome'],
            'matricula' => $_POST['matricula'],
            'turno' => $turno,
            'laboratorio_id' => $laboratorio_id
        ];
        $monitores[] = $monitor;
        save_data('monitores.json', $monitores);
    }
    header('Location: monitores.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Monitores</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="header">
        <i class="fas fa-user-astronaut"></i> Monitores de Laboratório
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
        <h2>Cadastrar Monitor</h2>
        <form method="POST">
            <p>Nome: <input type="text" name="nome" required></p>
            <p>Matrícula: <input type="text" name="matricula" required></p>
            <p>Turno:
                <select name="turno" required>
                    <option value="manhã">Manhã</option>
                    <option value="tarde">Tarde</option>
                    <option value="noite">Noite</option>
                </select>
            </p>
            <p>Laboratório:
                <select name="laboratorio_id" required>
                    <?php foreach ($labs as $lab): ?>
                    <option value="<?= $lab['id'] ?>"><?= htmlspecialchars($lab['nome']) ?> (<?= htmlspecialchars($lab['numero']) ?>)</option>
                    <?php endforeach; ?>
                </select>
            </p>
            <p><input type="submit" value="Cadastrar"></p>
        </form>
        <h3>Monitores Cadastrados</h3>
        <div class="dashboard-cards">
            <?php foreach ($monitores as $monitor): ?>
            <?php $lab = null; foreach ($labs as $l) { if ($l['id'] == $monitor['laboratorio_id']) { $lab = $l; break; } } ?>
            <div class="card">
                <div class="lab-title"><i class="fas fa-user-astronaut"></i> <?= htmlspecialchars($monitor['nome']) ?></div>
                <div class="lab-info"><i class="fas fa-id-badge"></i> Matrícula: <?= htmlspecialchars($monitor['matricula']) ?></div>
                <div class="lab-info"><i class="fas fa-clock"></i> Turno: <?= htmlspecialchars($monitor['turno']) ?></div>
                <div class="lab-info"><i class="fas fa-flask"></i> Laboratório: <?= $lab ? htmlspecialchars($lab['nome']) : '' ?></div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>

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
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $lab = [
        'id' => count($labs)+1,
        'nome' => $_POST['nome'],
        'numero' => $_POST['numero'],
        'capacidade' => intval($_POST['capacidade']),
        'projetor' => !empty($_POST['projetor'])
    ];
    $labs[] = $lab;
    save_data('laboratorios.json', $labs);
    header('Location: laboratorios.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Laboratórios</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="header">
        <i class="fas fa-flask"></i> Laboratórios de Informática
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
        <h2>Cadastrar Laboratório</h2>
        <form method="POST">
            <p>Nome: <input type="text" name="nome" required></p>
            <p>Número: <input type="text" name="numero" required></p>
            <p>Capacidade: <input type="number" name="capacidade" required></p>
            <p><label><input type="checkbox" name="projetor"> Projetor</label></p>
            <p><input type="submit" value="Cadastrar"></p>
        </form>
        <h3>Laboratórios Cadastrados</h3>
        <div class="dashboard-cards">
            <?php foreach ($labs as $lab): ?>
            <div class="card">
                <div class="lab-title"><i class="fas fa-flask"></i> <?= htmlspecialchars($lab['nome']) ?> (<?= htmlspecialchars($lab['numero']) ?>)</div>
                <div class="lab-info"><i class="fas fa-users"></i> Capacidade: <?= htmlspecialchars($lab['capacidade']) ?></div>
                <div class="lab-info"><i class="fas fa-video"></i> Projetor: <?= !empty($lab['projetor']) ? 'Sim' : 'Não' ?></div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>

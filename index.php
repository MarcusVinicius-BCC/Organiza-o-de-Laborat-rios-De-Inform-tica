<?php
function load_data($file) {
    $path = __DIR__ . "/data/" . $file;
    if (!file_exists($path)) return [];
    return json_decode(file_get_contents($path), true) ?: [];
}
$labs = load_data('laboratorios.json');
$aulas = load_data('aulas.json');
$aulas_por_lab = [];
foreach ($labs as $lab) {
    $aulas_por_lab[$lab['id']] = 0;
}
foreach ($aulas as $aula) {
    if (isset($aulas_por_lab[$aula['laboratorio_id']])) {
        $aulas_por_lab[$aula['laboratorio_id']]++;
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Gerenciamento de Laboratórios</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="header">
        <i class="fas fa-microchip"></i> Gerenciamento de Laboratórios de Informática
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
        <h1>Dashboard</h1>
        <div class="dashboard-cards">
            <?php foreach ($labs as $lab): ?>
            <div class="card">
                <div class="lab-title"><i class="fas fa-flask"></i> <?= htmlspecialchars($lab['nome']) ?> (<?= htmlspecialchars($lab['numero']) ?>)</div>
                <div class="lab-info"><i class="fas fa-users"></i> Capacidade: <?= htmlspecialchars($lab['capacidade']) ?></div>
                <div class="lab-info"><i class="fas fa-video"></i> Projetor: <input type="checkbox" disabled <?= !empty($lab['projetor']) ? 'checked' : '' ?>></div>
                <div class="lab-aulas"><i class="fas fa-book"></i> Aulas na Semana: <?= $aulas_por_lab[$lab['id']] ?></div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>

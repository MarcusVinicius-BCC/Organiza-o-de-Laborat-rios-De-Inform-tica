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
$professores = load_data('professores.json');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $prof = [
        'id' => count($professores)+1,
        'nome' => $_POST['nome'],
        'disciplina' => $_POST['disciplina']
    ];
    $professores[] = $prof;
    save_data('professores.json', $professores);
    header('Location: professores.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Professores</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="header">
        <i class="fas fa-chalkboard-teacher"></i> Professores
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
        <h2>Cadastrar Professor</h2>
        <form method="POST">
            <p>Nome: <input type="text" name="nome" required></p>
            <p>Disciplina: <input type="text" name="disciplina" required></p>
            <p><input type="submit" value="Cadastrar"></p>
        </form>
        <h3>Professores Cadastrados</h3>
        <div class="dashboard-cards">
            <?php foreach ($professores as $prof): ?>
            <div class="card">
                <div class="lab-title"><i class="fas fa-chalkboard-teacher"></i> <?= htmlspecialchars($prof['nome']) ?></div>
                <div class="lab-info"><i class="fas fa-book"></i> Disciplina: <?= htmlspecialchars($prof['disciplina']) ?></div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>

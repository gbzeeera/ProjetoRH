<?php
// home.php
include '../includes/functions.php';

if (!isUserLoggedIn()) {
    header("Location: login.php");
    exit();
}

$searchTerm = '';
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['search'])) {
    $searchTerm = filter_var($_POST['search'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $colaboradores = getColaboradores($searchTerm);
} else {
    $colaboradores = getColaboradores();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Home</title>
    <link rel="stylesheet" type="text/css" href="../misc/style2.css">
    <link rel="icon" href="../misc/Ico.png" type="image">
</head>
<body>
    <div class="navbar">
       <a href="../includes/logout.php" class="right">Sair</a>
       <a href="../includes/export.php" class="btn">Exportar toda base de dados para Excel</a>
    </div>

    <h2>Lista de Colaboradores</h2>
    
    <form method="post" action="home.php">
        <input type="text" name="search" placeholder="Pesquisar por nome ou matrícula" value="<?php echo htmlspecialchars($searchTerm); ?>">
        <button type="submit" class="btn">Pesquisar</button>
        <button href="#" onclick="window.location.reload();">Atualizar</button>
    </form>
    
    <table>
        <tr>
            <th>Nome</th>
            <th>Matrícula</th>
            <th>Data de Envio</th>
            <th>Download PDF</th>
        </tr>
        <?php if (count($colaboradores) > 0): ?>
            <?php 
                foreach ($colaboradores as $colaborador): 
                    $nomeDestacado = $colaborador['nome'];
                    $matriculaDestacado = $colaborador['matricula'];
                    $dataEnvio = $colaborador['data_envio'];

                    if ($searchTerm) {
                        $nomeDestacado = str_ireplace($searchTerm, "<span class='highlight'>$searchTerm</span>", $nomeDestacado);
                        $matriculaDestacado = str_ireplace($searchTerm, "<span class='highlight'>$searchTerm</span>", $matriculaDestacado);
                    }
            ?>
                <tr>
                    <td><?php echo $nomeDestacado; ?></td>
                    <td><?php echo $matriculaDestacado; ?></td>
                    <td><?php echo htmlspecialchars($dataEnvio); ?></td>
                    <td><a href="../includes/download.php?matricula=<?php echo $colaborador['matricula']; ?>" class="btn">Download</a></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="4">Nenhum colaborador encontrado.</td>
            </tr>
        <?php endif; ?>
    </table>
</body>
</html>



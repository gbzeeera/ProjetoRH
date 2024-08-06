<?php
// register.php
include '../includes/functions.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (registerUser($username, $password)) {
        header("Location: login.php");
        exit();
    } else {
        $error_message = "Usuário já existe ou ocorreu um erro ao registrar.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Cadastro</title>
    <link rel="stylesheet" type="text/css" href="../misc/styles.css">
    <link rel="icon" href="../misc/Ico.png" type="image">
</head>
<body>
    <h2>Cadastro</h2>
    <form method="post">
        <div class="form-group">
            <label for="username">Usuário:</label>
            <input type="text" id="username" name="username" required>
        </div>
        <div class="form-group">
            <label for="password">Senha:</label>
            <input type="password" id="password" name="password" required>
        </div>
        <div class="form-group">
            <button type="submit" class="btn">Cadastrar</button>
        </div>
        <?php if (isset($error_message)): ?>
            <p><?php echo $error_message; ?></p>
        <?php endif; ?>
    </form>
</body>
</html>

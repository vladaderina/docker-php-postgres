<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $login = $_POST['login'];
    $pass = $_POST['pass'];

    // Подключение к базе данных
    $db = new PDO('pgsql:host=postgres;dbname=mydatabase;port=5433', 'myuser', 'mypassword');

    // Проверка логина и пароля
    $stmt = $db->prepare("SELECT * FROM users WHERE login = :login AND pass = :pass");
    $stmt->execute(['login' => $login, 'pass' => $pass]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Сохранение данных пользователя в сессии
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_login'] = $user['login'];

        // Перенаправление на основную страницу
        header('Location: index.php');
        exit();
    } else {
        $error = "Неверный логин или пароль";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Авторизация</title>
</head>
<body>
    <h1>Авторизация</h1>
    <?php if (isset($error)) echo "<p>$error</p>"; ?>
    <form method="post">
        <label for="login">Логин:</label>
        <input type="text" id="login" name="login" required><br>
        <label for="pass">Пароль:</label>
        <input type="password" id="pass" name="pass" required><br>
        <button type="submit">Войти</button>
    </form>
</body>
</html>
<?php
session_start();

// Проверка, авторизован ли пользователь
if (!isset($_SESSION['user_id'])) {
    header('Location: authorization.php');
    exit();
}

// Подключение к базе данных
$db = new PDO('pgsql:host=postgres;dbname=weba2', 'postgres');

// Получение ролей пользователя
$stmt = $db->prepare("SELECT roles.code FROM roles JOIN user_roles ON roles.id = user_roles.role_id WHERE user_roles.user_id = :user_id");
$stmt->execute(['user_id' => $_SESSION['user_id']]);
$roles = $stmt->fetchAll(PDO::FETCH_COLUMN);

// Обработка выхода
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: authorization.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Основная страница</title>
</head>
<body>
    <h1>Добро пожаловать, <?php echo $_SESSION['user_name']; ?>!</h1>
    <p>Логин: <?php echo $_SESSION['user_login']; ?></p>
    <a href="?logout=1">Выход</a>

    <?php if (!in_array('ADM', $roles)): ?>
        <p>У вас нет доступа для просмотра статистики!</p>
    <?php else: ?>
        <h2>Статистика</h2>
        <table border="1">
            <tr>
                <th>Имя пользователя</th>
                <th>Логин</th>
                <th>Роли</th>
            </tr>
            <?php
            $stmt = $db->query("
                SELECT users.name, users.login, string_agg(roles.name, ', ') AS roles
                FROM users
                JOIN user_roles ON users.id = user_roles.user_id
                JOIN roles ON user_roles.role_id = roles.id
                GROUP BY users.id
                ORDER BY users.name
            ");
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                <tr>
                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo $row['login']; ?></td>
                    <td><?php echo $row['roles']; ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php endif; ?>
</body>
</html>

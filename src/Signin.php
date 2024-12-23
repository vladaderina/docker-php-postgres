<?php
session_start();

if (isset($_POST['submit'])) {
    // Получаем данные из формы
    $user = $_POST['user'];
    $email = $_POST['email'];
    $email = strtolower($email);
    $password = $_POST['password'];

    // Проверка на пустые поля
    if (empty($email) || empty($password)) {
        header("Location: Main.php?blank");
        exit();
    }

    // Проверка допустимых таблиц
    $allowed_tables = ['customers', 'admin'];
    if (!in_array($user, $allowed_tables)) {
        header("Location: Main.php?error");
        exit();
    }

    // Проверка формата email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: Main.php?invalid_email");
        exit();
    }

    // Подключение к базе данных
    $host = getenv('DB_HOST');
    $dbname = getenv('DB_NAME');
    $user_db = getenv('DB_USER');
    $password_db = getenv('DB_PASSWORD');

    $conn = pg_connect("host=$host dbname=$dbname user=$user_db password=$password_db") or die('Connection failed: ' . pg_last_error());

    // Безопасный запрос с использованием параметров
    $sql = "SELECT * FROM $user WHERE email = $1";
    $result = pg_query_params($conn, $sql, array($email));

    if (pg_num_rows($result) == 0) {
        header("Location: Main.php?error");
        exit();
    }

    $row = pg_fetch_assoc($result);

    // Проверка хешированного пароля
    if ($password == $row['password']){
        // Устанавливаем сессию
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['user_roles'] = [$user];

        // Перенаправление в зависимости от роли
        if ($user == "customers") {
            header("Location: Shop.php?submit=1&id=" . $row['id']);
            exit();
        } elseif ($user == "admin") {
            header("Location: Admin.php?submit=9");
            exit();
        }
    } else {
        header("Location: Main.php?error" . $row['password']);
        exit();
    }
} else {
    header("Location: Main.php");
    exit();
}
?>
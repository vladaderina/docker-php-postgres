<?php
// Подключение к базе данных
 # Connect to PostgreSQL database
 $host = getenv('DB_HOST');
 $dbname = getenv('DB_NAME');
 $user_db = getenv('DB_USER');
 $password_db = getenv('DB_PASSWORD');
 
 $conn = pg_connect("host=$host dbname=$dbname user=$user_db password=$password_db");
 if (!$conn) {
     die('Database connection failed: ' . pg_last_error());
 }
if (!$conn) {
    die("Ошибка подключения к базе данных.");
}

// Обработка формы регистрации
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $last_name = $_POST['last_name'];
    $first_name = $_POST['first_name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $credit_card_num = $_POST['credit_card_num'];
    $credit_card_expiry_date = $_POST['cce_m'] . '/' . $_POST['cce_y'];

    // SQL-запрос для вставки данных
    $query = "INSERT INTO customers (last_name, first_name, email, password, credit_card_num, credit_card_expiry_date) 
              VALUES ($1, $2, $3, $4, $5, $6)";

    // Параметры для запроса
    $params = array($last_name, $first_name, $email, $password, $credit_card_num, $credit_card_expiry_date);

    // Выполнение запроса
    $result = pg_query_params($conn, $query, $params);

    if (!$result) {
        // Получение текста ошибки
        $error_message = pg_last_error($conn);

        // Проверка, является ли ошибка дублированием ключа
        if (strpos($error_message, 'duplicate key value violates unique constraint "unique_customer_email"') !== false) {
            $error_message = "Пользователь с таким email уже существует.";
        } else {
            $error_message = "Ошибка при выполнении запроса: " . $error_message;
        }

        // Отображение ошибки на странице
        echo "<div id='emsg'>$error_message</div>";
    } else {
        // Успешная регистрация
        echo "<div id='emsg'>Регистрация прошла успешно!</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Регистрация</title>
    <link rel="stylesheet" type="text/css" href="Stylesheet.css">
    <style>
        /* CSS Стили */
        * {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, #f5f7fa, #c3cfe2);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: #333;
        }

        #su_pan {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            width: 500px;
            text-align: center;
        }

        h1 {
            font-size: 28px;
            color: black;
            margin-bottom: 20px;
            font-weight: bold;
        }

        label {
            font-size: 16px;
            color: #555;
            display: block;
            text-align: left;
            margin-bottom: 5px;
        }

        #submit {
            width: 100%; /* Ширина 100% */
            padding: 12px; /* Внутренние отступы */
            background-color: #1e90ff; /* Синий цвет фона */
            color: white; /* Белый цвет текста */
            border: none; /* Убираем границу */
            border-radius: 5px; /* Закругленные углы */
            font-size: 18px; /* Размер текста */
            cursor: pointer; /* Курсор в виде руки */
            transition: background-color 0.3s ease; /* Плавный переход цвета */
            margin-top: 20px; /* Отступ сверху */
            text-align: center; /* Выравнивание текста по центру */
            display: block; /* Отображение как блочный элемент */
            text-decoration: none; /* Убираем подчеркивание */
            height: 50px;
        }

        #submit:hover {
            background-color: #0056b3; /* Темно-синий цвет при наведении */
        }

        .return {
            width: 100%;
            padding: 12px;
            background-color: #1e90ff; /* Синий цвет кнопки */
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 18px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            margin-top: 20px;
            text-align: center;
            display: block;
            text-decoration: none;
        }

        .return:hover {
            background-color: #0056b3; /* Темно-синий цвет при наведении */
        }

        input[type="text"],
        input[type="email"],
        input[type="password"],
        input[type="credit_card_num"],
        input[type="first_name"],
        input[type="last_name"],
        select {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            color: #333;
        }

        #textbox {
            background-color: rgb(232, 240, 254); /* Синий цвет */
            color: black;
        }

        #input {
            background-color: rgb(232, 240, 254); /* Синий цвет */
            color: black;
        }

        input[type="submit"] {
            width: 100%;
            padding: 12px;
            background-color: #007BFF; /* Синий цвет кнопки */
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 18px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #0056b3; /* Темно-синий цвет при наведении */
        }

        #emsg {
            color: #d9534f; /* Красный цвет для ошибок */
            font-size: 14px;
            margin-bottom: 15px;
            text-align: left;
        }

        a {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #007BFF; /* Синий цвет ссылки */
            text-decoration: none;
            font-size: 16px;
        }

        a:hover {
            color: #0056b3; /* Темно-синий цвет при наведении */
        }

        /* Добавляем flexbox для выравнивания кнопок */
        .button-container {
            display: flex;
            justify-content: center;
            gap: 20px; /* Расстояние между кнопками */
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div id="su_pan">
        <form action="Register.php" method="post">
            <h1>РЕГИСТРАЦИЯ</h1>

            <table width="100%" border="0">
                <tr>
                    <td height="40" valign="middle" align="left"><label for="first_name"><b>Имя:</b></label></td>
                    <td align="right"><input id="textbox" type="text" name="first_name" /></td>
                </tr>
                <tr>
                    <td height="40" valign="middle" align="left"><label for="last_name"><b>Фамилия:</b></label></td>
                    <td align="right"><input id="textbox" type="text" name="last_name" /></td>
                </tr>
                <tr>
                    <td height="40" valign="middle" align="left"><label for="email"><b>Почта:</b></label></td>
                    <td align="right"><input id="textbox" type="email" name="email" /></td>
                </tr>
                <tr>
                    <td height="40" valign="middle" align="left"><label for="password"><b>Пароль:</b></label></td>
                    <td align="right"><input id="textbox" type="password" name="password" /></td>
                </tr>
                <tr>
                    <td height="40" valign="middle" align="left"><label for="credit_card_num"><b>Номер банковской карты:</b></label></td>
                    <td align="right"><input id="textbox" type="text" name="credit_card_num" maxlength="16" /></td>
                </tr>
                <tr>
                    <td height="40" valign="middle" align="left"><label for="cce_m"><b>Срок действия карты:</b></label></td>
                    <td align="right">
                        <label><b>Месяц:</b></label>
                        <select name="cce_m" id="cce_m"></select>
                        &nbsp;
                        <label><b>Год:</b></label>
                        <select name="cce_y" id="cce_y"></select>
                    </td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
            </table>

            <!-- Контейнер для кнопок -->
            <div class="button-container">
                <input id="submit" type="submit" name="submit" value="ЗАРЕГИСТРИРОВАТЬСЯ">
                <button type="button" class="return" onclick="window.location.href='Main.php'">НАЗАД</button>
            </div>
        </form>
    </div>

    <script>
        // Функция для создания выпадающего списка месяцев
        function createMonthDropdown() {
            const monthSelect = document.getElementById('cce_m');
            monthSelect.innerHTML = ''; // Очищаем текущие опции

            // Добавляем опцию "ВЫБЕРИТЕ"
            const defaultOption = document.createElement('option');
            defaultOption.value = 'X';
            defaultOption.textContent = 'ВЫБЕРИТЕ';
            monthSelect.appendChild(defaultOption);

            // Добавляем месяцы
            for (let month = 1; month <= 12; month++) {
                const option = document.createElement('option');
                option.value = month;
                option.textContent = month;
                monthSelect.appendChild(option);
            }
        }

        // Функция для создания выпадающего списка лет
        function createYearDropdown() {
            const yearSelect = document.getElementById('cce_y');
            yearSelect.innerHTML = ''; // Очищаем текущие опции

            // Добавляем опцию "ВЫБЕРИТЕ"
            const defaultOption = document.createElement('option');
            defaultOption.value = 'X';
            defaultOption.textContent = 'ВЫБЕРИТЕ';
            yearSelect.appendChild(defaultOption);

            // Добавляем годы (например, с 2014 по 2030)
            const currentYear = new Date().getFullYear();
            for (let year = 2014; year <= 2030; year++) {
                const option = document.createElement('option');
                option.value = year;
                option.textContent = year;
                yearSelect.appendChild(option);
            }
        }

        // Вызываем функции для создания выпадающих списков
        createMonthDropdown();
        createYearDropdown();
    </script>
</body>

</html>
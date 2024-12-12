<?php  
#===============================================#
# Файл: Customers.php
# Функция: Отображает список клиентов с их данными учетной записи, полученными из базы данных.
#===============================================#

# Проверка авторизации
if (!isset($_GET['submit'])) {
    header("Location: Main.php");
    exit();
} else {
    if ($_GET['submit'] != 9) {
        header("Location: Main.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Список клиентов</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Arial', sans-serif;
        }

        body {
            background-color: #f8f9fa;
            color: #343a40;
            font-size: 16px;
            padding: 20px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #495057;
        }

        p {
            text-align: center;
            margin-bottom: 20px;
            font-size: 14px;
            color: #6c757d;
        }

        table {
            width: 100%;
            margin: 0 auto;
            border-collapse: collapse;
            background-color: #ffffff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
        }

        th, td {
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #007bff;
            color: #ffffff;
            font-size: 14px;
        }

        tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        tr:nth-child(odd) {
            background-color: #e9ecef;
        }

        tr:hover {
            background-color: #f1f3f5;
        }

        a {
            color: #007bff;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        #back-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #007bff;
            font-size: 14px;
        }

        #back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <h2>Список клиентов</h2>
    <p>Нажмите на имя клиента, чтобы посмотреть историю покупок.</p>
    <table>
        <thead>
            <tr>
                <th>Имя</th>
                <th>Электронная почта</th>
                <th>Пароль</th>
                <th>Номер карты</th>
                <th>Дата окончания карты</th>
            </tr>
        </thead>
        <tbody>
            <?php
            # Получение данных из базы данных
            $host = getenv('DB_HOST');
            $dbname = getenv('DB_NAME');
            $user_db = getenv('DB_USER');
            $password_db = getenv('DB_PASSWORD');
        
            $db = pg_connect("host=$host dbname=$dbname user=$user_db password=$password_db") or die('Не удалось подключиться: ' . pg_last_error());
            $SQL = "SELECT * FROM customers ORDER BY last_name ASC, first_name ASC"; 
            $result = pg_query($db, $SQL);
            if (!$result) {
                die('Ошибка: ' . pg_last_error());
            }
            $num_results = pg_num_rows($result);
            pg_close($db);

            # Вывод данных в таблицу
            if ($num_results == 0) {
                echo "<tr><td colspan='5' style='text-align:center;'><b>Нет доступных клиентов</b></td></tr>";
            } else {
                while ($row = pg_fetch_assoc($result)) {
                    echo "
                    <tr>
                        <td><a href='Orders.php?submit=9&customer_id=" . $row['id'] . "'>" . htmlspecialchars($row['last_name'] . ", " . $row['first_name']) . "</a></td>
                        <td>" . htmlspecialchars($row['email']) . "</td>
                        <td>" . htmlspecialchars($row['password']) . "</td>
                        <td>" . htmlspecialchars($row['credit_card_num']) . "</td>
                        <td>" . htmlspecialchars($row['credit_card_expiry_date']) . "</td>
                    </tr>";
                }
            }
            ?>
        </tbody>
    </table>
    <a id="back-link" href="Admin.php?submit=9">Вернуться назад</a>
</body>
</html>

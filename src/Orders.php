<?php  

#===============================================#
# Файл: Orders.php
# Назначение: Часть админ-панели, отображает все заказы клиентов. Нажав на ID заказа, можно увидеть дополнительные детали (дата, товары и т. д.). Нажав на имя клиента, можно увидеть все его заказы.
#===============================================#

# Проверка на вход в систему админа
if (!isset($_GET['submit']) || $_GET['submit'] != 9) {
    header("Location: Main.php");
    exit();
}

$cid = $_GET['customer_id'] ?? null;

if ($cid !== "all" && (!ctype_digit($cid) || $cid === null)) {
    header("Location: Main.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Заказы</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f4f9;
            color: #333;
            margin: 0;
            padding: 0;
        }

        h2 {
            color: #444;
            text-align: center;
            margin-top: 20px;
        }

        #info {
            text-align: center;
            color: #666;
            margin-bottom: 20px;
        }

        table {
            width: 90%;
            margin: 0 auto;
            border-collapse: collapse;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            background: white;
            border-radius: 8px;
            overflow: hidden;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #3b6978;
            color: white;
            font-size: 16px;
        }

        tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        tr:nth-child(odd) {
            background-color: #e9ecef;
        }

        tr:hover {
            background-color: #d1ecf1;
        }

        a {
            color: #3b6978;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        .back-link {
            display: block;
            text-align: center;
            margin: 20px 0;
            color: #3b6978;
            font-weight: bold;
        }

        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <h2>Список заказов</h2>
    <p id="info">Нажмите на ID заказа, чтобы увидеть подробности, или на имя клиента, чтобы увидеть все его заказы.</p>

    <table>
        <tr>
            <th>ID заказа</th>
            <th>Дата и время</th>
            <th>Имя клиента</th>
            <th>Подытог</th>
            <th>НДС</th>
            <th>Общая сумма</th>
        </tr>
        <?php
        
        $host = getenv('DB_HOST');
        $dbname = getenv('DB_NAME');
        $user_db = getenv('DB_USER');
        $password_db = getenv('DB_PASSWORD');
    
        $db = pg_connect("host=$host dbname=$dbname user=$user_db password=$password_db") 
              or die('Ошибка подключения: ' . pg_last_error());

        if ($cid === "all") {
            $SQL = "SELECT *, orders.id AS order_number 
                    FROM orders 
                    INNER JOIN customers ON orders.customer_id = customers.id
                    ORDER BY orders.id ASC";
            $result = pg_query($db, $SQL);
        } else {
            $SQL = "SELECT *, orders.id AS order_number 
                    FROM orders 
                    INNER JOIN customers ON orders.customer_id = customers.id 
                    WHERE orders.customer_id = $1 
                    ORDER BY orders.id ASC";
            $result = pg_query_params($db, $SQL, [$cid]);
        }

        if (!$result) {
            die('Ошибка запроса: ' . pg_last_error());
        }

        $num_results = pg_num_rows($result);
        pg_close($db);

        if ($num_results == 0) {
            echo "<tr><td colspan='6' align='center'><strong>Заказов нет</strong></td></tr>";
        } else {
            while ($row = pg_fetch_assoc($result)) {
                $tsubtotal = number_format((float)$row['sub_total'], 2, '.', '');
                $thst = number_format((float)$row['hst'], 2, '.', '');
                $ttotal = number_format((float)$row['total_cost'], 2, '.', '');

                echo "
                <tr>
                    <td><a href='OrderDetails.php?submit=9&order_id=" . $row['order_number'] . "'>" . $row['order_number'] . "</a></td>
                    <td>" . $row['date'] . " в " . $row['time'] . "</td>
                    <td><a href='Orders.php?submit=9&customer_id=" . $row['customer_id'] . "'>" . $row['last_name'] . ", " . $row['first_name'] . "</a></td>
                    <td>₽" . $tsubtotal . "</td>
                    <td>₽" . $thst . "</td>
                    <td>₽" . $ttotal . "</td>
                </tr>";
            }
        }
        ?>
    </table>

    <a href="<?= $cid === 'all' ? 'Admin.php?submit=9' : 'Customers.php?submit=9'; ?>" class="back-link">Вернуться назад</a>
</body>
</html>

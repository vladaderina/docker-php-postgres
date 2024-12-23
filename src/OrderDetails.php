<?php  

#===============================================#
# Файл: OrderDetails.php
# Назначение: Отображает информацию о конкретном заказе, включая дату, время, стоимость, налоги, клиента и купленные места.
#===============================================#

# Проверка корректности запроса
if (!isset($_GET['submit']) || $_GET['submit'] != 9 || !isset($_GET['order_id']) || !ctype_digit($_GET['order_id'])) {
    header("Location: Main.php");
    exit();
}

$oid = $_GET['order_id'];

?>

<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Детали заказа #<?php echo htmlspecialchars($oid); ?></title>
    <style>
        /* Стили */
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #f5f7fa, #c3cfe2);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            color: #333;
        }

        #or_pan {
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            width: 600px;
            text-align: center;
        }

        h2 {
            font-size: 28px;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f5f5f5;
            font-weight: bold;
        }

        td {
            font-size: 14px;
        }

        a {
            display: block;
            margin-top: 20px;
            color: #007BFF;
            text-decoration: none;
            font-size: 16px;
        }

        a:hover {
            color: #0056b3;
        }
    </style>
</head>

<body>
    <div id="or_pan">
        <h2>Детали заказа #<?php echo htmlspecialchars($oid); ?></h2>
        <table>
            <?php
            # Подключение к базе данных PostgreSQL
            $host = getenv('DB_HOST');
            $dbname = getenv('DB_NAME');
            $user_db = getenv('DB_USER');
            $password_db = getenv('DB_PASSWORD');
        
            $db = pg_connect("host=$host dbname=$dbname user=$user_db password=$password_db") or die('Не удалось подключиться: ' . pg_last_error());

            # SQL-запрос для получения данных о заказе и местах
            $query = "
                SELECT 
                    orders.id AS order_id,
                    orders.sub_total,
                    orders.hst,
                    orders.total_cost,
                    orders.date,
                    orders.time,
                    customers.last_name,
                    customers.first_name,
                    customers.credit_card_num,
                    customers.credit_card_expiry_date,
                    seats.seat,
                    seats.price
                FROM orders
                LEFT JOIN customers ON customers.id = orders.customer_id
                LEFT JOIN seats ON seats.order_id = orders.id
                WHERE orders.id = $1
                ORDER BY seats.id";
            
            $result = pg_query_params($db, $query, [$oid]) or die('Ошибка запроса: ' . pg_last_error());

            if (pg_num_rows($result) == 0) {
                echo "<tr><td colspan='2'>Заказ не найден.</td></tr>";
            } else {
                $subtotal = $hst = $total = null;
                $customerName = $creditCard = $expiryDate = $date = $time = "";
        
                echo "<tr><th>Место</th><th>Цена</th></tr>";
            
                while ($row = pg_fetch_assoc($result)) {
                    $seat = $row['seat'] ?? '';
                    $price = $row['price'] ?? 0.0;
            
                    echo "<tr><td>" . htmlspecialchars($seat) . "</td><td>₽" . number_format($price, 2) . "</td></tr>";
            
                    # Сохраняем данные заказа
                    $subtotal = $row['sub_total'] ?? 0.0;
                    $hst = $row['hst'] ?? 0.0;
                    $total = $row['total_cost'] ?? 0.0;
                    $creditCard = htmlspecialchars($row['credit_card_num']);
                    $expiryDate = htmlspecialchars($row['credit_card_expiry_date']);
                    $date = htmlspecialchars($row['date']);
                    $time = htmlspecialchars($row['time']);
                    $customerName = $row['first_name'] . " " . $row['last_name'];
                }

                # Отображение общей информации о заказе
                echo "
                    <tr><td><strong>Подытог</strong></td><td>₽" . number_format($subtotal, 2) . "</td></tr>
                    <tr><td><strong>НДС</strong></td><td>₽" . number_format($hst, 2) . "</td></tr>
                    <tr><td><strong>Всего</strong></td><td>₽" . number_format($total, 2) . "</td></tr>
                    <tr><td><strong>Клиент</strong></td><td>$customerName</td></tr>
                    <tr><td><strong>Номер карты</strong></td><td>$creditCard</td></tr>
                    <tr><td><strong>Срок действия</strong></td><td>$expiryDate</td></tr>
                    <tr><td><strong>Дата</strong></td><td>$date</td></tr>
                    <tr><td><strong>Время</strong></td><td>$time</td></tr>";
            }

            pg_free_result($result);
            pg_close($db);
            ?>
        </table>
        <a href="Orders.php?submit=9&customer_id=all">Вернуться к списку заказов</a>
    </div>
</body>

</html>

<?php
header('Content-Type: application/json');

$host = getenv('DB_HOST');
$dbname = getenv('DB_NAME');
$user_db = getenv('DB_USER');
$password_db = getenv('DB_PASSWORD');

// Подключение к базе данных
$db = pg_connect("host=$host dbname=$dbname user=$user_db password=$password_db") 
    or die(json_encode(['success' => false, 'message' => 'Ошибка подключения к базе данных'], JSON_UNESCAPED_UNICODE));

$input = json_decode(file_get_contents('php://input'), true);
$date = date("Y/m/d");
$time = date("H:i:s");
$sub_total = $input['sub_total'];
$hst = $input['hst'];
$total_cost = $input['total_cost'];
$customer_id = $input['customer_id'];
// Вставка данных в таблицу orders
$SQL = "INSERT INTO orders (date, time, sub_total, hst, total_cost, customer_id) 
        VALUES ('$date', '$time', $sub_total, $hst, $total_cost, $customer_id) RETURNING id";
$result = pg_query($db, $SQL);
if (!$result) {
    echo json_encode(['success' => false, 'message' => 'Ошибка при создании заказа: ' . pg_last_error($db)], JSON_UNESCAPED_UNICODE);
    exit();
}

$order_row = pg_fetch_assoc($result);
$order_id = $order_row['id'];

// Обновление таблицы seats
$SQLx = "SELECT seat FROM seats";
$resultx = pg_query($db, $SQLx);
if (!$resultx) {
    echo json_encode(['success' => false, 'message' => 'Ошибка при получении мест: ' . pg_last_error($db)], JSON_UNESCAPED_UNICODE);
    exit();
}

while ($rowx = pg_fetch_assoc($resultx)) {
    if (isset($_POST[$rowx['seat']])) {
        $SQLa = "UPDATE seats SET order_id = $order_id WHERE seat = '" . $rowx['seat'] . "'";
        $resulta = pg_query($db, $SQLa);
        if (!$resulta) {
            echo json_encode(['success' => false, 'message' => 'Ошибка при обновлении мест: ' . pg_last_error($db)], JSON_UNESCAPED_UNICODE);
            exit();
        }
    }
}

pg_close($db);

// Возвращаем успешный результат
echo json_encode([
    'success' => true,
    'message' => 'Заказ успешно создан',
    'order_id' => $order_id,
    'customer_id' => $customer_id
], JSON_UNESCAPED_UNICODE);

?>
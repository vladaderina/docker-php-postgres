<?php
header('Content-Type: application/json');

$host = getenv('DB_HOST');
$dbname = getenv('DB_NAME');
$user_db = getenv('DB_USER');
$password_db = getenv('DB_PASSWORD');

$db = pg_connect("host=$host dbname=$dbname user=$user_db password=$password_db") 
    or die(json_encode(['success' => false, 'message' => 'Ошибка подключения к базе данных'], JSON_UNESCAPED_UNICODE));

$input = json_decode(file_get_contents('php://input'), true);
$orderId = $input['orderId'];
$status = $input['status'];

if (!$orderId || !$status) {
    echo json_encode(['success' => false, 'message' => 'Неверные параметры'], JSON_UNESCAPED_UNICODE);
    exit();
}

$query = pg_query_params($db, "UPDATE orders SET status = $1 WHERE id = $2", [$status, $orderId]);

if ($query) {
    echo json_encode(['success' => true, 'message' => 'Статус успешно обновлен'], JSON_UNESCAPED_UNICODE);
} else {
    echo json_encode(['success' => false, 'message' => 'Ошибка при обновлении статуса: ' . pg_last_error($db)], JSON_UNESCAPED_UNICODE);
}

pg_close($db);
?>
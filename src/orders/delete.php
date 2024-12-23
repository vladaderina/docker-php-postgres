<?php
header('Content-Type: application/json');

$host = getenv('DB_HOST');
$dbname = getenv('DB_NAME');
$user_db = getenv('DB_USER');
$password_db = getenv('DB_PASSWORD');

$db = pg_connect("host=$host dbname=$dbname user=$user_db password=$password_db") 
    or die(json_encode(['success' => false, 'message' => 'Ошибка подключения к базе данных'], JSON_UNESCAPED_UNICODE));

$input = json_decode(file_get_contents('php://input'), true);

if (isset($input['action']) && $input['action'] == 'refund') {
    // Удаление всех заказов
    $SQL = "DELETE FROM orders";
    $result = pg_query($db, $SQL);
    if (!$result) {
        echo json_encode(['success' => false, 'message' => 'Ошибка при удалении заказов: ' . pg_last_error($db)], JSON_UNESCAPED_UNICODE);
        exit();
    }

    // Сброс order_id в таблице seats
    $SQL = "UPDATE seats SET order_id = 0";
    $result = pg_query($db, $SQL);
    if (!$result) {
        echo json_encode(['success' => false, 'message' => 'Ошибка при сбросе order_id: ' . pg_last_error($db)], JSON_UNESCAPED_UNICODE);
        exit();
    }

    echo json_encode(['success' => true, 'message' => 'Все заказы успешно удалены'], JSON_UNESCAPED_UNICODE);
} else {
    echo json_encode(['success' => false, 'message' => 'Неверное действие'], JSON_UNESCAPED_UNICODE);
}

pg_close($db);
?>
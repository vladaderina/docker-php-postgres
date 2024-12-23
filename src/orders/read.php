<?php
    header('Content-Type: application/json');

    // Отладочная информация: Включаем отладку
    $debug = [];
    $debug['script_start'] = 'Скрипт read.php запущен';

    $host = getenv('DB_HOST');
    $dbname = getenv('DB_NAME');
    $user_db = getenv('DB_USER');
    $password_db = getenv('DB_PASSWORD');

    // Отладочная информация: Вывод переменных окружения
    $debug['db_host'] = $host;
    $debug['db_name'] = $dbname;
    $debug['db_user'] = $user_db;

    $db = pg_connect("host=$host dbname=$dbname user=$user_db password=$password_db") 
        or die(json_encode(['success' => false, 'message' => 'Ошибка подключения к базе данных', 'debug' => $debug], JSON_UNESCAPED_UNICODE));

    // Отладочная информация: Успешное подключение к базе данных
    $debug['db_connection'] = 'Успешное подключение к базе данных';

    $cid = $_GET['customer_id'] ?? 'all';

    // Отладочная информация: Вывод параметра customer_id
    $debug['customer_id'] = $cid;

    if ($cid !== "all" && (!ctype_digit($cid) || $cid === null)) {
        // Отладочная информация: Неверный параметр customer_id
        $debug['error'] = 'Неверный параметр customer_id';
        echo json_encode(['success' => false, 'message' => 'Неверный параметр customer_id', 'debug' => $debug]);
        exit();
    }

    if ($cid === "all") {
        $SQL = "SELECT *, orders.id AS order_number 
                FROM orders 
                INNER JOIN customers ON orders.customer_id = customers.id
                ORDER BY orders.id ASC";
        // Отладочная информация: Вывод SQL-запроса
        $debug['sql_query'] = $SQL;
        $result = pg_query($db, $SQL);
    } else {
        $SQL = "SELECT *, orders.id AS order_number 
                FROM orders 
                INNER JOIN customers ON orders.customer_id = customers.id 
                WHERE orders.customer_id = $1 
                ORDER BY orders.id ASC";
        // Отладочная информация: Вывод SQL-запроса с параметром
        $debug['sql_query'] = $SQL . " (customer_id: $cid)";
        $result = pg_query_params($db, $SQL, [$cid]);
    }

    if (!$result) {
        // Отладочная информация: Ошибка выполнения запроса
        $debug['error'] = 'Ошибка выполнения запроса: ' . pg_last_error($db);
        echo json_encode(['success' => false, 'message' => 'Ошибка выполнения запроса', 'debug' => $debug]);
        exit();
    }

    // Отладочная информация: Успешное выполнение запроса
    $debug['query_success'] = 'Запрос успешно выполнен';

    $orders = [];
    while ($row = pg_fetch_assoc($result)) {
        $orders[] = [
            'order_number' => $row['order_number'],
            'date' => $row['date'],
            'time' => $row['time'],
            'customer_name' => $row['last_name'] . ', ' . $row['first_name'],
            'sub_total' => number_format((float)$row['sub_total'], 2, '.', ''),
            'hst' => number_format((float)$row['hst'], 2, '.', ''),
            'total_cost' => number_format((float)$row['total_cost'], 2, '.', ''),
            'status' => $row['status']
        ];
    }

    // Отладочная информация: Количество заказов
    $debug['orders_count'] = count($orders);

    pg_close($db);

    // Отладочная информация: Завершение скрипта
    $debug['script_end'] = 'Скрипт read.php завершен';

    echo json_encode(['success' => true, 'data' => $orders, 'debug' => $debug]);
?>
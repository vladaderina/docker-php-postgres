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

        /* Стиль для активной строки */
        .active-row {
            background-color: #b3d7ff !important; /* Цвет активной строки */
            font-weight: bold;
        }
    </style>
</head>
<body>
    <h2>Список заказов</h2>
    <p id="info">Нажмите на ID заказа, чтобы увидеть подробности, или на имя клиента, чтобы увидеть все его заказы.</p>

    <table id="orders-table">
        <tr>
            <th>ID заказа</th>
            <th>Дата и время</th>
            <th>Имя клиента</th>
            <th>Подытог</th>
            <th>НДС</th>
            <th>Общая сумма</th>
            <th>Статус</th>
        </tr>
    </table>

    <a href="<?= $cid === 'all' ? 'Admin.php?submit=9' : 'Customers.php?submit=9'; ?>" class="back-link">Вернуться назад</a>

    <script>
        document.addEventListener('DOMContentLoaded', async () => {
            const cid = '<?= $_GET['customer_id'] ?? 'all'; ?>';
            const response = await fetch(`orders/read.php?customer_id=all`);
            const data = await response.json();

            if (data.success) {
                const tableBody = document.querySelector('#orders-table tbody');

                data.data.forEach(order => {
                    const row = `
                        <tr data-order-id="${order.order_number}">
                            <td><a href='OrderDetails.php?submit=9&order_id=${order.order_number}'>${order.order_number}</a></td>
                            <td>${order.date} в ${order.time}</td>
                            <td><a href='Orders.php?submit=9&customer_id=${order.customer_id}'>${order.customer_name}</a></td>
                            <td>₽${order.sub_total}</td>
                            <td>₽${order.hst}</td>
                            <td>₽${order.total_cost}</td>
                            <td>
                                <select class="status-select" data-order-id="${order.order_number}">
                                    <option value="Pending" ${order.status === 'Pending' ? 'selected' : ''}>В ожидании</option>
                                    <option value="Processing" ${order.status === 'Processing' ? 'selected' : ''}>Обработан</option>
                                    <option value="Shipped" ${order.status === 'Shipped' ? 'selected' : ''}>Продан</option>
                                    <option value="Delivered" ${order.status === 'Delivered' ? 'selected' : ''}>Доставлен</option>
                                </select>
                            </td>
                        </tr>
                    `;
                    tableBody.innerHTML += row;
                });

                // Обработчик изменения статуса
                document.querySelectorAll('.status-select').forEach(select => {
                    select.addEventListener('change', async (e) => {
                        const orderId = e.target.dataset.orderId;
                        const status = e.target.value;

                        const response = await fetch('orders/update.php', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify({ orderId, status })
                        });

                        const result = await response.json();
                        if (result.success) {
                            alert('Статус успешно обновлен!');
                        } else {
                            alert('Ошибка при обновлении статуса: ' + result.message);
                        }
                    });
                });

                // Обработчик выбора активной строки
                document.querySelectorAll('#orders-table tr[data-order-id]').forEach(row => {
                    row.addEventListener('click', () => {
                        // Удаляем класс active-row у всех строк
                        document.querySelectorAll('#orders-table tr[data-order-id]').forEach(r => r.classList.remove('active-row'));

                        // Добавляем класс active-row к выбранной строке
                        row.classList.add('active-row');
                    });
                });
            } else {
                alert('Ошибка при загрузке данных: ' + data.message);
            }
        });
    </script>
</body>
</html>
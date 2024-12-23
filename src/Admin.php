<?php
// Предположим, что роль пользователя хранится в сессии
session_start();

// Роль, необходимая для доступа к Admin.php?submit=9
$required_role = 'admin';
// Проверяем, есть ли у пользователя необходимая роль
if (!in_array($required_role, $_SESSION['user_roles'])) {
    // Если роли недостаточно, выводим сообщение и список ролей
    echo "У вас недостаточно привилегий для просмотра данной страницы.<br>";
    echo "Ваши роли: " . implode(", ", $_SESSION['user_roles']);
    exit(); // Останавливаем выполнение скрипта, чтобы не отображать панель администратора
}

// Если роль есть, продолжаем выполнение скрипта
# Comment: Verifies if the login info matches with the admin account in the database
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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Панель администратора</title>
    <link rel="stylesheet" type="text/css" href="Stylesheet.css">
    <style>
        /* CSS Styling */
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

        #adminshop {
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            width: 400px;
            text-align: center;
        }

        h2 {
            font-size: 28px;
            color: #333;
            margin-bottom: 20px;
            font-weight: bold;
        }

        table {
            width: 100%;
        }

        input[type="submit"] {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 18px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        input[type="submit"]:last-child {
            background-color: #007BFF;
        }

        input[type="submit"]:last-child:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div id="adminshop">
        <div id="admin_pan">
            <h2>Панель администратора</h2>
            <table width="100%" border="0" align="center">
                <tr>
                    <td align="center" height="45px">
                        <form action="Shop.php?submit=9&id=0" method="post">
                            <input type="submit" name="submit" value="Схема рассадки">
                        </form>
                    </td>
                </tr>
                <tr>
                    <td align="center" height="45px">
                        <form action="Customers.php?submit=9&id=0" method="post">
                            <input type="submit" name="submit" value="Пользователи">
                        </form>
                    </td>
                </tr>
                <tr>
                    <td align="center" height="45px">
                        <form action="Orders.php?submit=9&customer_id=all" method="post">
                            <input type="submit" name="submit" value="Заказы">
                        </form>
                    </td>
                </tr>
                <tr>
                    <td align="center" height="45px">
                        <form id="refundForm">
                            <input type="submit" name="submit" value="Возврат билетов">
                        </form>
                    </td>
                </tr>
                <tr>
                    <td align="center" height="45px">
                        <form action="Main.php" method="post">
                            <input type="submit" name="submit" value="Выход">
                        </form>
                    </td>
                </tr>
            </table>
        </div>
    </div>

    <script>
        // JavaScript для обработки возврата билетов
        document.getElementById('refundForm').addEventListener('submit', async (e) => {
            e.preventDefault(); // Предотвращаем стандартное поведение формы

            if (confirm('Вы уверены, что хотите удалить все заказы?')) {
                try {
                    const response = await fetch('orders/delete.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ action: 'refund' })
                    });

                    const result = await response.json();
                    if (result.success) {
                        alert('Все заказы успешно удалены!');
                    } else {
                        alert('Ошибка при удалении заказов: ' + result.message);
                    }
                } catch (error) {
                    console.error('Ошибка:', error);
                    alert('Произошла ошибка при выполнении запроса.');
                }
            }
        });
    </script>
</body>
</html>
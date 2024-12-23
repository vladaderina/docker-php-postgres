<?php
if (isset($_POST['process'])) {
    // Вызов create.php через AJAX
    ?>
    <!DOCTYPE html>
    <html lang="ru">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Заказ обработан</title>
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

            #confirm {
                background: #fff;
                padding: 30px;
                border-radius: 10px;
                box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
                text-align: center;
            }

            h2 {
                font-size: 28px;
                color: #333;
                margin-bottom: 20px;
                font-weight: bold;
            }

            p {
                font-size: 16px;
                color: #555;
                margin-bottom: 20px;
            }

            input[type='submit'] {
                width: 100%;
                padding: 12px;
                background-color: #007BFF;
                color: white;
                border: none;
                border-radius: 5px;
                font-size: 18px;
                cursor: pointer;
                transition: background-color 0.3s ease;
            }

            input[type='submit']:hover {
                background-color: #0056b3;
            }

            table {
                width: 100%;
                margin: 20px 0;
            }

            td {
                padding: 10px;
            }

            a {
                display: inline-block;
                padding: 12px 20px;
                background-color: #007BFF;
                color: white;
                border: none;
                border-radius: 5px;
                font-size: 16px;
                cursor: pointer;
                transition: background-color 0.3s ease;
                text-decoration: none;
                margin: 10px;
            }

            a:hover {
                background-color: #0056b3;
            }
        </style>
    </head>
    <body>
        <div id="confirm">
            <div id="confirm_panel">
                <h2>Спасибо за ваш заказ</h2>
                <p align="center">Мы будем рады видеть вас на концерте!</p>
                <div align="center">
                    <a href="Shop.php?submit=1&id=<?= $_POST['customer_id'] ?>">КУПИТЬ ЕЩЁ</a>
                    <a href="Main.php">ВЫЙТИ</a>
                </div>
            </div>
        </div>

        <script>
            // Отправка данных на сервер через AJAX
            document.addEventListener('DOMContentLoaded', async () => {
                const response = await fetch('orders/create.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        process: true,
                        sub_total: <?= $_POST['sub_total'] ?>,
                        hst: <?= $_POST['hst'] ?>,
                        total_cost: <?= $_POST['total_cost'] ?>,
                        customer_id: <?= $_POST['customer_id'] ?>,
                        <?php
                        foreach ($_POST as $key => $value) {
                            if (strpos($key, 'seat') === 0) {
                                echo "$key: true,\n";
                            }
                        }
                        ?>
                    })
                });

                const result = await response.json();
                if (result.success) {
                    console.log('Заказ успешно создан:', result);
                } else {
                    alert('Ошибка при создании заказа: ' + result.message);
                }
            });
        </script>
    </body>
    </html>
    <?php
} else {
    header("Location: Main.php");
    exit();
}
?>
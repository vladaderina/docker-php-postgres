<!DOCTYPE html>
<html lang="en">

<head>
    <!-- 
    ===============================================

    File: Main.php
    Function: Login page, sends the login details to the signin.php file to be processed.

    ===============================================
    -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log In</title>
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

        #signin {
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            width: 400px;
            text-align: center;
        }

        h1 {
            font-size: 28px;
            color: #333;
            margin-bottom: 20px;
            font-weight: bold;
        }

        label {
            font-size: 16px;
            color: #555;
            display: block;
            text-align: left;
            margin-bottom: 5px;
        }

        input[type="email"],
        input[type="password"],
        select {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            color: #333;
        }

        input[type="submit"] {
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

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        #msg,
        #msg_a {
            color: #d9534f;
            font-size: 14px;
            margin-bottom: 15px;
        }

        /* Стили для кнопки "Создать новый аккаунт" */
        .create-account-btn {
            width: 100%;
            padding: 12px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 18px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            margin-top: 20px;
            text-align: center;
            display: block;
            text-decoration: none;
        }

        .create-account-btn:hover {
            background-color: #0056b3;
            color: white; /* Убедитесь, что цвет текста остаётся белым */
        }
    </style>
</head>

<body>
    <div id="signin">
        <div id="main_pan">
            <form action="Signin.php" method="post">
                <h1>ВХОД</h1>

                <?php
                # Login validation messages
                if (isset($_GET['created'])) {
                    echo "<p id='msg_a'>Войдите в свой новый аккаунт</p>";
                }

                if (isset($_GET['blank'])) {
                    echo "<p id='msg'>Заполните все поля</p>";
                }

                if (isset($_GET['error'])) {
                    echo "<p id='msg'>Неправильный логин или пароль</p>";
                }
                ?>

                <!-- Login form -->
                <label for="email"><b>Почта:</b></label>
                <input type="email" name="email" required />

                <label for="password"><b>Пароль:</b></label>
                <input type="password" name="password" required />

                <label for="user"><b>Роль:</b></label>
                <select name="user">
                    <option value="customers">Пользователь</option>
                    <option value="admin">Администратор</option>
                </select>

                <input type="submit" name="submit" value="Войти">

                <!-- Кнопка "Создать новый аккаунт" -->
                <a href="SignUp.php" class="create-account-btn">Создать новый аккаунт</a>
            </form>
        </div>
    </div>
</body>

</html>
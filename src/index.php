<?php
#===============================================#

# File: Index.php
# Function: Redirects you to the main page

#===============================================#

// Проверяем, нужно ли выполнить тестирование Xdebug
if (isset($_GET['test_xdebug']) && $_GET['test_xdebug'] === '1') {
    echo "Testing Xdebug...\n";

    // Проверка, включен ли Xdebug
    if (extension_loaded('xdebug')) {
        echo "Xdebug is installed and enabled!\n";
    } else {
        echo "Xdebug is not installed or enabled.\n";
    }

    // Вывод информации о Xdebug
    xdebug_info();

    // Прерываем выполнение, чтобы не перенаправлять на Main.php
    exit();
}

// Если тестирование Xdebug не запрашивалось, перенаправляем на Main.php
header("Location: Main.php");
exit();
?>
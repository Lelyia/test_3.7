<?php
/*
Практическая часть
1. Задание 1: Форма для отправки отзыва о товаре
Создайте форму с полями: имя, отзыв (textarea), рейтинг (1-5).
Отправляйте данные методом POST .
Реализуйте обработку данных, проверяя метод запроса ( POST ).
Выводите результат с введенными пользователем данными.
Подсказка:
Используйте массив $_POST для получения данных:
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
$name = $_POST['name'];*/
// Дальнейшая обработка данных


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = htmlspecialchars(trim($_POST['username']));
    $rating = intval($_POST['rating']);
    $review = htmlspecialchars(trim($_POST['review']));
 if (empty($username)) {
    echo "Пожалуйста, введите ваше имя.";
} elseif ($rating < 1 || $rating > 5) {
    echo "Оценка должна быть числом от 1 до 5.";
} elseif (empty($review)) {
    echo "Пожалуйста, оставьте отзыв.";
} else {
    try {
        $pdo = new PDO('mysql:host=localhost;dbname=mydb', 'root', '');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt = $pdo->prepare("INSERT INTO reviews (username, rating, review) VALUES (:username, :rating, :review)");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':rating', $rating);
        $stmt->bindParam(':review', $review);

        if ($stmt->execute()) {
            echo "Спасибо за ваш отзыв!";
        } else {
            echo "Произошла ошибка при отправке отзыва.";
        }
    } catch (PDOException $e) {
        echo "Ошибка подключения к базе данных: " . $e->getMessage();
    }
}
} else {
echo "Неправильный метод запроса.";
}
?>
<?php
/*2. Задание 2: Форма для отмены заказа
Создайте форму с полями: номер заказа, причина отмены (textarea).
Отправка данных методом POST .
Обрабатывайте данные, выводя номер заказа и причину отмены.
Подсказка:
Используйте htmlspecialchars() для защиты данных от XSS-атак:
$order_id = htmlspecialchars($_POST['order_id']);*/


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $order_id = htmlspecialchars(trim($_POST['order_id']));
    $reason = isset($_POST['reason']) ? htmlspecialchars(trim($_POST['reason'])) : '';

    if (empty($order_id)) {
        echo "Номер заказа обязателен.";
    } else {
        try {
            $pdo = new PDO('mysql:host=localhost;dbname=mydb', 'root', '');
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $pdo->prepare("SELECT status FROM orders WHERE order_id = :order_id");
            $stmt->bindParam(':order_id', $order_id);
            $stmt->execute();

            $order = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($order) {
                if ($order['status'] !== 'cancelled') {
                    $update_stmt = $pdo->prepare("UPDATE orders SET status = 'cancelled', cancel_reason = :reason WHERE order_id = :order_id");
                    $update_stmt->bindParam(':reason', $reason);
                    $update_stmt->bindParam(':order_id', $order_id);

                    if ($update_stmt->execute()) {
                        echo "Заказ успешно отменен.";
                    } else {
                        echo "Ошибка при отмене заказа.";
                    }
                } else {
                    echo "Заказ уже был отменен ранее.";
                }
            } else {
                echo "Заказ с указанным номером не найден.";
            }
        } catch (PDOException $e) {
            echo "Ошибка подключения к базе данных: " . $e->getMessage();
        }
    }
} else {
    echo "Неверный метод запроса.";
}
?>


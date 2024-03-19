<?php require 'db_connect.php';
include 'header.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
                body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            text-align: center;
        }
        h1, h2, h3 {
            margin-top: 5px;
            text-align: center;
        }
        .result {
            margin-top: 10px;
            padding: 10px;
            background-color: #fff;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
    </style>
</head>
<h2>Запрос №1</h2>
<h3>Заработок за определенный период</h3>
<?php

$start_date = '2022-01-01';
$end_date = '2024-12-31';

// Выполняем запрос к базе данных
$query = "SELECT SUM(records.count * category.price) AS total_earnings
          FROM records
          INNER JOIN category ON records.id_category = category.id
          WHERE records.date_create BETWEEN ? AND ?";
$stmt = $mysqli->prepare($query);
$stmt->bind_param('ss', $start_date, $end_date);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $total_earnings = $row['total_earnings'];
    echo "Общий заработок за период с $start_date по $end_date: $total_earnings";
} else {
    echo "Нет данных о заработке за указанный период";
}

$stmt->close();
$mysqli->close();
?>    



<h2>Запрос №2</h2>
<h3>Pассчитать сколько заказов выполнила каждая швея</h3>

<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "studio";

// Устанавливаем соединение с базой данных
$conn = new mysqli($servername, $username, $password, $dbname);

// Проверяем соединение
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL запрос для подсчета количества заказов, выполненных каждым работником
$sql = "SELECT s.id AS id_seamstress, s.fio AS seamstress_name, COUNT(r.id) AS records_count
        FROM seamstresses s
        LEFT JOIN records r ON s.id = r.id_seamstress
        GROUP BY s.id";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Выводим результат запроса
    while($row = $result->fetch_assoc()) {
        echo $row["seamstress_name"]. " - Количество заказов: " . $row["records_count"]. "<br>";
    }
} else {
    echo "Нет данных о выполненных заказах";
}

$conn->close();
?>

</body>

</html>


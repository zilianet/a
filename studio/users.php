<?php 
require 'db_connect.php';
require 'header.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        /* Стили для таблицы пользователей */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            color: #333;
            text-align: center; /* Центрирование содержимого по центру */
        }
        table {
            margin: 20px auto; /* Центрирование таблицы */
            border-collapse: collapse;
            width: 300px;
            user-select: none; /* Отключение выделения текста */
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); /* Добавление тени */
        }
        th, td {
            padding: 10px;
            border: 1px solid #ccc;
            text-decoration: none;
        }
        .modal {
            display: none; 
        }
        

.modal-content {
    background-color: #fefefe;
    margin: 15% auto;
    padding: 20px;
    border: 1px solid #888;
    width: 60%;
    height: 400px;
    max-width: 500px;
    position: relative;
}

.close {
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
    cursor: pointer;
}

.close:hover,
.close:focus {
    color: black;
    text-decoration: none;
    cursor: pointer;
}

    </style>
</head>
  <body>
    <h1>Пользователи</h1>
  <?php

  // Определение параметров сортировки
  $sort = isset($_GET['sort']) ? $_GET['sort'] : 'id';
  $sortDir = (isset($_GET['dir']) && $_GET['dir'] === 'desc') ? 'desc' : 'asc';

  // Получаем данные о пользователях из базы данных с учетом сортировки
  $query = "SELECT * FROM users ORDER BY fio $sortDir";
  $result = $mysqli->query($query);

  // HTML код для отображения пользователей в таблице
  echo "<table border='1'>
          <tr>
              <th><a href='?sort=name&".($sortDir === 'asc' ? 'dir=desc' : 'dir=asc')."'>Имя</a></th>
          </tr>";

  while ($row = $result->fetch_assoc()) {
      echo "<tr>
              <td><a href='#' class='user-link' data-user-id='".$row['id']."'>".$row['fio']."</a></td>
            </tr>";
  }

  echo "</table>";

  $mysqli->close();
  ?>


  <!-- HTML код модального окна -->
  <div id="userModal" class="modal">
    <div class="modal-content">
      <span class="close">&times;</span>
      <div id="userInfo"></div>
    </div>
  </div>

  <!-- JavaScript код для отображения информации о пользователе в модальном окне -->
  <script>
  document.querySelectorAll('.user-link').forEach(link => {
    link.addEventListener('click', (event) => {
      event.preventDefault();
      let userId = link.dataset.userId;
      fetch('get_user_info.php?user_id=' + userId)
        .then(response => response.text())
        .then(data => {
          document.getElementById('userInfo').innerHTML = data;
          document.getElementById('userModal').style.display = 'block';
        });
    });
  });

  document.querySelector('.close').addEventListener('click', () => {
    document.getElementById('userModal').style.display = 'none';
  });
  </script>
      
  </body>
</html>

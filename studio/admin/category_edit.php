<?php 
require 'db_connect.php'; 

$title = $id ? 'Правка категории заявок' : 'Добавление категорий заявок'; 

$myrow = ['name'=>'', 'price'=>'']; 
$error_name = ''; 
$error_price = ''; 

if ($id) { 
  $sth = $mysqli->prepare("SELECT * FROM category WHERE id=?"); 
  $sth->bind_param('i', $id); 
  $sth->execute(); 
  $result = $sth->get_result(); 
  if ($result->num_rows == 0) {
    echo 'Категория не найдена';
    return; 
  }
  $myrow = $result->fetch_assoc(); 
} 

if ($method == 'DELETE') { 
  $sth = $mysqli->prepare("DELETE FROM category WHERE id=?"); 
  $sth->bind_param('i', $id); 
  $sth->execute(); 
  header('Content-type: application/json'); 
  echo json_encode(array('success' => 1), JSON_FORCE_OBJECT); 
  return; 
} 

if ($method == 'POST') { 
  $name = addslashes($_POST['name']); 
  $price = intval($_POST['price']); 
  
  if ($id) { 
    if ($name == $myrow['name']) {
      $error_name = 'Измените название'; 
    }
    if ($price == $myrow['price']) {
      $error_price = 'Измените цену'; 
    } else { 
      $sth = $mysqli->prepare("UPDATE category SET name=?, price=? WHERE id=?"); 
      $sth->bind_param('sii', $name, $price, $id); 
      if ($sth->execute()) {
        header("location:category.php"); 
      }
    } 
  } else { 
    if (!$name) {
      $error_name = 'Введите название'; 
    }
    if (!$price) {
      $error_price = 'Введите цену'; 
    } else { 
      $sth = $mysqli->prepare("SELECT * FROM category WHERE name=?"); 
      $sth->bind_param('s', $name); 
      $sth->execute(); 
      $result = $sth->get_result(); 
      if ($result->num_rows == 0) { 
        $sth = $mysqli->prepare("INSERT INTO category (name, price) VALUES (?, ?)"); 
        $sth->bind_param('si', $name, $price); 
        if ($sth->execute()) {
          header("location:category.php"); 
        }
      } else {
        $error_name = 'Такое название уже существует'; 
      }
    } 
  } 
} 

require 'header.php'; 

?> 

<head>
  <style>
        form {
        display: flex;
        flex-direction: column;
        align-items: center;
        margin-top: 1rem; /* Adjust margin-top as needed for spacing */
    }

    label {
        margin-bottom: 0.5rem; /* Adds spacing below each label */
    }

    input {
        width: 100%;
        padding: 0.5rem; /* Adjust as needed for input padding */
        margin-bottom: 1rem; /* Adds spacing below each input */
    }

    button {
        margin-top: 1rem; /* Adds spacing above the button */
    }
  </style>
</head>

<form class="mt-4 text-center" method="post"> 
  <div class="small text-danger text-center"><?php echo $error_name ?></div> 
  <div> 
    <label>Название категории:</label> 
    <input type="text" name="name" value="<?php echo $myrow['name'] ?>"> 
    <label>Цена:</label> 
    <input type="text" name="price" value="<?php echo $myrow['price'] ?>"> 
  </div> 
  <button class="mt-2 btn btn-primary" data-id="<?php echo $id ?>">Сохранить</button> 
</form> 

<?php require '../footer.php'; ?>
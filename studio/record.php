<?php
require 'db_connect.php';

$title = 'Создание заявки'; 
$method = $_SERVER['REQUEST_METHOD'];
$myrow = ['phone'=>'', 'id_category'=>'', 'id_seamstress'=>'', 'count'=>'', 'date_create'=>'','id_user'=>'']; 
$users = array(); 

foreach ($mysqli->query('SELECT * FROM users') as $row) { 
    $users[$row['id']] = $row['fio']; 
} 
$error_phone = '';
$error_datecreate = ''; 
$error_count = '';

if ($method == 'POST') {
    $phone = isset($_POST['phone']) ? addslashes($_POST['phone']) : '';
    $id_category = isset($_POST['id_category']) ? intval($_POST['id_category']) : 0;
    $id_seamstress = isset($_POST['id_seamstress']) ? intval($_POST['id_seamstress']) : 0;
    $count = isset($_POST['count']) ? intval($_POST['count']) : 0;
    $date_create = isset($_POST['date_create']) ? addslashes($_POST['date_create']) : '';
    $file = $_FILES['myfile'];

    $no_errors = true;
    if (empty($phone)) {
        $no_errors = false;
        $error_phone = 'Введите номер телефона';
    }
    if (empty($count)) {
        $no_errors = false;
        $error_count = 'Введите количество';
    }
    if (empty($date_create)) {
        $no_errors = false;
        $error_datecreate = 'Введите временную метку';
    }
    if ($no_errors) {
        $query = "INSERT INTO records (phone, id_category, id_seamstress, count, date_create, id_user) VALUES (?, ?, ?, ?, ?, ?)"; 
        $sth = $mysqli->prepare($query);

        if ($sth === false) {
            die('Ошибка в prepare: ' . $mysqli->error);
        }
        $sth->bind_param('siissi', $phone, $id_category, $id_seamstress, $count, $date_create, $_SESSION['User']);

        if ($sth->execute()) {
          upload_photo_to_server($file, $mysqli->insert_id);
          header("location:records.php");
        } else {
            echo "Ошибка выполнения запроса: " . $sth->error;
        }
    }
}

function upload_photo_to_server($file, $id){
  if ($file['name']){
    $ext = explode('.', $file['name']);
    $ext = strtolower($ext[count($ext) - 1]);
    if ($ext == 'jpg'){
      $new_file = FILEUSER_PATH.$id.'_0.jpg';
      if (file_exists($new_file)) unlink($new_file);
      move_uploaded_file($file['tmp_name'], $new_file);
      chmod($new_file, 0777);
    } else $error_file = 'Расширение фото должно быть jpg';
  }
}

require 'header.php';
?>

<style>
  select[name=id_category] { width: 216px;}
</style>
<form class="mt-4 d-flex justify-content-center" method="post" enctype="multipart/form-data">
  <div class="ms-4 d-flex flex-column align-items-center"><div>

  <div class="small text-danger text-center"><?php echo $error_phone ?></div>
  <div class="d-flex justify-content-between mb-2">
      <label>Номер телефона:</label>
      <input type="text" name="phone" value="<?php echo $myrow['phone'] ?>">
  </div> 

  <div class="d-flex justify-content-between">
      <label>Количество:</label>
      <input type="number" name="count" value="<?php echo $myrow['count'] ?>">
  </div>

  <div class="my-2 d-flex justify-content-between">
      <label>Категория заявки:</label>
      <select name="id_category" style="width: 190px;">
          <?php foreach ($mysqli->query('SELECT * FROM category') as $catrow) { ?>
              <option <?php if ($catrow['id'] == $myrow['id_category']) echo 'selected'; ?> value="<?php echo $catrow['id'] ?>"><?php echo $catrow['name'] ?></option>
          <?php } ?>
      </select>
  </div>

  <div class="small text-danger text-center"><?php echo $error_datecreate ?></div>

  <div class="mb-2 d-flex justify-content-between">
      <label>Временная метка:</label>
      <input type="datetime-local" id="dateInput" name="date_create">
  </div>

    <div class="mb-2 d-flex justify-content-between">
      <label class="me-3">Пример:</label> <input type="file" name="myfile">
    </div>

    <div class="d-flex justify-content-end">
      <button class="btn btn-primary ms-4">Отправить</button>
    </div>
  </div></div>
</form>
<script>

// Получаем текущую дату и время
let currentDate = new Date();
// Добавляем 3 часа к текущему времени
currentDate.setHours(currentDate.getHours() + 3);
// Форматируем дату и время в формат, понятный для поля ввода
let dateString = currentDate.toISOString().slice(0, 16);
// Устанавливаем полученное значение, увеличенное на 3 часа, как значение по умолчанию для поля ввода
document.getElementById('dateInput').value = dateString;

</script>

</script>
<?php require 'footer.php'; ?>

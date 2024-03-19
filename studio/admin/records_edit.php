<?php 
require 'db_connect.php';

$title = $id ? 'Правка заявок' : 'Добавление заявок';
$myrow = ['id_category'=>1, 'id_seamstress'=>1, 'count'=>1, 'date_create'=>'', 'id_users'=>'', 'phone'=>'']; 

$users = array(); 
foreach ($mysqli->query('SELECT * FROM users') as $row){
    $users[$row['id']] = $row['fio']; 
} 

$error_id_category = '';
$error_id_seamstress = '';
$error_count = '';
$error_date_create = '';
$error_id_user = '';
$error_phone = '';
$error_file = '';
$error_file1 = '';

if (!empty($id))  {
  $sth = $mysqli->prepare("SELECT * FROM records WHERE id=?");
  $sth->bind_param('i', $id);
  $sth->execute();  
  $result = $sth->get_result();
  if ($result->num_rows == 0) return danger('заявка не найдена');
  $myrow = $result->fetch_assoc();
}
if ($method == 'DELETE') {
  $sth = $mysqli->prepare("DELETE FROM records WHERE id=?");
  $sth->bind_param('i', $id);
  $sth->execute();
  header('Content-type: application/json');
  echo json_encode(array('success' => 1), JSON_FORCE_OBJECT);
  return;
}
if ($method == 'POST') {
  $id_category        = intval($_POST['id_category']);
  $id_seamstress = intval($_POST['id_seamstress']);
  $count = intval($_POST['count']);
  $date_create = addslashes($_POST['date_create']);
  $id_user       = intval($_POST['id_user']);
  $phone       = addslashes($_POST['phone']);
  $file        = $_FILES['myfile'];
  $file1        = $_FILES['myfile1'];
  if (!empty($id))  {
    upload_photo_to_server($file, $id);
    upload_photo_to_server1($file1, $id);
    if ($id_category == $myrow['id_category'] && $id_seamstress == $myrow['id_seamstress'] && $count == $myrow['count']
        && $date_create == $myrow['date_create'] && $id_user == $myrow['id_user'] && $phone == $myrow['phone']){
      if (!$file['name']) $error_name = 'Внесите изменения';
    } else {
      $sth = $mysqli->prepare("UPDATE records SET id_category=?, id_seamstress=?, count=?, date_create=?, phone=? WHERE id=?");
      $sth->bind_param('iiissi', $id_category, $id_seamstress, $count, $date_create, $phone, $id);
      if ($sth->execute()) {
          header("location:records.php");
      } else {
      }
      
    }
  } else {
    $no_errors = true;
    if (!$id_category    ){ $no_errors = false; $error_id_category     = 'Выберите категорию'; }
    if (!$id_seamstress    ){ $no_errors = false; $error_id_seamstress     = 'Выберите швею'; }
    if (!$count ){ $no_errors = false; $error_count = 'Введите количество'; } 
    if (!$date_create   ){ $no_errors = false; $error_date_create    = 'Введите дату подачи'; } 
    if (!$id_user){ $no_errors = false; $error_id_user = 'Введите пользователя'; }
    if (!$phone){ $no_errors = false; $error_phone = 'Введите номер телефона'; }
    if ($no_errors){
      $query = "INSERT INTO records (id_category, id_seamstress, count, date_create, id_user, phone) VALUES (?,?,?,?,?,?)"; 
      $sth = $mysqli->prepare($query);
      $sth->bind_param('iiisis', $id_category, $id_seamstress, $count, $date_create, $id_user, $phone); 
      
      if ($sth->execute()){ 
        upload_photo_to_server($file, $mysqli->insert_id);
        upload_photo_to_server1($file1, $mysqli->insert_id);
        header("location:records.php");
      }
    }
  }
}

function upload_photo_to_server($file, $id){
  if ($file['name']){
    $ext = explode('.', $file['name']);
    $ext = strtolower($ext[count($ext) - 1]);
    if ($ext == 'jpg'){
      move_uploaded_file($file['tmp_name'], FILE_PATH.$id.'_0.jpg');
      chmod(FILE_PATH.$id.'_0.jpg', 0777);
    } else {
      global $error_file;
      $error_file = 'Расширение фото должно быть jpg';
    }
  }
}
function upload_photo_to_server1($file1, $id){
  if ($file1['name']){
    $ext = explode('.', $file1['name']);
    $ext = strtolower($ext[count($ext) - 1]);
    if ($ext == 'jpg'){
      move_uploaded_file($file1['tmp_name'], FILE_PATH.$id.'_1.jpg');
      chmod(FILE_PATH.$id.'_1.jpg', 0777);
    } else {
      global $error_file1;
      $error_file1 = 'Расширение фото должно быть jpg';
    }
  }
}

$myfile = FILE_PATH.$id.'_0.jpg';
$myfile1 = FILE_PATH.$id.'_1.jpg';
require 'header.php';
?>
<style>
  select[name=id_category] { width: 216px;}
  form {
    display: flex;
    flex-direction: column;
    align-items: center;
  }

  .form-group {
    margin-bottom: 10px;
  }

  label {
    width: 150px;
    text-align: right;
    margin-right: 10px;
  }

  select,
  input[type="text"],
  input[type="datetime-local"] {
    width: 200px;
    padding: 5px;
    border-radius: 5px;
    border: 1px solid #ccc;
  }

  .small {
    color: red;
    text-align: center;
    margin-top: 5px;
  }

  .buttons {
    display: flex;
    justify-content: flex-end;
    margin-top: 10px;
  }

  button {
    padding: 5px 10px;
    margin-left: 10px;
    border-radius: 5px;
    cursor: pointer;
  }

  .btn-danger {
    background-color: #dc3545;
    color: white;
  }

  .btn-primary {
    background-color: #007bff;
    color: white;
  }
  .w-25>img{
    object-fit: cover;  
    width: 400px;
    height: 200px;
  }
  h1{
    font-size: 24px;
  }
  .storona{
  display: flex;
  align-items: center;
  gap: 60px;
  }
  
</style>
<form class="mt-4 d-flex justify-content-center" method="post" enctype="multipart/form-data">
 <div class="storona">
    <div class="w-25">
      <?php if (file_exists($myfile)){ ?>
        <img class='rounded img-thumbnail' src='<?php echo $myfile ?>?t=<?php echo rand() ?>'/>
        <?php } ?>
    </div>

    <div class="w-25">
      <?php if (file_exists($myfile1)){ ?>
        <img class='rounded img-thumbnail' src='<?php echo $myfile1 ?>?t=<?php echo rand() ?>'/>
        <?php } ?>
    </div>
 </div> 

  <div class="my-2 d-flex justify-content-between">
      <label>Категория заявки:</label>
      <select name="id_category" style="
    width: 190px;">
        <?php foreach ($mysqli->query('SELECT * FROM category') as $catrow) { ?>
          <option <?php if ($catrow['id'] == $myrow['id_category']) echo 'selected'; ?> 
            value="<?php echo $catrow['id'] ?>"><?php echo $catrow['name'] ?></option>
        <?php } ?>
      </select>
    </div>

    
    <div class="my-2 d-flex justify-content-between">
      <label>Швея:</label>
      <select name="id_seamstress" style="width: 190px;">
          <?php foreach ($mysqli->query('SELECT * FROM seamstresses') as $catrow) { ?>
            <option <?php if ($catrow['id'] == $myrow['id_seamstress']) echo 'selected'; ?> 
              value="<?php echo $catrow['id'] ?>"><?php echo $catrow['fio'] ?></option>
          <?php } ?>
        </select>
    </div>


    <div class="small text-danger text-center"><?php echo $error_count ?></div>
      <div class="mb-2 d-flex justify-content-between">
      <label>Количество:</label>
      <input type="text" name="count" id="count" value="<?php echo isset($myrow['count']) ? $myrow['count'] : 1 ?>">
    </div>


    <div class="small text-danger text-center"><?php echo $error_datecreate ?></div>
      <div class="mb-2 d-flex justify-content-between">
      <label>Временная метка:</label>
      <input type="datetime-local" id="dateInput" name="date_create">
    </div>

    <div class="small text-danger text-center"><?php echo $error_id_user ?></div>
<div class="mb-2 d-flex justify-content-between">
    <label>Клиент:</label>
    <select name="id_user">
        <?php
        foreach ($users as $userId => $userData) {
            echo "<option value='$userId'>$userData</option>";
        }
        ?>
    </select>
</div>

<div class="small text-danger text-center"><?php echo $error_phone ?></div>
      <div class="mb-2 d-flex justify-content-between">
      <label>Номер телефона:</label>
      <input type="text" name="phone" id="phone" value="<?php echo isset($myrow['phone']) ? $myrow['phone'] : 1 ?>">
    </div>

    <div class="small text-danger text-center"><?php echo $error_file ?></div>
    <div class="mb-2 d-flex justify-content-between">
      <label>Пример:</label> <input type="file" name="myfile">
    </div>
    <div class="small text-danger text-center"><?php echo $error_file1 ?></div>
    <div class="mb-2 d-flex justify-content-between">
      <label>Результат:</label> <input type="file" name="myfile1">
    </div>
    <div class="d-flex justify-content-end">
      <button class="btn btn-danger">Удалить</button>
      <button class="btn btn-primary ms-4">Сохранить</button>
    </div>
  </div></div>
</form>
<script>
  $('button.btn-danger').on('click', e => { if (!confirm('Удалить заявку?')) return;
    $.ajax({ url: 'records_edit.php?id=<?php echo $id ?>', method: 'delete',
      success: res => { window.location.replace('records.php')}
    });
  });

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
<?php require '../footer.php'; ?>

<?php require 'db_connect.php';
$title = 'Авторизация';

if ($method == 'POST') { $data = array('login' => 'ошибка авторизации');

  $login = addslashes($_POST['login']);
  $sth = $mysqli->prepare("SELECT * FROM users WHERE login=?");
  $sth->bind_param('s', $login); $sth->execute(); $result = $sth->get_result();
  if ($result->num_rows > 0){ 
    $password = md5($_POST['password']);
    $myrow = $result->fetch_assoc();
    if ($myrow['password'] == $password){
      $data['is_valid'] = BASE.'/';
      $_SESSION['User'] = $myrow['id'];
      $_SESSION['Name'] = $myrow['fio'];
      $_SESSION['isAdmin'] = $myrow['isAdmin'];
    }
  }

  header('Content-type: application/json');
  echo json_encode($data, JSON_FORCE_OBJECT);
  return;
}

require 'header.php';
?>
<form class="mt-4 d-flex flex-column align-items-center ajaxForm">
  <div>
    <div class="mb-2 d-flex justify-content-between">
      <label>Логин:</label>
      <input type="text" name="login" required>
    </div>
    <div class="mb-2 d-flex justify-content-between">
      <label>Пароль:</label>
      <input type="password" name="password" required>
    </div>
    <div class="d-flex justify-content-end">
      <button class="btn btn-primary checkFormBtn">Войти</button>
    </div>
  </div>
</form>
<?php require 'footer.php'; ?>

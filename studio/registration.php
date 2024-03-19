<?php require 'db_connect.php';  
$title = 'Регистрация';  
  
if ($method == 'POST') {  
    $data = array('success' => 1);  
    $myrow = [];   
  
    $key = 'fio';  
    $myrow[$key] = addslashes($_POST[$key]);  
    if (!$myrow[$key]) {  
        $data['success'] = 0;  
        $data[$key] = 'обязательное поле';  
    } elseif (!preg_match("/[А-Яа-я -]/u", $myrow[$key])) {  
        $data['success'] = 0;  
        $data[$key] = 'разрешенные символы: кирилица, пробел и тире';  
    }  
  
    $key = 'login';  
    $myrow[$key] = addslashes($_POST[$key]);  
    if (!$myrow[$key]) {  
        $data['success'] = 0;  
        $data[$key] = 'обязательное поле';  
    } elseif (!preg_match("/[A-Za-z0-9-]/", $myrow[$key])) {  
        $data['success'] = 0;  
        $data[$key] = 'разрешенные символы: латиница, цифры и тире';  
    } else {  
        $sth = $mysqli->prepare("SELECT * FROM users WHERE $key=?");  
        $sth->bind_param('s', $myrow[$key]);  
        $sth->execute();  
        $result = $sth->get_result();  
        if ($result->num_rows > 0) {  
            $data['success'] = 0;  
            $data[$key] = 'такой логин уже используется';  
        }  
    }  
  
    $key = 'password';  
    $myrow[$key] = $_POST[$key];  
    if (!$myrow[$key]) {  
        $data['success'] = 0;  
        $data[$key] = 'обязательное поле';  
    } elseif (strlen($myrow[$key]) < 6) {  
        $data['success'] = 0;  
        $data[$key] = 'не менее 6 символов';  
    }    
  
    $key = 'password2';  
    if (!$_POST[$key]) {  
        $data['success'] = 0;  
        $data[$key] = 'обязательное поле';  
    } elseif ($_POST[$key] != $myrow['password']) {  
        $data['success'] = 0;  
        $data[$key] = 'пароли не совпадают';  
    }  
  
    if ($data['success']) {  
        $query = "INSERT INTO users (fio, login, password) VALUES (?, ?, ?)";  
        $sth = $mysqli->prepare($query);  
        $sth->bind_param('sss', $myrow['fio'],  $myrow['login'], md5($myrow['password']));  
         
        if ($sth->execute()) { 
            $data['is_valid'] = BASE.'/login.php'; 
        } else { 
            $data['fio'] = 'ошибка регистрации пользователя'; 
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
            <label>ФИО:</label>  
            <input type="text" name="fio" required>  
        </div>  
 
        <div class="mb-2 d-flex justify-content-between">  
            <label>Логин:</label>  
            <input type="text" name="login" required>  
        </div>  
 
        <div class="mb-2 d-flex justify-content-between">  
            <label>Пароль:</label>  
            <input type="password" name="password" required>  
        </div>  
 
        <div class="mb-2 d-flex justify-content-between">  
            <label>Пароль:</label>  
            <input type="password" name="password2" required>  
        </div>  
 
        <div class="mb-2 d-flex justify-content-between">  
            <input type="checkbox" id="rules" required>  
            <label class='ms-2' for='rules'>я согласен с правилами регистрации</label>  
        </div>  
 
        <div class="d-flex justify-content-end">  
            <button class="btn btn-primary checkFormBtn">Зарегистрировать</button>  
        </div>  
 
    </div>  
 
</form>  
 
<?php require 'footer.php'; ?>
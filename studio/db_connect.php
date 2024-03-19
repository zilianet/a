<?php
session_start();
$is_authenticated = isset($_SESSION["User"]) && $_SESSION["User"];
$is_admin = $is_authenticated && isset($_SESSION["isAdmin"]) && $_SESSION["isAdmin"];
const BASE = '/studio'; 
const MEDIA = BASE.'/media/photo/'; 
const MY_PATH = BASE.'/media/photo/'; 
const FILEUSER_PATH = 'media/photo/';


    $mysqli = new mysqli('localhost','root','','studio'); 
    $mysqli->set_charset("utf8mb4");

  $id = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;
  $method = $_SERVER['REQUEST_METHOD'];

  function danger($text){ echo "<div class='alert alert-danger text-center'>$text</div>";}

  function assert_user(){ global $is_authenticated;
    if ($is_authenticated) return;
    require 'header.php';
    danger('Доступно только авторизированным пользователям'); require 'footer.php';
    exit;
  }

  function assert_admin(){ global $is_admin; assert_user();
    if ($is_admin) return;
    require 'header.php';
    danger('Доступно только администраторам'); require 'footer.php';
    exit;
  }?>
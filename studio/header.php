  <!DOCTYPE html>
  <html>

  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $title ?></title>
    <link href="<?php echo BASE ?>/style/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo BASE ?>/style/style.css" rel="stylesheet">
    <link rel="icon" href="<?php echo BASE ?>/media/logo.jpg">
  </head>
<style>
  header {
  background-color: #fff; /* white background color */
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); /* shadow effect */
}

.navbar {
  padding: 0.5rem 1rem; /* padding around the navbar */
}

.navbar-brand img {
  margin-right: 10px; /* add some space between the logo and text */
}

.nav-link {
  color: #333; /* link color */
}

.nav-link:hover {
  color: #007bff; /* link color on hover */
}

.navbar-toggler {
  border-color: #007bff; /* color of the toggler icon */
}

.navbar-toggler-icon {
  background-image: url('data:image/svg+xml;...'); /* custom icon for the toggler */
}

@media (max-width: 768px) {
  .navbar-collapse {
    background-color: #f8f9fa; /* background color for collapsed menu */
    border-top: 1px solid #dee2e6; /* border at the top of collapsed menu */
  }
}

</style>
  <body>
    <header class="container-md">
      <nav class="navbar navbar-expand-lg bg-info bg-gradient">
        <div class="container-fluid">
          <a class="navbar-brand" href="<?php echo BASE ?>"><img src="<?php echo BASE ?>/media/logo.jpg" width="30"></a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#myMenu" aria-controls="myMenu" aria-expanded="true">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="myMenu">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
              <li class="nav-item"><a class="nav-link" href="<?php echo BASE ?>/">О нас</a></li>
              <?php if ($is_authenticated){ ?>
              <li class="nav-item"><a class="nav-link" href="<?php echo BASE ?>/record.php">Оформить заказ</a></li>
              <li class="nav-item"><a class="nav-link" href="<?php echo BASE ?>/records.php">Мои заказы</a></li>
                <?php if ($is_admin){ ?>
              <li class="nav-item"><a class="nav-link" href="<?php echo BASE ?>/admin/index.php">АдминПанель</a></li>
              <li class="nav-item"><a class="nav-link" href="<?php echo BASE ?>/users.php">Пользователи</a></li>
              <li class="nav-item"><a class="nav-link" href="<?php echo BASE ?>/zapros.php">Запросы</a></li>
                <?php } ?>
              <?php } ?>
            </ul>
            <div class="form-inline mt-2 mt-md-0">
              <ul class="navbar-nav mr-auto">
                <?php if ($is_authenticated){ ?>
                <li class="pt-2"><?php echo $_SESSION["Name"] ?></li>
                <li class="nav-item"><a class="nav-link" href="<?php echo BASE ?>/logout.php">Выйти</a></li>
                <?php } else { ?>
                <li class="nav-item"><a class="nav-link" href="<?php echo BASE ?>/registration.php">Регистрация</a></li>
                <li class="nav-item"><a class="nav-link" href="<?php echo BASE ?>/login.php">Войти</a></li>
                <?php } ?>
              </ul>
            </div>
          </div>
        </div>
      </nav>
    </header>

    <script src="<?php echo BASE ?>/js/jquery-3.7.1.min.js"></script>

    <main class="container-md mt-5 pt-3">
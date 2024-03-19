<?php require 'db_connect.php'; $title = 'Ателье'; require 'header.php'; ?>
<div class='h3 text-center'><?php echo $title ?></div>
<?php
$category = array(); 
foreach ($mysqli->query('SELECT * FROM category') as $row){ $category[$row['id']] = $row['name']; } 
?>

<div class="d-flex justify-content-center">
  <img class="rounded img-thumbnail w-25" src="<?php echo BASE ?>/media/logo.jpg">
  <h3 class="ms-3 my-auto">Стиль для каждого!</h3>
</div>

<div id="carouselExampleCaptions" class="carousel slide bg-success-subtle mt-5 p-2" data-bs-ride="carousel">
  <div class="carousel-indicators">
    <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
    <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1" aria-label="Slide 2"></button>
    <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="2" aria-label="Slide 3"></button>
    <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="3" aria-label="Slide 4"></button>
    <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="4" aria-label="Slide 5"></button>
  </div>
  <div class="carousel-inner">
    <?php $class = 'active'; foreach ($mysqli->query('SELECT * FROM records ORDER BY id DESC LIMIT 5') as $row) { ?>
    <div class="carousel-item <?php echo $class ?> w-100">
      <div class="d-flex justify-content-center" style="height: 50vh">
        <?php $file = $row['id'].'_0.jpg'; if (file_exists(FILEUSER_PATH.$file)){ ?>
        <img class='d-block img-fluid' src='<?php echo MEDIA.$file ?>' alt="<?php echo $row['name'] ?>">
        <?php } ?>
      </div>
      <div class="carousel-caption d-none d-md-block text-bg-info opacity-50">
        <h5 class="m-0"><?php echo $row['name'] ?></h5>
        <p class="m-0"><?php echo $category[$row['id_category']]?></p>
      </div>
    </div>
    <?php $class = ''; } ?>
  </div>
  <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Previous</span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Next</span>
  </button>
</div>



<?php require 'footer.php'; ?>

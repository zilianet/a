<?php 
$title = 'Заявки'; 
require 'db_connect.php'; 
require 'header.php';  

foreach ($mysqli->query('SELECT * FROM category') as $row){ $category[$row['id']] = $row['name']; } 
$users = array(); 
foreach ($mysqli->query('SELECT * FROM users') as $row){ $users[$row['id']] = $row['fio']; } 

$seamstresses = array(); 
foreach ($mysqli->query('SELECT * FROM seamstresses') as $row){ 
    $seamstresses[$row['id']] = $row['fio']; 
} 

?> 

<style> 
  .table-primary td.tovar_photo { width: 10%;} 
  .table-primary tbody tr { cursor: pointer;} 
  .tovar_photo>img{
        width: 140px; 
        height: 140px; 
        object-fit: cover;
    }


table td, table th {
    padding: 8px;
    text-align: center;
}
</style> 

<table class="table table-primary table-hover table-bordered table-striped mb-4"> 
  <thead class=""> 
    <tr> 
      <th class="text-center">№</th> 
      <th>Категория</th> 
      <th>Швея</th> 
      <th>Количество</th> 
      <th>Дата подачи</th> 
      <th>Пользователь</th>
      <th>Телефон</th> 
      <th>Ожидание</th>
      <th>Результат</th> 
      <th class='text-center'><a class="btn btn-success" href="records_edit.php">Добавить</a></th>
    </tr> 
  </thead>
  <tbody>
    <?php $num = 1; foreach ($mysqli->query('SELECT * FROM records') as $myrow) { ?>
        <tr data-id="<?php echo $myrow['id'] ?>">
            <td class="text-center"><?php echo $num++ ?></td>
            <td class="text-center"><?php echo $category[$myrow['id_category']] ?></td>
            <td class="text-center"><?php echo $seamstresses[$myrow['id_seamstress']] ?></td>
            <td><?php echo $myrow['count'] ?></td>
            <td class="text-end"><?php echo $myrow['date_create'] ?></td>
            <td class="text-center"><?php echo $users[$myrow['id_user']]  ?></td>
            <td class="text-center"><?php echo $myrow['phone']  ?></td>

            <td class='tovar_photo'>
            <?php $myfile = $myrow['id'].'_0.jpg'; if (file_exists(FILE_PATH.$myfile)){ ?>
            <img class='rounded img-thumbnail' src='<?php echo MEDIA.$myfile ?>?t=<?php echo rand() ?>'/>
            <?php } ?>
            </td>
            <td class='tovar_photo'>
                <?php 
                $myfile1 = $myrow['id'].'_1.jpg'; 
                if (file_exists(FILE_PATH.$myfile1)){ 
                ?>
                    <img class='rounded img-thumbnail' src='<?php echo MEDIA.$myfile1 ?>?t=<?php echo rand() ?>'/>
                <?php 
                } else {
                    echo "пока здесь ничего нет";
                }
                ?>
            </td>

            <td class='text-center'> 
                <a class="btn btn-secondary" href="records_edit.php?id=<?php echo $myrow['id'] ?>">Изменить</a>
                <button class="btn btn-danger" data-id="<?php echo $myrow['id'] ?>">Удалить</button>
            </td>
        </tr>
    <?php } ?>
  </tbody>
</table>

<script>
    $('button.btn-danger').on('click', e => {
        let $btn = $(e.currentTarget);
        if (!confirm('Удалить заявку с id ' + $btn.data('id') + '?')) return;
        $.ajax({
            url: 'records_edit.php?id=' + $btn.data('id'),
            method: 'DELETE',
            success: res => { window.location.reload() }
        });
    });
</script>

<?php require '../footer.php'; ?>

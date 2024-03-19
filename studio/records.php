<?php
$title = 'Мои заявки';
require 'db_connect.php';
require 'header.php';

$category = array();
foreach ($mysqli->query('SELECT * FROM category') as $row) {
    $category[$row['id']] = $row['name'];
}

$records = array();
foreach ($mysqli->query('SELECT * FROM records') as $row){
    $records[] = $row;
}

$seamstresses = array();
foreach ($mysqli->query('SELECT * FROM seamstresses') as $row) {
    $seamstresses[$row['id']] = $row['fio'];
}

if (!empty($id)) {
    $sth = $mysqli->prepare("SELECT * FROM records WHERE id=?");
    $sth->bind_param('i', $id);
    $sth->execute();
    $result = $sth->get_result();

    if ($result->num_rows == 0) {
        echo danger('Заявка не найдена');
    } else {
        $myrow = $result->fetch_assoc();
        $sth = $mysqli->prepare("DELETE FROM records WHERE id=?");
        $sth->bind_param('i', $id);
        $sth->execute();
    }
}
?>

<style>
    .table-success td.tovar_photo { width: 10%; }
    .table-success tbody tr { cursor: pointer; }
</style>

<table class="table table-success table-hover table-bordered table-striped mb-4">
    <thead>
        <tr>
            <th class="text-center">№</th>
            <th>Номер телефона</th>
            <th>Категория</th>
            <th>Количество</th>
            <th>Временная метка</th>
            <th class='text-center'>Действие</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $num = 1;
        $records_query = $mysqli->query('SELECT * FROM records WHERE id_user = ' . $_SESSION['User']);
        if ($records_query->num_rows > 0) {
            while ($myrow = $records_query->fetch_assoc()) {
        ?>
                <tr data-id="<?php echo $myrow['id'] ?>">
                    <td class="text-center"><?php echo $num++ ?></td>
                    <td><?php echo $myrow['phone'] ?></td>
                    <td><?php echo $category[$myrow['id_category']] ?></td>
                    <td><?php echo $myrow['count'] ?></td>
                    <td><?php echo $myrow['date_create'] ?></td>
                    <td class="text-center">
                        <a href="records_edit.php?id=<?php echo $myrow['id'] ?>" class="btn btn-primary">Редактировать</a>
                        <a href="?id=<?php echo $myrow['id'] ?>" class="btn btn-danger">Удалить</a>
                    </td>

                </tr>
        <?php
            }
        } else {
            echo "<tr><td colspan='7'>Нет данных для отображения.</td></tr>";
        }
        ?>
    </tbody>
</table>

    <script>
                $('a.btn-danger').on('click', e => { if (!confirm('Удалить заявку?')) return;
          $.ajax({ url: 'records_edit.php?id=<?php echo $id ?>', method: 'delete',
            success: res => { window.location.replace('records.php')}
          });
        });
      </script>

<?php require 'footer.php'; ?>

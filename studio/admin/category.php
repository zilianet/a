<?php $title = 'Категории заявок';
require 'db_connect.php';
require 'header.php'; ?>
<table class="table table-primary table-hover table-bordered table-striped w-auto mx-auto">
  <thead class="">
		<tr>
			<th class="text-center">№</th>
			<th>Название</th>
			<th>Цена</th>
			<th><a class="btn btn-success" href="category_edit.php">Добавить</a></th>
		</tr>
	</thead>
	<tbody>
		<?php $num = 1;
		foreach ($mysqli->query('SELECT * FROM category') as $myrow) { ?>
			<tr>
				<td class="text-center"><?php echo $num++ ?></td>
				<td><?php echo $myrow['name'] ?></td>
				<td><?php echo $myrow['price']?></td>
				<td>
					<a class="btn btn-primary" href="category_edit.php?id=<?php echo $myrow['id'] ?>">Изменить</a>
					<button class="btn btn-danger" data-id=<?php echo $myrow['id'] ?>>Удалить</button>
				</td>
			</tr>
		<?php } ?>
	</tbody>
</table>

<script>
	$('button.btn-danger').on('click', e => {
		let $btn = $(e.currentTarget);
		if (!confirm('Удалить категорию ' + $btn.parent().prev().prev().html())) return;
		$.ajax({
			url: 'category_edit.php?id=' + $btn.data('id'),	method: 'delete',
			success: res => {	window.location.reload()}
		});
	});
    </script>
<?php require '../footer.php'; ?>
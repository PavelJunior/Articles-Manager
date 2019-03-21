<?php foreach ($posts as $post): ?>
	<div>
		<h2>
			<a href="<?=ROOT?>post/one/<?=$post['id']?>"><?=$post['title']?></a> <a href="<?=ROOT?>post/edit/<?=$post['id']?>">EDIT</a>
		</h2>
		<p>
			<?=$post['preview']?>
		</p>
	</div>
<?php endforeach; ?>

<a href="<?=ROOT?>post/add">Добавить статью</a>
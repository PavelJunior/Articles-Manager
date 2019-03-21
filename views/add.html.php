<form method="post">
	Название<br>
	<input type="text" name="name" value="<?=$name?>">
	<?=$nameError?><br>
	Превью<br>
	<input type="text" name="preview" value="<?=$preview?>">
	<?=$previewError?><br>
	Контент<br>
	<textarea name="text"><?=$text ?></textarea>
	<?=$textError?><br>
	<input type="submit" value="submit"><br>
	<a href="<?=ROOT?>">Назад</a>
</form>
<form method="post">
	Название<br>
	<input type="text" name="name" value="<?=$name?>">
	<?=$nameError?><br>
	Превью<br>
	<textarea name="preview"><?=$preview?></textarea>
	<?=$previewError?><br>
	Контент<br>
	<textarea name="text"><?=$text?></textarea>
	<?=$textError?><br>
	<input type="submit" value="submit"><br>
	<a href="<?=ROOT?>">Назад</a>

</form>
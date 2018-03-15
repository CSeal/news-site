<div>
	<Label for="<?=$input->name?>">Введите код с картинки:</label>
	<input id="<?=$input->name?>" name="<?=$input->name?>" type="text" <?php include 'jsv.tpl'?>/>
</div>
<div class="captcha">
	<img src="/images/update.png" alt="Обновить картинку"/>
	<img src="captcha.php" alt="Каптча"/>
</div>
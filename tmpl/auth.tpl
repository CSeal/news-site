<div id="auth">
	<?php if($message){?><span class="message"><?=$message?></span><?php }?>
	<form name="auth" action="<?=$action?>" method="post">
		<div>
			<input type="text" name="login" placeholder="Логин" />
			<input type="password" name="password" placeholder="Пароль" />
			<input type="submit" name="auth" value="Войти" />
		</div>
	</form>
	<img src="images/bg_item_top.png" alt="" id="top_sep" />
	<img src="images/icon_register.png" alt="" id="icon_register" />
	<a href="<?=$linkRegister?>" id="link_register">Регистрация</a>
	<img src="images/bg_item_top.png" alt="" />
	<div id="links_reset">
		<a href="<?=$linkReset?>">Забыли пароль?</a>
		<a href="<?=$linkRemind?>">Забыли логин?</a>
	</div>
</div>
<div class="main">
	<?=$breadcrumbs?>
	<?php if($header){?><h1><?=$header?></h1><?php }?>
	<?php if($message){?><p class="message"><?=$message?></p><?php }?>
	<div class="form">
		<div <?php if($name){?> id="<?=$name?>"<?php }?>>
			<form <?php if($name){?> name="<?=$name?>"<?php }?> action="<?=$action?>" method="<?=$method?>" <?php if($enctype){?> enctype="<?=$enctype?>"<?php }?> <?php if($check){?> onsubmit = "return checkForm(event)" <?php }?>>
				<?php foreach($inputs as $input){?>
					<?php include 'f_'.$input->type.'.tpl';?>
				<?php }?>
			</form>
		</div>
	</div>
</div>
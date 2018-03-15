<div>
	<input id="<?=$input->name?>" name="<?php if($input->name){?><?=$input->name?><?php }else{ ?><?=$name?><?php }?>" type="submit" value="<?=$input->value?>" <?php if(isset($jsv[$input->name])){?>t_confirm="<?=$jsv[$input->name]->t_confirm?><?php }?>" />
</div>
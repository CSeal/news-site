<div>
	<Label for="<?=$input->name?>"><?=$input->label?>:</label>
	<input id="<?=$input->name?>" name="<?=$input->name?>" type="text" value="<?=$input->value?>" placeholder="<?=$input->defValue?>" <?php include "jsv.tpl";?>/>
</div>
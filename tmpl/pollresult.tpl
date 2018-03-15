<div class="main">
	<?=$breadcrumbs?>
	<?php if($message){ ?><p class="message"><?=$message?></p><?php }?>
	<h1><?=$title?></h1>
	<div id="poll_result">
	<?php foreach($data as $d){?>
		<p><?=$d->title?></p>
		<div class="poll_result" style="width: <?=$d->percent?>%;"><?=$d->voters?></div>
		<p class="poll_percent"><?=$d->percent?>%</p>
		<div class="clear"></div>
	<?php }?>
	<br />
	<p>Общее количество голосов :<b><?=$countVoters?></b></p>
	</div>
</div>
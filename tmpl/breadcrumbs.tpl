<nav>
	<span>Вы здесь: </span>
	<?php $first = true; foreach($data as $d){?>
	<?php if(!$first){?>&rArr;<?php }?>
	<?php if(!$d->link){?><span><?=$d->title ?></span>
	<?php }else{ ?><a href="<?=$d->link ?>"><span><?=$d->title ?></span></a><?php }?>
	<?php $first = false; }?>
</nav>
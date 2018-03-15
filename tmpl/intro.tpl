<div class="main">
	<?php if(isset($breadcrumbs)){?><?=$breadcrumbs?><?php }?>
	<article>
		<?php if($obj->title){?><h1><?=$obj->title?></h1><?php }?>
		<?php if($obj->img){?><div class="article_img">
			<img src="<?=($obj->img)?>" alt="<?=$obj->title?>">
		</div>
		<?php }?>
		<?=$obj->description?>
	</article>
</div>
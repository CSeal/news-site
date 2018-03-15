<head>
	<?php if($title){ ?>
	<title><?=$title?></title>
	<?php } ?>
	<?php foreach($meta as $m): ?>
	<meta <?php if($m->httpEquiv): ?> http-equiv="<?=$m->httpEquiv?>"
		  <?php else: ?> name="<?=$m->name?>" 
		  <?php endif; ?>
		  content="<?=$m->content?>" />
	<?php endforeach; ?>	  
	<?php foreach($js as $src): ?>
		<script type="text/javascript" src="<?=$src?>"></script>
	<?php endforeach; ?>
	<?php foreach($css as $href): ?>
		<link rel="stylesheet" href="<?=$href?>" type="text/css" />
	<?php endforeach; ?>
	<?php if($favicon): ?>
		<link rel="shortcut icon" href="<?=$favicon?>" type="image/x-icon" />
	<?php endif; ?>
</head>
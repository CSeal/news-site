<?php
$countPages = ceil($countElements / $countElementsOnPage);//Определения числа страниц(Округление до верхнего целого)
if($countPages > 1){
	$left = $active - 1;//Количество страниц с лева
	$right = $countPages - $active;//Количество страниц с права
	if($left < floor($countShowPages / 2)) $start = 1;// 
	else $start = floor($countShowPages / 2);
	$end = $start + $countShowPages - 1;
	if($end > $countPages){
		$start -= $end - $countPages;//В случае если конечный порог выходит за дипозон количества страниц, то надо сместить назад начальный порог
		$end = $countPages;//Конечный порог становится равным количеству страниц
		if($start < 1 ) $start = 1;// Если при смещении стартового порога, он вышел из дипозона натуральных чисел, тогда стартовый парог равен первому натуральному числу
	}

?>
<div id="pagination">
	<?php if($active == 1){?><span>Первая</span><span>Предыдущая</span>
	<?php }else{ ?><a href="<?=$urlPage.'1'?>"><span>Первая</span></a><a href="<?=$urlPage.($active - 1)?>"><span>Предыдущая</span></a><?php }?>
	<?php for($i = $start; $i <= $end; ++$i){?>
		<?php if($i == $active){?>
		<span class="active"><?=$i?></span>
		<?php }else{ ?>
		<a href="<?=$urlPage.$i?>"><span><?=$i?></span></a>
		<?php }?>
	<?php }?>
	<?php if($active == $countPages){?><span>Следующая</span><span>Последняя</span>
	<?php }else{ ?><a href="<?=$urlPage.($active + 1)?>"><span>Следующая</span></a><a href="<?=$urlPage.$countPages?>"><span>Последняя</span></a><?php }?>
</div>
<?php }?>
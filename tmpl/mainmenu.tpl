<?php
/**
*Шаблон для отрисовки древовидного каскадного меню.
*
*Циклически запускается функция. Если есть подменю(Значение войство id элемента меню ищется в значениях масива $childrens,
*если есть совпадения то выбирается первый совпавший ключ элемента в массиве $childrens и рекурорно проверется,
*есть ли у элемента с этим ключтом подменю(рекурсорный спуск). Перед каждой рекурсией елемент удаляется из массива $childrens. 
*Активные элементы выделяются маркером.
*/
?>
<?php function printItem($item, &$items, $childrens, $active){?>
<?php if(count($items) == 0) return; ?>
	<div>
		<a <?php if(in_array($item->id, $active)){ ?>class="active"<?php } ?><?php if($item->extenrnal){?>rel="externel"<?php } ?>href="<?=$item->link ?>"><?=$item->title?></a>
			<?php 
				while(true){
				 $key = array_search($item->id, $childrens);
				 if(!$key) break;
				 unset($childrens[$key]);
			?><?=printItem($items[$key], $items, $childrens, $active)?>		
			<?php }?>
	</div>	
<?php unset($items[$item->id]);?>
<?php } ?>
<div class="block">
	<div class="header">Уроки и статьи</div>
	<div class="content">
		<nav>
		<?php foreach($items as $item){ ?>
		<?php if(array_key_exists($item->id, $childrens)) continue;?>	
		<?=printItem($item, $items, $childrens, $active)?>
		<?php } ?>
		</nav>
	</div>
</div>


<nav>
				<ul id="topmenu">
					<?php foreach($items as $item){?>		
							<li><a href="<?=$item->link ?>"
						<?php if($item->link === $uri){ ?>
							class="active"
						<?php } ?>
						<?php if($item->external){ ?>
							rel="external" target="_blanck"
						<?php } ?>	
						><span><?=$item->title ?></span></a></li>
					<?php } ?>	
				</ul>
</nav>
<div class="main">
	<h2 class="h1">Свежие статьи</h2>
	<?php foreach($articles as $article){ ?>
	<section>
						<div class="article_img">
							<img src="<?=$article->img?>" alt="<?=$article->title?>" />
							<div>
								<img class="date_img" src="images/date.png" alt="" />
								<div class="date_text"><?=$article->day_show?><br /><?=$article->month_show?></div>
							</div>
						</div>
						<h2><?=$article->title?></h2>
						<p><?=$article->intro?></p>
						<a class="more" href="<?=$article->link?>">Прочитать</a>
						<div class="clear"></div>
						<div class="article_info">
							<ul>
								<li>
									<img src="images/icon_user.png" alt="" />
								</li>
								<li>Манузин Антон</li>
								<li><?=$article->count_сomments?> <?=$article->countComentText?></li>
								<?php if($article->section){?>
								<li>
									<a href="<?=$article->section->link?>"><?=$article->section->title?></a>
								</li>
								<?php if($article->category){?>
								<li>
									<a href="<?=$article->category->link?>"><?=$article->category->title?></a>
								</li>
								<?php }?>
								<?php }?>
							</ul>
						<div class="clear"></div>
						</div>
					</section>
	<?php }?>
	<?php if(count($moreArticles) > 0){?><h3 style="text-align: center;">Ещё статьи:</h3><?php  foreach($moreArticles as $oneOfMoreArticle){?>
	<p><a href="<?=$oneOfMoreArticle->link?>"><?=$oneOfMoreArticle->title?></a></p>
	<?php }}?>
<?=$pagination?>
</div>					
<?php function printComment($comment, $comments, &$childrens, $auth_user){ ?>
		<div class="comment" id="comment_<?=$comment->id?>">
			<img src="<?=$comment->user->avatar?>" alt="<?=$comment->user->name?>" />
			<span class="name"><?=$comment->user->name?></span>
			<span class="date"><?=$comment->date?></span>
			<p class="text"><?=$comment->text?></p>
	<p class="functions"><span <?php if(!$auth_user){?>onclick="alert('Для добавления коментариев зарегестрируйтеся!')"<?php }?>>Ответить</span></p>
			<?php while(true){
				$key = array_search($comment->id, $childrens);
				if(!$key) break;
				unset($childrens[$key]);
				if(isset($comments[$key])){?>
					<?=printComment($comments[$key], $comments, $childrens, $auth_user)?>
				<?php }?>
			<?php }?>
		</div>
<?php }?>

<div class="main">
<?php if(isset($breadcrumbs)){ ?><?=$breadcrumbs?><?php }?>
	<article>
		<h1><?=$article->title?></h1>
		<?php if($article->img){?>
		<div class="aricle_img">
		<img src="<?=$article->img?>" alt="<?=$article->title?>"/>
		</div>
		<?php }?>
		<?=$article->full?>
		<div class="article_info">
			<ul>
				<li>
					<div>
						<img src="/images/date_article.png" alt=""/>
					<div>
					Создано: <?=$article->date?>
				</li>
				<li>
					<div>
						<img src="/images/icon_user.png" alt=""/>
					<div>
					Манузин Антон
				</li>
			</ul>
			<div class="clear"></div>
		</div>
	</article>
	<div id="article_pn">
	<?php if($prev_article){ ?><a id="prev_article" href="<?=$prev_article->link?>">Предидущая статья</a><?php } ?>
	<?php if($next_article){ ?><a id="next_article" href="<?=$next_article->link?>">Следующая статья</a><?php } ?>
	<div class="clear"></div>
	</div>
	<div id="article_copy">
		<p class="center"><i>Копирование материалов разрешается только с указанием автора (Манузин Антон) и индексируемой прямой ссылкой на сайт (<a href="<?=Config::ADDRESS?>"><?=Config::ADDRESS?></a>)!</i></p>
	</div>
	<div id="article_vk">
		<p>Добавляйтесь ко мне в друзья <b>ВКонтакте</b>: <a rel="external" href="https://vk.com/id172208620">Манузин Антон</a></p>
	</div>
	<p>Если у Вас остались какие-либо вопросы, либо у Вас есть желание высказаться по поводу этой статьи, то Вы можете оставить свой комментарий внизу страницы.</p>
	<p>Если Вам понравился сайт, то разместите ссылку на него (у себя на сайте, на форуме, в контакте):</p>
	<ol id="recom">
		<li>
			Кнопка:
			<br /><textarea name="" cols="50" rows="5">&lt;a href="<?=Config::ADDRESS?>" target="_blank"&gt;&lt;img src="<?=Config::ADDRESS.Config::DIR_IMG?>button.gif" style="border: 0; height: 31px; width: 88px;" alt="Как создать свой сайт" /&gt;&lt;/a&gt;</textarea>
			<p>Она выглядит вот так: <a href="<?=Config::ADDRESS?>" rel="external"><img src="<?=Config::ADDRESS.Config::DIR_IMG?>button.gif" style="border: 0; height: 31px; width: 88px;" alt="Как создать свой сайт" /></a></p>
		</li>
		<li>
			Текстовая ссылка:<br /><textarea name="" cols="50" rows="5">&lt;a href="<?=Config::ADDRESS?>" target="_blank"&gt;Как создать свой сайт&lt;/a&gt;</textarea>
			<p>Она выглядит вот так: <a href="<?=Config::ADDRESS?>" rel="external">Как создать свой сайт</a></p>
		</li>
		<li>BB-код ссылки для форумов (например, можете поставить её в подписи):
			<br /><textarea name="" cols="50" rows="5">[URL="<?=Config::ADDRESS?>"]Как создать свой сайт[/URL]</textarea>
		</li>
	</ol>
	<div id="comments">
		<h2 class="h1">Комментарии(<span id="count_comments"><?=count($comments)?></span>):</h2>
		<input type="button" value="Добавить комментарий" id="add_comment" <?php if(!$auth_user){?> onclick="alert('Для добавления коминтариев на сайте необходимо авторизоватся!');"<?php }?>/>
		<?php foreach($comments as $comment){?>
			<?php if($comment->parent_id == 0){?><?=printComment($comment, $comments, $childrens, $auth_user)?><?php }?>
		<?php }?>
		<div class="clear"></div>
		<?php if(!$auth_user){?>
			<p class="center">Для добавления комментариев надо войти в систему.<br />Если вы ещё не зарегестрированы на сайте, то  сначала <a href="<?=$link_register?>">зарегестрируйтесь</a></p>
		<?php }?>
	</div>
</div>
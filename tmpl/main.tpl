<!DOCTYPE html>
<html>
	<?=$header?>
<body>
	<div id="container">
		<header>
			<h1>&lt;CSeal.cool /&gt;</h1>
			<?=$topMenu?>
			</header>
		<div id="top">
			<div class="clear"></div>
			<div id="search">
				<form name="search" action="<?=$linkSearch?>" method="get">
					<table>
						<tr>
							<td>
								<input type="text" name="query" placeholder="Поиск" />
							</td>
							<td>
								<input type="submit" name="search" value="" />
							</td>
						</tr>
					</table>
				</form>
			</div>
			<?=$auth?>
		</div>
		<?=$slider?>
		<div id="content">
			<div id="left"><?=$left?></div>
			<div id="right"><?=$right?></div>
			<div id="center"><?=$center?></div>
			<div class="clear"></div>
		</div>
		<footer>
			<div class="sep"></div>
			<p>Copyright &copy; 2010-<?=date("Y");?> Манузин Антон --> CSeal. Все права защищены.</p>
		</footer>
	</div>
</body>
</html>
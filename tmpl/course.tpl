<?php foreach($courses as $course){?>
	<div class="block">
	<div class="header"><?=$course->header?></div>
					<div class="content">
						<div class="free">
							<p class="title"><?=$course->sub_header?></p>
							<a rel="external" href="<?=$course->link?>">
							<img src="<?=$course->img?>" alt="<?=$course->sub_header?>" />
							</a>
							<?=$course->text?><?php if($course->did){?>
								<p class="center"><b>Чтобы получить Видеокурс,<br />заполните форму</b></p>
								<form name="free_course" action="#" method="post">
									<table>
										<tr>
											<td>
												<label for="free_course_email">E-mail:</label>
											</td>
											<td>
												<input id="free_course_email" type="text" name="email" value="<?php if($auth_user){?><?=$auth_user->email?><?php }?>"/>
											</td>
										</tr>
										<tr>
											<td>
												<label for="free_course_name">Имя:</label>
											</td>
											<td>
												<input id="free_course_name" type="text" name="name" value="<?php if($auth_user){?><?=$auth_user->name?><?php }?>"/>
											</td>
										</tr>
										<tr>
											<td colspan="2" class="center">
												<input type="submit" name="free_course" value="Получить курс" class="button" />
											</td>
										</tr>
									</table>
								</form>
							<?php } else {?>
								<div class="center">
								<a href="<?=$course->link?>" class="button">Записаться</a>
								</div>
							<?php }?>
						</div>
					</div>
				</div>
<?php }?>
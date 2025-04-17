<?php
require_once ($_SERVER['DOCUMENT_ROOT'] . '/install/core/head.php');
	$act = isset($_GET['act']) ? $_GET['act'] : null;
	switch ($act) {
		default:
		echo '<div class="content">';
		echo '<div class="menu">';
		echo '<div class="title">Добро пожаловать!</div>';
		echo '<div class="pod_title">
		Вас приветствует мастер установки LetsFor!<br />
		Перед началом установки проверьте что база данных создана и готова к работе, чтобы начать установку нажмите на кнопку ниже.
		</div>';
		if (!file_exists(''.$_SERVER['DOCUMENT_ROOT'].'/LFcore/config.php'))
		{
			echo '<a class="button" href="' . homeLink() . '/install/?act=form">Начать установку</a>';
		} else {
			echo '<a class="button-dissable">Установка уже выполнена</a>';
			echo '<a class="button flex" href="' . homeLink() . '/install/?act=reinstall" style="margin-left: 5px;"><span class="icon_mm"><div class="ico_cen_bar"><svg xmlns="http://www.w3.org/2000/svg" fill="#000000" width="10px" height="10px" viewBox="0 0 1024 1024" class="icon"><path d="M909.1 209.3l-56.4 44.1C775.8 155.1 656.2 92 521.9 92 290 92 102.3 279.5 102 511.5 101.7 743.7 289.8 932 521.9 932c181.3 0 335.8-115 394.6-276.1 1.5-4.2-.7-8.9-4.9-10.3l-56.7-19.5a8 8 0 0 0-10.1 4.8c-1.8 5-3.8 10-5.9 14.9-17.3 41-42.1 77.8-73.7 109.4A344.77 344.77 0 0 1 655.9 829c-42.3 17.9-87.4 27-133.8 27-46.5 0-91.5-9.1-133.8-27A341.5 341.5 0 0 1 279 755.2a342.16 342.16 0 0 1-73.7-109.4c-17.9-42.4-27-87.4-27-133.9s9.1-91.5 27-133.9c17.3-41 42.1-77.8 73.7-109.4 31.6-31.6 68.4-56.4 109.3-73.8 42.3-17.9 87.4-27 133.8-27 46.5 0 91.5 9.1 133.8 27a341.5 341.5 0 0 1 109.3 73.8c9.9 9.9 19.2 20.4 27.8 31.4l-60.2 47a8 8 0 0 0 3 14.1l175.6 43c5 1.2 9.9-2.6 9.9-7.7l.8-180.9c-.1-6.6-7.8-10.3-13-6.2z"/></svg></div></span></a>';
		}
		echo '</div>';
		echo '</div>';
		break;
		
	case 'form':
	if (!file_exists(''.$_SERVER['DOCUMENT_ROOT'].'/LFcore/config.php'))
		{
			echo '<div class="content">
			<div class="menu">
			<div class="title">Начало установки!</div>
			<div class="pod_title">
			Введите информацию о базе данных.
			</div>
			</div>
			</div>';
			
			echo '<div class="box">';
			echo '<div class="image">';
			echo '<img src="' . homeLink() . '/install/css/images/db.png"/>';
			echo '</div>';
			
			echo '<form method="post" id="install" action="' . homeLink() . '/install/core/check_reqments.php" class="instform">
			<dt><label for="host">Хост:</label></dt>
			<input type="text" id="dbHost" name="host" placeholder="localhost..." required><br>
			<dt><label for="user">Пользователь:</label></dt>
			<input type="text" id="dbUser" name="user" required><br>
			<dt><label for="password">Пароль:</label></dt>
			<input type="password" id="dbPass" name="password"><br>
			<dt><label for="database">База данных:</label></dt>
			<input type="text" id="dbName" name="database" required><br>
			<dt><label></label></dt>
			<button type="submit">Продолжить</button>
			</form>';
			echo '</div>';
		} else {
			header ("Location: " . homeLink() . "/install/?act=reinstall");
		}
		break;
		
	case 'reg':
		echo '<div class="content">
		<div class="menu">
		<div class="title">Окончание установки!</div>
		<div class="pod_title">
		Регистрация администратора.
		</div>
		</div>
		</div>';

		echo '<div class="box">';
		echo '<div class="image">';
		echo '<img src="' . homeLink() . '/install/css/images/reg.png"/>';
		echo '</div>';
		echo '<form method="post" action="' . homeLink() . '/registr" class="instform">
		<dt><label for="host">Логин:</label></dt>
		<input type="text" name="login" maxlength="13" required/><br />
		<dt><label for="user">email:</label></dt>
		<input type="text" name="email" maxlength="40" required/><br />
		<dt><label for="password">Пароль:</label></dt>
		<input type="password" name="pass" maxlength="25" required/><br />
		<dt><label for="database">Повтор пароля:</label></dt>
		<input type="password" name="r_pass" maxlength="25" required/><br />
		<dt><label></label></dt>
		<button type="submit" name="reg">Продолжить</button>
		</form>';
		echo '</div>';
		break;
		
	case 'reinstall':
		echo '<div class="content">';
		echo '<div class="menu">';
		echo '<div class="title">Обратите внимание!</div>';
		echo '<div class="pod_title">
		Чтобы переустановить LetsFor удалите файл config.php в /LFcore
		</div>';
		echo '<a class="button" href="' . homeLink() . '/install">Вернуться назад</a>';
		echo '</div>';
		echo '</div>';
		break;
		
	}

require_once ($_SERVER['DOCUMENT_ROOT'] . '/install/core/footer.php');
?>

<script>
	var x, i, j, l, ll, selElmnt, a, b, c;
	x = document.getElementsByClassName("gt-select");
	l = x.length;
	for (i = 0; i < l; i++) {
		selElmnt = x[i].getElementsByTagName("select")[0];
		ll = selElmnt.length;
		a = document.createElement("DIV");
		a.setAttribute("class", "select-selected");
		a.innerHTML = selElmnt.options[selElmnt.selectedIndex].innerHTML;
		x[i].appendChild(a);
		b = document.createElement("DIV");
		b.setAttribute("class", "select-items select-hide");
		for (j = 0; j < ll; j++) {
			c = document.createElement("DIV");
			c.innerHTML = selElmnt.options[j].innerHTML;
			if (selElmnt.selectedIndex == j) {
				c.setAttribute("class", "same-as-selected"); // Добавлено здесь
			}
			c.addEventListener("click", function(e) {
				var y, i, k, s, h, sl, yl;
				s = this.parentNode.parentNode.getElementsByTagName("select")[0];
				sl = s.length;
				h = this.parentNode.previousSibling;
				for (i = 0; i < sl; i++) {
					if (s.options[i].innerHTML == this.innerHTML) {
						s.selectedIndex = i;
						h.innerHTML = this.innerHTML;
						y = this.parentNode.getElementsByClassName("same-as-selected");
						yl = y.length;
						for (k = 0; k < yl; k++) {
							y[k].removeAttribute("class");
						}
						this.setAttribute("class", "same-as-selected");
						break;
					}
				}
				h.click();
			});
			b.appendChild(c);
		}
		x[i].appendChild(b);
		a.addEventListener("click", function(e) {
			e.stopPropagation();
			closeAllSelect(this);
			this.nextSibling.classList.toggle("select-hide");
			this.classList.toggle("select-arrow-active");
		});
	}

	function closeAllSelect(elmnt) {
		var x, y, i, xl, yl, arrNo = [];
		x = document.getElementsByClassName("select-items");
		y = document.getElementsByClassName("select-selected");
		xl = x.length;
		yl = y.length;
		for (i = 0; i < yl; i++) {
			if (elmnt == y[i]) {
				arrNo.push(i)
			} else {
				y[i].classList.remove("select-arrow-active");
			}
		}
		for (i = 0; i < xl; i++) {
			if (arrNo.indexOf(i)) {
				x[i].classList.add("select-hide");
			}
		}
	}
	document.addEventListener("click", closeAllSelect);
</script>
<?
include($_SERVER['DOCUMENT_ROOT'] . '/install/core/head.php');
echo '<title>Ошибка</title>';
$act = isset($_GET['act']) ? $_GET['act'] : null;
	switch ($act) {
		case 'nonzip':
		echo '<div class="content">';
		echo '<div class="menu">';
		echo '<div class="title">Обновление не найдено</div>';
		echo '<div class="pod_title">
		Загрузите пакет обновления в /install/update/up
		</div>';
		echo '<a class="button" href="' . homeLink() . '/install/update">Вернуться назад</a>';
		echo '</div>';
		echo '</div>';
		break;
		case 'success':
		echo '<div class="content">';
        echo '<div class="menu">';
        echo '<div class="title">Успешно!</div>';
        echo '<div class="pod_title">
		Поздравляем с успешным окончанием обновления LetsFor!<br />
		</div>';
        echo '<a class="button" href="' . homeLink() . '">На главную</a>';
        echo '</div>';
        echo '</div>';
		break;
	}
include($_SERVER['DOCUMENT_ROOT'] . '/install/core/footer.php');
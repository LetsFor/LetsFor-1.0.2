<?
require_once ($_SERVER['DOCUMENT_ROOT'].'/root-dir/root-dir-head.php');


$code = isset($_GET['code']) ? $_GET['code'] : null;

echo '<title>Error '.$code.'</title>';

echo '<style>
.menu {
	padding: 15px;
}
@media all and (min-width: 800px){ 
#sidebar {
	display: none;
}
}
</style>';

switch($code) {
case '400':
echo '<div class="browserr">';

echo '<center>';
echo '<div class="menu" style="background: none;">';
echo '<div class="ntf">400</div>';
echo '<div class="name_window_err">Отказано в доступе</div>';
echo '<div class="menu_nb">Неверный запрос.</div>';
echo '<div class="menu" style="border-bottom: 0px; padding: 4px 0 5px; background: none;"><a class="button" href="'.homeLink().'">На главную</a></div>';
echo '</div>';
echo '</center>';

echo '<center>';
echo '<div class="menu" style="background: none;">';
echo '<div class="name_window_err">Найти страницу самостоятельно</div>';
echo '<div class="menu_nb">Воспользуйтесь кнопкой ниже чтобы перейти на форму поиска.</div>';
echo '<div class="menu" style="border-bottom: 0px; padding: 4px 0 5px; background: none;"><a class="button" href="'.homeLink().'/search">Поиск</a></div>';
echo '</div>';
echo '</center>';

echo '<center>';
echo '<div class="menu_nb">';
echo '<div class="name_window_err">Дополнительные действия</div>';
echo '<div class="menu" style="background: none; padding: 10px; border-radius: 9px; border-bottom: 0px;">Проверьте правильность написания ссылки в адресной строке.</div>';
echo '</div>';
echo '</center>';

echo '</div>';
break;


case '403':
echo '<div class="browserr">';

echo '<center>';
echo '<div class="menu" style="background: none;">';
echo '<div class="ntf">403</div>';
echo '<div class="name_window_err">Отказано в доступе</div>';
echo '<div class="menu_nb">Переход на данную страницу запрещен.</div>';
echo '<div class="menu" style="border-bottom: 0px; padding: 4px 0 5px; background: none;"><a class="button" href="'.homeLink().'">На главную</a></div>';
echo '</div>';
echo '</center>';

echo '<center>';
echo '<div class="menu" style="background: none;">';
echo '<div class="name_window_err">Найти страницу самостоятельно</div>';
echo '<div class="menu_nb">Воспользуйтесь кнопкой ниже чтобы перейти на форму поиска.</div>';
echo '<div class="menu" style="border-bottom: 0px; padding: 4px 0 5px; background: none;"><a class="button" href="'.homeLink().'/search">Поиск</a></div>';
echo '</div>';
echo '</center>';

echo '<center>';
echo '<div class="menu_nb">';
echo '<div class="name_window_err">Дополнительные действия</div>';
echo '<div class="menu" style="background: none; padding: 10px; border-radius: 9px; border-bottom: 0px;">Проверьте правильность написания ссылки в адресной строке.</div>';
echo '</div>';
echo '</center>';

echo '</div>';
break;


case '404':
echo '<div class="browserr">';

echo '<center>';
echo '<div class="menu" style="background: none;">';
echo '<div class="ntf">404</div>';
echo '<div class="name_window_err">Страница не найдена</div>';
echo '<div class="menu_nb">Страница на которую вы пытаетесь попасть не существует или была удалена.</div>';
echo '<div class="menu" style="border-bottom: 0px; padding: 4px 0 5px; background: none;"><a class="button" href="'.homeLink().'">На главную</a></div>';
echo '</div>';
echo '</center>';

echo '<center>';
echo '<div class="menu" style="background: none;">';
echo '<div class="name_window_err">Найти страницу самостоятельно</div>';
echo '<div class="menu_nb">Воспользуйтесь кнопкой ниже чтобы перейти на форму поиска.</div>';
echo '<div class="menu" style="border-bottom: 0px; padding: 4px 0 5px; background: none;"><a class="button" href="'.homeLink().'/search">Поиск</a></div>';
echo '</div>';
echo '</center>';

echo '<center>';
echo '<div class="menu_nb">';
echo '<div class="name_window_err">Дополнительные действия</div>';
echo '<div class="menu" style="background: none; padding: 10px; border-radius: 9px; border-bottom: 0px;">Проверьте правильность написания ссылки в адресной строке.</div>';
echo '</div>';
echo '</center>';

echo '</div>';
break;


case '405':
echo '<div class="browserr">';

echo '<center>';
echo '<div class="menu" style="background: none;">';
echo '<div class="ntf">405</div>';
echo '<div class="name_window_err">Отказано в доступе</div>';
echo '<div class="menu_nb">Метод запроса не поддерживается.</div>';
echo '<div class="menu" style="border-bottom: 0px; padding: 4px 0 5px; background: none;"><a class="button" href="'.homeLink().'">На главную</a></div>';
echo '</div>';
echo '</center>';

echo '<center>';
echo '<div class="menu" style="background: none;">';
echo '<div class="name_window_err">Найти страницу самостоятельно</div>';
echo '<div class="menu_nb">Воспользуйтесь кнопкой ниже чтобы перейти на форму поиска.</div>';
echo '<div class="menu" style="border-bottom: 0px; padding: 4px 0 5px; background: none;"><a class="button" href="'.homeLink().'/search">Поиск</a></div>';
echo '</div>';
echo '</center>';

echo '<center>';
echo '<div class="menu_nb">';
echo '<div class="name_window_err">Дополнительные действия</div>';
echo '<div class="menu" style="background: none; padding: 10px; border-radius: 9px; border-bottom: 0px;">Проверьте правильность написания ссылки в адресной строке.</div>';
echo '</div>';
echo '</center>';

echo '</div>';
break;


case '408':
echo '<div class="browserr">';

echo '<center>';
echo '<div class="menu" style="background: none;">';
echo '<div class="ntf">408</div>';
echo '<div class="name_window_err">Отказано в доступе</div>';
echo '<div class="menu_nb">Сервер разорвал соединение с клиентом.</div>';
echo '<div class="menu" style="border-bottom: 0px; padding: 4px 0 5px; background: none;"><a class="button" href="'.homeLink().'">На главную</a></div>';
echo '</div>';
echo '</center>';

echo '<center>';
echo '<div class="menu" style="background: none;">';
echo '<div class="name_window_err">Найти страницу самостоятельно</div>';
echo '<div class="menu_nb">Воспользуйтесь кнопкой ниже чтобы перейти на форму поиска.</div>';
echo '<div class="menu" style="border-bottom: 0px; padding: 4px 0 5px; background: none;"><a class="button" href="'.homeLink().'/search">Поиск</a></div>';
echo '</div>';
echo '</center>';

echo '<center>';
echo '<div class="menu_nb">';
echo '<div class="name_window_err">Дополнительные действия</div>';
echo '<div class="menu" style="background: none; padding: 10px; border-radius: 9px; border-bottom: 0px;">Проверить соединение с интернетом.</div>';
echo '</div>';
echo '</center>';

echo '</div>';
break;


case '410':
echo '<div class="browserr">';

echo '<center>';
echo '<div class="menu" style="background: none;">';
echo '<div class="ntf">410</div>';
echo '<div class="name_window_err">Страница не найдена</div>';
echo '<div class="menu_nb">Страница на которую вы пытаетесь попасть была удалена.</div>';
echo '<div class="menu" style="border-bottom: 0px; padding: 4px 0 5px; background: none;"><a class="button" href="'.homeLink().'">На главную</a></div>';
echo '</div>';
echo '</center>';

echo '<center>';
echo '<div class="menu" style="background: none;">';
echo '<div class="name_window_err">Найти страницу самостоятельно</div>';
echo '<div class="menu_nb">Воспользуйтесь кнопкой ниже чтобы перейти на форму поиска.</div>';
echo '<div class="menu" style="border-bottom: 0px; padding: 4px 0 5px; background: none;"><a class="button" href="'.homeLink().'/search">Поиск</a></div>';
echo '</div>';
echo '</center>';

echo '<center>';
echo '<div class="menu_nb">';
echo '<div class="name_window_err">Дополнительные действия</div>';
echo '<div class="menu" style="background: none; padding: 10px; border-radius: 9px; border-bottom: 0px;">Проверьте правильность написания ссылки в адресной строке.</div>';
echo '</div>';
echo '</center>';

echo '</div>';
break;


case '500':
echo '<div class="browserr">';

echo '<center>';
echo '<div class="menu" style="background: none;">';
echo '<div class="ntf">500</div>';
echo '<div class="name_window_err">Внутренняя ошибка сервера</div>';
echo '<div class="menu_nb">Сервер столкнулся с внутренней ошибкой или неправильной настройкой не смог обработать запрос.</div>';
echo '<div class="menu" style="border-bottom: 0px; padding: 4px 0 5px; background: none;"><a class="button" href="'.homeLink().'">На главную</a></div>';
echo '</div>';
echo '</center>';

echo '<center>';
echo '<div class="menu" style="background: none;">';
echo '<div class="name_window_err">Найти другую страницу</div>';
echo '<div class="menu_nb">Воспользуйтесь кнопкой ниже чтобы перейти на форму поиска.</div>';
echo '<div class="menu" style="border-bottom: 0px; padding: 4px 0 5px; background: none;"><a class="button" href="'.homeLink().'/search">Поиск</a></div>';
echo '</div>';
echo '</center>';

echo '<center>';
echo '<div class="menu_nb">';
echo '<div class="name_window_err">Дополнительные действия</div>';
echo '<div class="menu" style="background: none; padding: 10px; border-radius: 9px; border-bottom: 0px;">Проверьте правильность написания ссылки в адресной строке.</div>';
echo '</div>';
echo '</center>';

echo '</div>';
break;


case '502':
echo '<div class="browserr">';

echo '<center>';
echo '<div class="menu" style="background: none;">';
echo '<div class="ntf">502</div>';
echo '<div class="name_window_err">Сервер получает некорректный ответ от вышестоящего сервера</div>';
echo '<div class="menu_nb">Сервер, к которому вы обращаетесь, не смог получить корректный ответ от другого сервера.</div>';
echo '<div class="menu" style="border-bottom: 0px; padding: 4px 0 5px; background: none;"><a class="button" href="'.homeLink().'">На главную</a></div>';
echo '</div>';
echo '</center>';

echo '<center>';
echo '<div class="menu" style="background: none;">';
echo '<div class="name_window_err">Найти другую страницу</div>';
echo '<div class="menu_nb">Воспользуйтесь кнопкой ниже чтобы перейти на форму поиска.</div>';
echo '<div class="menu" style="border-bottom: 0px; padding: 4px 0 5px; background: none;"><a class="button" href="'.homeLink().'/search">Поиск</a></div>';
echo '</div>';
echo '</center>';

echo '<center>';
echo '<div class="menu_nb">';
echo '<div class="name_window_err">Дополнительные действия</div>';
echo '<div class="menu" style="background: none; padding: 10px; border-radius: 9px; border-bottom: 0px;">Проверьте правильность написания ссылки в адресной строке.</div>';
echo '</div>';
echo '</center>';

echo '</div>';
break;


case '504':
echo '<div class="browserr">';

echo '<center>';
echo '<div class="menu" style="background: none;">';
echo '<div class="ntf">504</div>';
echo '<div class="name_window_err">Долгое время ожидания ответа</div>';
echo '<div class="menu_nb">Сервер не получает своевременный ответ от другого сервера.</div>';
echo '<div class="menu" style="border-bottom: 0px; padding: 4px 0 5px; background: none;"><a class="button" href="'.homeLink().'">На главную</a></div>';
echo '</div>';
echo '</center>';

echo '<center>';
echo '<div class="menu" style="background: none;">';
echo '<div class="name_window_err">Найти другую страницу</div>';
echo '<div class="menu_nb">Воспользуйтесь кнопкой ниже чтобы перейти на форму поиска.</div>';
echo '<div class="menu" style="border-bottom: 0px; padding: 4px 0 5px; background: none;"><a class="button" href="'.homeLink().'/search">Поиск</a></div>';
echo '</div>';
echo '</center>';

echo '<center>';
echo '<div class="menu_nb">';
echo '<div class="name_window_err">Дополнительные действия</div>';
echo '<div class="menu" style="background: none; padding: 10px; border-radius: 9px; border-bottom: 0px;">Проверьте правильность написания ссылки в адресной строке.</div>';
echo '</div>';
echo '</center>';

echo '</div>';
break;


case '505':
echo '<div class="browserr">';

echo '<center>';
echo '<div class="menu" style="background: none;">';
echo '<div class="ntf">505</div>';
echo '<div class="name_window_err">Не удалось установить соединение с сервером</div>';
echo '<div class="menu_nb">Не удаëтся установить соединение с сервером, обратитесь к администратору сайта.</div>';
echo '<div class="menu" style="border-bottom: 0px; padding: 4px 0 5px; background: none;"><a class="button" href="'.homeLink().'">На главную</a></div>';
echo '</div>';
echo '</center>';

echo '<center>';
echo '<div class="menu" style="background: none;">';
echo '<div class="name_window_err">Найти другую страницу</div>';
echo '<div class="menu_nb">Воспользуйтесь кнопкой ниже чтобы перейти на форму поиска.</div>';
echo '<div class="menu" style="border-bottom: 0px; padding: 4px 0 5px; background: none;"><a class="button" href="'.homeLink().'/search">Поиск</a></div>';
echo '</div>';
echo '</center>';

echo '<center>';
echo '<div class="menu_nb">';
echo '<div class="name_window_err">Дополнительные действия</div>';
echo '<div class="menu" style="background: none; padding: 10px; border-radius: 9px; border-bottom: 0px;">Проверьте правильность написания ссылки в адресной строке.</div>';
echo '</div>';
echo '</center>';

echo '</div>';
break;

}
require_once ($_SERVER['DOCUMENT_ROOT'].'/root-dir/root-dir-footer.php');
?>
<?php
require_once ($_SERVER['DOCUMENT_ROOT'] . '/LFcore/core.php');
require_once ($_SERVER['DOCUMENT_ROOT'] . '/LFcore/src/site-inf.php');

echo '<div class="home">';
if ($t_f == 0) {
    echo '<h1 style="padding: 5px;">Ничего нет</h1>';
    echo '<div class="stolb_info-num-stat-gt">Создайте тему и начните обсуждение!</div>';
} else {
    echo '<div class="new_tem_forum"><a class="new_tem-link" data-bs-toggle="modal" data-bs-target="#offkat" style="margin-right: 10px;"><i class="fas fa-edit"></i></a>
	<a class="new_tem-link" onclick="window.refreshContent()" style="margin-right: 10px;"><i class="fas fa-refresh"></i></a>
	<a class="new_tem-link" href="' . homeLink() . '/newt"><span class="icon"><i class="fas fa-align-left"></i></span> Новые обсуждения</font></a></div>';
    $qwerad = dbquery("SELECT * FROM `ads` ORDER BY `kogda` DESC LIMIT 6");
    while ($ads = mfa($qwerad)) {
        echo '<a class="link_ad" href="' . $ads['url'] . '"><span class="icon_s_bar" style="margin: 0 5px 0 5px; color: var(--buttons-dark-hover)"><span class="icon_mm"><div class="ico_cen_bar"><i class="fas fa-volume-low"></i></div></span></span>' . $ads['name'] . '</a>';
    }
}

echo '<div class="thems-lenta" id="home-items--len"></div>';
echo '</div>';
?>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const container = document.querySelector('.thems-lenta'); // Контейнер для отображения тем
    const loader = document.querySelector('#loader'); // Лоадер
    const limit = 30; // Лимит записей на одну загрузку
    let offset = 0; // Оффсет для пагинации
    let loading = false; // Флаг загрузки
    let noMoreItems = false; // Флаг окончания данных

    // Функция загрузки данных с сервера
    function loadContent(initialLoad = false) {
        if (loading || noMoreItems) return; // Исключаем повторные запросы

        loading = true;
        loader.style.display = 'block'; // Показываем лоадер

        fetch(`/lenta_more?offset=${offset}&limit=${limit}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`Ошибка сервера: ${response.statusText}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.items && data.items.length > 0) {
                    const html = data.items.map(item => item.html).join('');
                    if (initialLoad) {
                        container.innerHTML = html; // Заменяем содержимое
                    } else {
                        container.innerHTML += html; // Добавляем данные
                    }
                    offset += limit; // Увеличиваем оффсет
                } else {
                    noMoreItems = true; // Если элементов меньше лимита, данных больше нет
                }
                loader.style.display = 'none'; // Скрываем лоадер
                loading = false;
            })
            .catch(error => {
                console.error('Ошибка загрузки:', error.message);
                loader.style.display = 'none';
                loading = false;
            });
    }

    // Функция перезагрузки данных
    function refreshContent() {
        noMoreItems = false; // Сбрасываем флаг окончания данных
        offset = 0; // Сбрасываем оффсет
        container.innerHTML = ''; // Очищаем содержимое контейнера
        loadContent(true); // Загружаем данные с начала
    }

    // Функция для обработки ошибок
    function handleError(error) {
        console.error('Произошла ошибка:', error.message);
        loader.style.display = 'none'; // Скрываем лоадер в случае ошибки
        loading = false;
    }

    // Функция для бесконечной прокрутки
    function handleScroll() {
        const scrollPosition = window.innerHeight + window.scrollY;
        const threshold = document.body.offsetHeight - 100;

        if (scrollPosition >= threshold) {
            loadContent();
        }
    }

    // Начальная загрузка
    loadContent(true);

    // Обработка события прокрутки
    window.addEventListener('scroll', handleScroll);

    // Экспорт функции перезагрузки для внешнего вызова
    window.refreshContent = refreshContent;
});
</script>
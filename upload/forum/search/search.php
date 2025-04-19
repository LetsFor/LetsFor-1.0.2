<?php
require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-head.php');

echo '<title>Поиск по форуму</title>';

if (empty($user['id'])) {
    header('Location: ' . homeLink() . '');
    exit();
}

echo '<div class="menu_nb">
<span class="kat_name"><h1>Поиск по форуму</h1>
<h2 class="kat_opisanie">Введите запрос</h2>
</span></div>';

echo '<div class="menu_t">';
echo '<form method="post" action="" id="searchForm">';
echo '<center><input placeholder="Что ищем?" type="text" name="tema" id="search" style="margin: 0" /></center>';
echo '</form>';
echo '</div>';

echo '<div id="results"></div>';

require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
?>

<script>
    let debounceTimeout;
    let cache = {};

    // Предотвращаем действие по умолчанию при нажатии Enter
    document.getElementById('search').addEventListener('keydown', function(event) {
        if (event.key === 'Enter') {
            event.preventDefault();
        }
    });

    document.getElementById('search').addEventListener('input', function() {
        clearTimeout(debounceTimeout);
        let searchTerm = this.value;

        if (cache[searchTerm]) {
            document.getElementById('results').innerHTML = cache[searchTerm];
            return;
        }

        if (searchTerm.length > 0) {
            debounceTimeout = setTimeout(function() {
                document.getElementById('loading').style.display = 'block';
                var xhr = new XMLHttpRequest();
                xhr.open('POST', '/forum/search/result.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onreadystatechange = function() {
                    if (xhr.readyState == 4 && xhr.status == 200) {
                        document.getElementById('loading').style.display = 'none';

                        // Создаем временный элемент для парсинга HTML
                        const tempDiv = document.createElement('div');
                        tempDiv.innerHTML = xhr.responseText;

                        // Удаляем все CSS-ссылки из загружаемого контента
                        const cssLinks = tempDiv.querySelectorAll('link[rel="stylesheet"]');
                        cssLinks.forEach(link => link.remove());

                        // Обновляем содержимое элемента #results
                        document.getElementById('results').innerHTML = tempDiv.innerHTML;
                        cache[searchTerm] = tempDiv.innerHTML;
                    }
                };
                xhr.send('tema=' + encodeURIComponent(searchTerm));
            }, 300); // 300 мс задержка
        } else {
            document.getElementById('results').innerHTML = '';
        }
    });
</script>
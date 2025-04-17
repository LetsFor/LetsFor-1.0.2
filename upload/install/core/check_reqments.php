<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/install/core/head.php');

echo '<div class="content">
        <div class="menu" style="display: flex; align-items: center;">';

echo '<div class="loader" id="loader" style="margin-right: 13px;">';
require_once($_SERVER['DOCUMENT_ROOT'] . '/LFcore/src/loader.php');
echo '</div>';

echo '<div class="info-block">
        <div class="title">Пожалуйста подождите</div>
        <div class="pod_title">Установка...</div>
      </div>
      </div>
      </div>';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $host     = trim($_POST['host']);
    $database = trim($_POST['database']);
    $user     = trim($_POST['user']);
    $password = trim($_POST['password']);

    // Инициализируем базу данных через адаптер
    DataADP::init($host, $database, $user, $password, [PDO::ATTR_EMULATE_PREPARES => false]);
    $instance = DataADP::getInstance();

    $dumpFile   = $_SERVER['DOCUMENT_ROOT'] . '/install/db/db.sql';
    $dumpContent = file_get_contents($dumpFile);
    $queries    = explode(';', $dumpContent);
    $errorMessages = [];

    foreach ($queries as $query) {
        $query = trim($query);
        if ($query !== '') {
            // Если выполнение запроса возвращает false, регистрируем ошибку
            if ($instance->dbquery($query) === false) {
                $errorMessages[] = 'Ошибка выполнения запроса: ' . $query;
            }
        }
    }

    if (!empty($errorMessages)) {
        echo '<div class="content">
                <div class="menu">
                    <div class="title">Установка невозможна</div><br />
                    <div class="pod_title">Очистите базу данных для продолжения установки</div>
                    <a class="button" href="' . homeLink() . '/install">Вернуться назад</a>
                </div>
              </div>';
        echo '<div class="content" id="result">';
        foreach ($errorMessages as $error) {
            echo '<div class="err_console">' . $error . '</div>';
        }
        echo '</div>';
        exit;
    }

    $configContent  = "<?php\n";
    $configContent .= "\$config = [\n";
    $configContent .= "    'host' => '$host',\n";
    $configContent .= "    'user' => '$user',\n";
    $configContent .= "    'pass' => '$password',\n";
    $configContent .= "    'base' => '$database'\n";
    $configContent .= "];\n";
    $configContent .= "?>";

    file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/LFcore/config.php', $configContent);
}

require_once($_SERVER['DOCUMENT_ROOT'] . '/install/core/footer.php');
?>

<script>
// Показать лоадер
function showLoader() {
    document.getElementById('loader').style.display = 'block';
}

// Скрыть лоадер
function hideLoader() {
    document.getElementById('loader').style.display = 'none';
}

// Создаем новый XMLHttpRequest объект
var xhr = new XMLHttpRequest();

// Настраиваем его: POST-запрос на URL
xhr.open('POST', '/install/core/check_reqments.php', true);

// Устанавливаем заголовки запроса
xhr.setRequestHeader('Content-Type', 'application/json');

// Функция обработки состояния запроса
xhr.onreadystatechange = function () {
    if (xhr.readyState === 4) { // Если запрос завершен
        try {
            if (xhr.status === 200) { // Если запрос выполнен успешно
                setTimeout(function () {
                    hideLoader();
                    window.location.href = '/install/intermediate.php';
                }, 3000);
            } else {
                hideLoader();
                throw new Error('Request failed');
            }
        } catch (error) {
            console.error('Ошибка:', error);
        }
    }
};

// Отправляем запрос с данными и показываем лоадер
var data = JSON.stringify({ key: 'value' });
showLoader();
xhr.send(data);
</script>
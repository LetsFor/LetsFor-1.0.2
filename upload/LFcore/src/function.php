<?
$css = new LFstyleGenerate;
$seo = new SeoItems;
$view = new views;
$prus = new displayUserInfo;
$select = new SelectField;

function homeLink()
{
    $home = 'https://' . $_SERVER['HTTP_HOST'];
    return $home;
}

function LFS($msg)
{
    $msg = trim($msg);
    $msg = htmlspecialchars($msg, ENT_QUOTES, 'UTF-8');
    return $msg;
}

class DataADP
{
    private static $instance = null;
    private $pdo;

    public function __construct($host, $dbname, $username, $password, $options = [])
    {
        try {
            $dsn = "mysql:host=$host;dbname=$dbname";
            $defaultOptions = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Режим обработки ошибок
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Режим выборки
                PDO::ATTR_EMULATE_PREPARES => false, // Отключение эмуляции подготовленных запросов
            ];
            $options = array_replace($defaultOptions, $options); // Слияние параметров
            $this->pdo = new PDO($dsn, $username, $password, $options);
        } catch (PDOException $e) {
            error_log("Ошибка соединения с БД: " . $e->getMessage());
            $this->pdo = null; // Помечаем соединение как неактивное
        }
    }

    public static function init($host, $dbname, $username, $password, $options = [])
    {
        if (self::$instance === null) {
             self::$instance = new self($host, $dbname, $username, $password, $options);
        }
    }

    public static function getInstance()
    {
        return self::$instance;
    }

    public function isInitialized()
    {
        return isset($this->pdo);
    }

    public function dbquery($query, $params = [])
    {
        if (!$this->isInitialized()) {
            return false; // Пропускаем запрос
        }

        try {
            $stmt = $this->pdo->prepare($query);
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            error_log("Ошибка запроса: " . $e->getMessage());
            return false;
        }
    }

    public function msdb($dbname)
    {
        if (!$this->isInitialized()) {
            return false; // Пропускаем запрос
        }

        try {
            $this->pdo->exec("USE $dbname");
            return true;
        } catch (PDOException $e) {
            error_log("Ошибка выбора базы данных: " . $e->getMessage());
            return false;
        }
    }

    public function mfa($stmt)
    {
        if ($stmt === false) {
            return null; // Пропуск
        }

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    public function mfar($stmt)
    {
        if ($stmt === false) {
            return null; // Пропуск
        }

        $result = $stmt->fetch(PDO::FETCH_BOTH);
        return $result ?: null;
    }

    public function msres($stmt, $row = 0, $field = 0)
    {
        if ($stmt === false) {
            return null; // Пропуск
        }

        $result = $stmt->fetch(PDO::FETCH_BOTH);
        return ($result && isset($result[$field])) ? $result[$field] : null;
    }

    public function msnumrows($stmt)
    {
        if ($stmt === false) {
            return 0; // Пропуск
        }

        return $stmt->rowCount();
    }

    public function getLastInstId()
    {
        if (!$this->isInitialized()) {
            return false; // Пропуск
        }

        try {
            return $this->pdo->lastInsertId();
        } catch (PDOException $e) {
            error_log("Ошибка получения последнего ID: " . $e->getMessage());
            return false;
        }
    }
}

// Глобальные функции
function dbquery($query, $params = [])
{
    $instance = DataADP::getInstance();
    if ($instance === null || !$instance->isInitialized()) {
        return false; // Пропуск
    }

    return $instance->dbquery($query, $params);
}

function msdb($dbname)
{
    $instance = DataADP::getInstance();
    if ($instance === null || !$instance->isInitialized()) {
        return false; // Пропуск
    }

    return $instance->msdb($dbname);
}

function mfa($stmt)
{
    $instance = DataADP::getInstance();
    if ($instance === null || !$instance->isInitialized()) {
        return null; // Пропуск
    }

    return $instance->mfa($stmt);
}

function mfar($stmt)
{
    $instance = DataADP::getInstance();
    if ($instance === null || !$instance->isInitialized()) {
        return null; // Пропуск
    }

    return $instance->mfar($stmt);
}

function msres($stmt, $row = 0, $field = 0)
{
    $instance = DataADP::getInstance();
    if ($instance === null || !$instance->isInitialized()) {
        return null; // Пропуск
    }

    return $instance->msres($stmt, $row, $field);
}

function msnumrows($stmt)
{
    $instance = DataADP::getInstance();
    if ($instance === null || !$instance->isInitialized()) {
        return 0; // Пропуск
    }

    return $instance->msnumrows($stmt);
}

function getLastInstId()
{
    $instance = DataADP::getInstance();
    if ($instance === null || !$instance->isInitialized()) {
        return false; // Пропуск
    }

    return $instance->getLastInstId();
}

function getMySQLVersion()
{
    $output = shell_exec('mysql -V');
    preg_match('@[0-9]+\.[0-9]+\.[0-9]+@', $output, $version);
    return $version[0];
}

function checkCookie() {
    // Проверяем наличие куков
    if (empty($_COOKIE['uslog']) || empty($_COOKIE['uspass'])) {
        return null; // Возвращаем пустой результат, если куки отсутствуют
    }

    // Приводим значения куков к безопасному формату
    $uslog = LFS($_COOKIE['uslog']);
    $uspass = LFS($_COOKIE['uspass']);

    // Получаем пользователя из базы данных
    $dbs = dbquery(
        "SELECT * FROM users WHERE login = :uslog AND pass = :uspass LIMIT 1",
        [
            ':uslog' => $uslog,
            ':uspass' => $uspass
        ]
    );

    // Возвращаем результат запроса
    return mfa($dbs);
}

///////////////////////////////
///////////  Ошибка //////////
/////////////////////////////

function err($err = NULL)
{
    if (!$err) {
        $m = '<div class="errors"><div class="menu_cont"><div class="err">Ошибка</div></div></div>';
    } else {
        $m = '<div class="error_banner">Упс...</div><div class="menu_cont"><div class="block--info">' . $err . '</div></div>';
    }
    return $m;
}

///////////////////////////////
//////// Размер файла ////////
/////////////////////////////

function fsize($file)
{
    if (!file_exists($file)) return "Файл не найден";
    $filesize = filesize($file);
    $size = array('б', 'Кб', 'Мб', 'Гб');
    if ($filesize > pow(1024, 3)) {
        $n = 3;
    } elseif ($filesize > pow(1024, 2)) {
        $n = 2;
    } elseif ($filesize > 1024) {
        $n = 1;
    } else {
        $n = 0;
    }
    $filesize = ($filesize / pow(1024, $n));
    $filesize = round($filesize, 1);
    return $filesize . ' ' . $size[$n];
}

///////////////////////////////
/////////// BB Коды //////////
/////////////////////////////

function bb($mes)
{
    $mes = stripslashes($mes);
    $mes = preg_replace('#\[IMG\](.*?)\[/IMG\]#si', '<a data-fancybox="gallery" href="$1" data-caption="image_$1"><img class="bbimg" src="$1" alt="[IMG]" title="[IMG]"></a>', $mes);
    $mes = preg_replace('#\[VIDEO\](.*?)\[/VIDEO\]#si', '<video style="margin: 10px 0; border-radius: var(--all-border-radius);" controls width="80%" max-height="50%" loop muted><source src="$1" type="video/mp4"></video>', $mes);
    $mes = preg_replace('#\[QUOTE\](.*?)\[/QUOTE\]#si', '<div class="cit">$1</div>', $mes);
    $mes = preg_replace('#\[B\](.*?)\[/B\]#si', '<span style="font-weight: bold;">$1</span>', $mes);
    $mes = preg_replace('#\[CENTER\](.*?)\[/CENTER\]#si', '<div style="text-align: center;">$1</div>', $mes);
    $mes = preg_replace('#\[LEFT\](.*?)\[/LEFT\]#si', '<div style="text-align: left;">$1</div>', $mes);
    $mes = preg_replace('#\[RIGHT\](.*?)\[/RIGHT\]#si', '<div style="text-align: right;">$1</div>', $mes);
    $mes = preg_replace('#\[I\](.*?)\[/I\]#si', '<i>$1</i>', $mes);
    $mes = preg_replace('#\[U\](.*?)\[/U\]#si', '<u>$1</u>', $mes);
    $mes = preg_replace('#\[S\](.*?)\[/S\]#si', '<s>$1</s>', $mes);

    $mes = preg_replace('/\[SIZE\s*=\s*([\'"]?)(.*?)\1\](.*?)\[\/SIZE\]/si', '<span style="font-size: $2px">$3</span>', $mes);
    $mes = preg_replace('/\[spoiler\s*=\s*([\'"]?)(.*?)\1\](.*?)\[\/spoiler\]/si', '<div class="spoiler-block" style="margin: 7px 0px 0px 0px;"><a class="spoiler-title"><span class="sp_icon"><i class="fas fa-circle-info"></i></span><div class="sp_name">$2</div></a><div class="spoiler-content">$3</div></div>', $mes);
    $mes = preg_replace('/\[COLOR\s*=\s*([\'"]?)(.*?)\1\](.*?)\[\/COLOR\]/si', '<span style="color:$2">$3</span>', $mes);
    $mes = preg_replace('/\[URL\s*=\s*([\'"]?)(.*?)\1\](.*?)\[\/URL\]/si', '<a href="$2" target="_blank" rel="nofollow">$3</a>', $mes);
	$mes = preg_replace('/\[CODE\s*=\s*([\'"]?)(.*?)\1\](.*?)\[\/CODE\]/si', '<div class="code_bb_item"><b>$2:<hr style="background: var(--font-color)"></b><div id="code-container"><pre class="line-numbers"><code class="language-$2">$3</code></pre></div></div>', $mes);

    return $mes;
}

///////////////////////////////
/////////// Листинг //////////
/////////////////////////////

function page($k_page = 1)
{
    $page = 1;
    $page = LFS($page);
    $k_page = LFS($k_page);
    if (isset($_GET['selection'])) {
        if ($_GET['selection'] == 'top')
            $page = LFS(intval($k_page));
        elseif (is_numeric($_GET['selection']))
            $page = LFS(intval($_GET['selection']));
    }
    if ($page < 1) $page = 1;
    if ($page > $k_page) $page = $k_page;
    return $page;
}

// Определяем кол-во страниц
function k_page($k_post = 0, $k_p_str = 10)
{
    if ($k_post != 0) {
        $v_pages = ceil($k_post / $k_p_str);
        return $v_pages;
    } else return 1;
}

function str($link = '?', $k_page = 1, $page = 1)
{
    if ($page < 1) $page = 1;
    $page = LFS($page);
    $k_page = LFS($k_page);

    echo '<div class="mst">';

    if ($page != 1)
        echo '<a class="page2" href="' . $link . 'selection=1">1</a>';
    else
        echo '<span class="page">1</span>';

    for ($ot = -2; $ot <= 2; $ot++) {
        if ($page + $ot > 1 && $page + $ot < $k_page) {
            if ($ot == -2 && $page + $ot > 2) {
                echo '<a class="page2" href="' . $link . 'selection=' . ($page - 1) . '">❮</a>';
            }
            if ($ot != 0)
                echo '<a class="page2" href="' . $link . 'selection=' . ($page + $ot) . '">' . ($page + $ot) . '</a>';
            else
                echo '<span class="page">' . ($page + $ot) . '</span>';
            if ($ot == 2 && $page + $ot < $k_page - 1) {
                echo '<a class="page2" href="' . $link . 'selection=' . ($page + 1) . '">❯</a>';
            }
        }
    }


    // Отображаем ссылку на последнюю страницу
    if ($page != $k_page)
        echo '<a class="page2" href="' . $link . 'selection=top">' . $k_page . '</a>';
    elseif ($k_page > 1)
        echo '<span class="page">' . $k_page . '</span>';

    echo '</div>';
}


///////////////////////////////
//////////// Время ///////////
/////////////////////////////

function vremja($time = NULL)
{
    if (!$time) $time = time();
    $data = date('j.n.y', $time);
    if ($data == date('j.n.y')) $res = 'Сегодня в ' . date('G:i', $time);
    elseif ($data == date('j.n.y', time() - 86400)) $res = 'Вчера в ' . date('G:i', $time);
    elseif ($data == date('j.n.y', time() - 172800)) $res = 'Позавчера в ' . date('G:i', $time);
    else {
        $m = array('0', 'Янв', 'Фев', 'Мар', 'Апр', 'Май', 'Июн', 'Июл', 'Авг', 'Сен', 'Окт', 'Ноя', 'Дек');
        $res = date('j ' . $m[date('n', $time)] . ' Y в G:i', $time);
        $res = str_replace(date('Y'), '', $res);
    }
    return $res;
}

///////////////////////////////
//////////// Смайлы //////////
/////////////////////////////

function syncSmileFoldersWithDatabase()
{
    global $pdo;
    $directory = $_SERVER['DOCUMENT_ROOT'] . '/files/smile/';
    $folders = glob($directory . '*');
    $folderNames = array();

    foreach ($folders as $folder) {
        if (is_dir($folder)) {
            $folderNames[] = basename($folder);
        }
    }

    // Проверяем и добавляем новые папки в базу данных
    foreach ($folderNames as $folderName) {
        $result = dbquery("SELECT * FROM smile_p WHERE name = '" . $folderName . "'");
        if (msnumrows($result) == 0) {
            // Если папка не найдена в базе данных, добавляем её
            dbquery("INSERT INTO smile_p (name) VALUES ('" . $folderName . "')");
        }
    }

    // Проверяем и удаляем отсутствующие папки из базы данных
    $result = dbquery("SELECT name FROM smile_p");

    while ($row = mfa($result)) {
        if (!in_array($row['name'], $folderNames)) {
            dbquery("DELETE FROM smile_p WHERE name = '" . $row['name'] . "'");
        }
    }

    // Проверка и добавление новых файлов в базу данных
    foreach ($folderNames as $folderName) {
        $files = glob($directory . $folderName . '/*'); // Получение всех файлов в папке
        foreach ($files as $file) {
            if (is_file($file)) {
                $fileName = basename($file);
                $result = dbquery("SELECT * FROM smile WHERE icon = '" . $fileName . "'");
                if (msnumrows($result) == 0) {
                    dbquery("INSERT INTO smile (name, icon, papka) VALUES ('sm[" . rand(123, 456) . "]', '" . $fileName . "', (SELECT id FROM smile_p WHERE name = '" . $folderName . "'))");
                }
            }
        }
    }

    // Проверка и удаление файлов из базы данных, если они не найдены в папке
    $result_icon = dbquery("SELECT icon, papka FROM smile");

    while ($row_icon = mfa($result_icon)) {
        $row_papka = mfa(dbquery("SELECT name FROM smile_p WHERE id = '" . $row_icon['papka'] . "'"));
        $dirsmile = $_SERVER['DOCUMENT_ROOT'] . '/files/smile/' . $row_papka['name'] . '/';
        $filepath = $dirsmile . $row_icon['icon'];
        if (!file_exists($filepath)) {
            dbquery("DELETE FROM smile WHERE icon = '" . $row_icon['icon'] . "'");
        }
    }
}

function smile($msg)
{
    global $HOME;
    $msg = trim($msg);
    $s = dbquery("SELECT * FROM smile ORDER BY id DESC");
    while ($smile = mfar($s)) {
        // Получение имени папки для текущего смайла
        $row_papka = mfa(dbquery("SELECT name FROM smile_p WHERE id = '" . $smile['papka'] . "'"));

        // Формируем полный путь до файла смайла
        $iconPath = homeLink() . '/files/smile/' . $row_papka['name'] . '/' . $smile['icon'];

        // Заменяем текстовый смайл на изображение
        $msg = str_replace($smile['name'], ' <img style="padding: 5px;" src="' . $iconPath . '" alt="' . $smile['name'] . '"/> ', $msg);
    }
    return $msg;
}


////////////////////////////////
//// Определение iP адреса ////
//////////////////////////////

function get_ip()
{
    $value = '';
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $value = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $value = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } elseif (!empty($_SERVER['REMOTE_ADDR'])) {
        $value = $_SERVER['REMOTE_ADDR'];
    }

    return $value;
}

function showAlert($message, $type = 'info', $duration = 3000)
{
    echo "<script>
    document.addEventListener('DOMContentLoaded', function() {
        const alertContainer = document.getElementById('alertContainer') || document.body;

        // Функция для показа алерта
        function showCustomAlert(message, type, duration) {
            const alert = document.createElement('div');
            alert.className = 'alert ' + type;
            alert.innerText = message;

            alertContainer.appendChild(alert);

            setTimeout(() => alert.classList.add('show'), 10);

            setTimeout(() => {
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 600);
            }, duration);
        }

        // Проверяем наличие флага перезагрузки через performance.navigation
        if (performance.navigation.type === 1) { // 1 означает перезагрузку страницы
            showCustomAlert('$message', '$type', $duration);
        } else {
            // Если перезагрузки не было, показываем алерт немедленно
            showCustomAlert('$message', '$type', $duration);
        }
    });
    </script>";
}

function PassCryptor($passcrypt)
{
    $crypt = md5(md5(md5($passcrypt)));
    return $crypt;
}

function deleteDirectory($dir)
{
    if (!is_dir($dir)) {
        return false;
    }

    $items = array_diff(scandir($dir), ['.', '..']);
    foreach ($items as $item) {
        $path = $dir . '/' . $item;
        if (is_dir($path)) {
            deleteDirectory($path); // Рекурсивный вызов для подкатегорий
        } else {
            unlink($path); // Удаление файла
        }
    }

    return rmdir($dir); // Удаление пустой папки
}

function CoreLogo()
{
    $logo = '<span class="logotip"></span>';
    return $logo;
}

class LFstyleGenerate
{
	public function syncFoldersWithDatabase()
	{
		global $pdo;
		$directory = $_SERVER['DOCUMENT_ROOT'] . '/design/style/';
		$folders = glob($directory . '*');
		$folderNames = array();
		
		foreach ($folders as $folder) {
			if (is_dir($folder)) {
				$folderNames[] = basename($folder);
			}
		}
		
		// Проверяем и добавляем новые папки в базу данных
		foreach ($folderNames as $folderName) {
			$sql = "SELECT * FROM `themes` WHERE `folder` = '" . $folderName . "'";
			$result = dbquery($sql);
			if (msnumrows($result) == 0) {
				// Если папка не найдена в базе данных, добавляем её
				dbquery("INSERT INTO `themes` (folder) VALUES ('" . $folderName . "')");
			}
		}
		
		// Проверяем и удаляем отсутствующие папки из базы данных
		$sql = "SELECT folder FROM themes";
		$result = dbquery($sql);
		
		while ($row = mfa($result)) {
			if (!in_array($row['folder'], $folderNames)) {
				$sql = "DELETE FROM `themes` WHERE `folder` = '" . $row['folder'] . "'";
				dbquery($sql);
			}
		}
	}
	
	public function getFolderFromDatabase()
	{
		$index = mfa(dbquery("SELECT * FROM `settings` WHERE `id` = '1'"));
		$row = mfa(dbquery("SELECT * FROM `themes` WHERE `folder` = '" . $index['style'] . "'"));
		
		if (isset($row['folder'])) {
			$folder = $row['folder'];
		} else {
			$folder = "default"; // Значение по умолчанию
		}
		return $folder;
	}
	
	public function LFstyleLink()
	{
		// Сначала синхронизируем папки с базой данных
		$this->syncFoldersWithDatabase();
		global $pdo;
		
		$folder = $this->getFolderFromDatabase();
		
		// Получаем список файлов CSS из указанной папки
		$directory = $_SERVER['DOCUMENT_ROOT'] . '/design/style/' . $folder . '/';
		$cssFiles = glob($directory . '*.css');
		$cssFileNames = array_map('basename', $cssFiles);
		$cssFileString = implode(',', $cssFileNames);
		
		$parameters = [
			'folder' => $folder,
			'css' => $cssFileString,
			'dir' => 'LTR',
			'd' => time()
		];
		
		$link = '/css.php?' . http_build_query($parameters);
		$flink = str_replace('%2C', ',', $link);
		
		return '<link rel="stylesheet" type="text/css" href="' . $flink . '">';
	}
}

class displayUserInfo
{
	public function displayUserIcon($us) {
		$prv_us_t = mfa(dbquery("SELECT * FROM `user_prevs` WHERE `id` = '" . $us['prev'] . "'"));
		$perm_ank = mfa(dbquery("SELECT * FROM `admin_perm` WHERE `id` = '" . $us['level_us'] . "'"));
		
		// Если уровень не равен 1 и prev пустой
		if ($us['level_us'] != 1 && empty($us['prev'])) {
			echo '<span class="icon_for_us" style="' . html_entity_decode($perm_ank['color_prefix'], ENT_QUOTES, 'UTF-8') . '">' . html_entity_decode($perm_ank['icon'], ENT_QUOTES, 'UTF-8') . '</span>';
		} elseif (isset($us['prev']) && $us['level_us'] == 1) {
			// Если prev установлен и уровень равен 1
			$color = empty($us['color_prev']) ? $prv_us_t['color_icon_prev'] : $us['color_prev'];
			$icon = empty($us['icon_prev']) ? $prv_us_t['icon_prev'] : $us['icon_prev'];
				
			echo '<span class="icon_for_us" style="' . html_entity_decode($color, ENT_QUOTES, 'UTF-8') . '">';
			echo html_entity_decode($icon, ENT_QUOTES, 'UTF-8');
			echo '</span>';
		} elseif ($us['level_us'] != 1 && isset($us['prev'])) {
			// Если уровень не равен 1 и prev установлен
			$color = empty($us['color_prev']) ? $prv_us_t['color_icon_prev'] : $us['color_prev'];
			$icon = empty($us['icon_prev']) ? $prv_us_t['icon_prev'] : $us['icon_prev'];
				
			echo '<span class="icon_for_us" style="' . html_entity_decode($color, ENT_QUOTES, 'UTF-8') . '">';
			echo html_entity_decode($icon, ENT_QUOTES, 'UTF-8');
			echo '</span>';
			echo '<span class="icon_for_us" style="' . html_entity_decode($perm_ank['color_prefix'], ENT_QUOTES, 'UTF-8') . '; margin-left: -5px !important; position: relative">' . html_entity_decode($perm_ank['icon'], ENT_QUOTES, 'UTF-8') . '</span>';
		} elseif ($us['level_us'] == 1 && $us['prev'] == 0) {
			// Если уровень равен 1 и prev равен 0
			echo '<style>.icon_for_us { display: none !important }</style>';
		}
	}
	
	public function displayPrefixForUser($us) {
		$prv_us_t = mfa(dbquery("SELECT * FROM `user_prevs` WHERE `id` = '" . $us['prev'] . "'"));
		$perm_ank = mfa(dbquery("SELECT * FROM `admin_perm` WHERE `id` = '" . $us['level_us'] . "'"));
		
		if ($perm_ank['id'] != 1) {
			echo '<center><span class="prefix_for_us" style="' . $perm_ank['color_prefix'] . '">' . $perm_ank['name'] . '</span></center>';
		} else {
			if (empty($us['prev'])) {
				echo '<span></span>';
			} else {
				if (empty($us['color_prev'])) {
					echo '<center><span class="prefix_for_us" style="' . $prv_us_t['color_icon_prev'] . '">' . $prv_us_t['name'] . '</span></center>';
				} else {
					echo '<center><span class="prefix_for_us" style="' . $us['color_prev'] . '">' . $prv_us_t['name'] . '</span></center>';
				}
			}
		}
	}
}

class views
{
	public function handleForumViews($id) {
		$forum = mfa(dbquery("SELECT * FROM `forum_tema` WHERE `id` = '" . $id . "'"));
		// Локальная функция для фильтрации ввода
		$ip_viev = $_SERVER['REMOTE_ADDR'];
		// Проверка если IP адрес пользователя присутствует в темах
		$ipCheckUser = mfa(dbquery("SELECT * FROM `tems_vievs_ip` WHERE `tema` = '" . $forum['id'] . "'"));
		// Получение количества просмотров тем
		$vievNumRes = msres(dbquery("SELECT COUNT(*) FROM `tems_vievs_ip` WHERE `tema` = '" . $forum['id'] . "'"));
		// Получение значения user
		$user = checkCookie();
		
		if(isset($user['id'])) {
			// 1. Если IP и ID пользователя совпадают, ставим новую запись
			if ($ipCheckUser['user_v'] == $user['id'] && $ip_viev == $ipCheckUser['ip']) {
				echo '<span></span>';
			} else {
				if ($ipCheckUser['ip'] != $user['ip']) {
					dbquery("INSERT INTO `tems_vievs_ip` SET `user_v` = '" . $user['id'] . "', `tema` = '" . $forum['id'] . "', `ip` = '" . $ip_viev . "'");
				}
			}
			
			// 2. Обновление userID и IP
			if ($user['ip'] == $ipCheckUser['ip']) {
				dbquery("UPDATE `tems_vievs_ip` SET `user_v` = '" . $user['id'] . "' WHERE `ip` = '" . $user['ip'] . "'");
			}
			
			if ($user['id'] == $ipCheckUser['user_v']) {
				dbquery("UPDATE `tems_vievs_ip` SET `ip` = '" . $user['ip'] . "' WHERE `user_v` = '" . $user['id'] . "'");
			}
		}
		// Возвращение и отображение количества просмотров
		return $vievNumRes;
	}
}

function insertMultiple($tablename, $prefixColumn, $themeIdColumn, $themeId) {
    global $prefix_array;

    foreach ($prefix_array as $order => $value) { // Порядок соответствует индексу
        $query = "INSERT INTO $tablename ($prefixColumn, $themeIdColumn) VALUES (:prefix, :theme_id)";
        $params = [
            ':prefix' => $value,
            ':theme_id' => $themeId,
        ];

        $stmt = dbquery($query, $params);
    }
}


function UserAvatar($value, $width, $height)
{
    if (empty($width & $height)) {
        $worh = '';
    } else {
        $worh = 'width: ' . $width . 'px; height: ' . $height . 'px;';
    }
    if (empty($value['avatar'])) {
        $avatar = '<img class="avatar" src="' . homeLink() . '/files/ava/net.png" style="' . $worh . '" />';
    } else {
        $avatar = '<img class="avatar" src="' . homeLink() . '/files/ava/' . $value['avatar'] . '" style="' . $worh . '" />';
    }
    return $avatar;
}

///////////////////////////////
//////// Функция ника ////////
/////////////////////////////

function nickLink($id)
{
    global $HOME;
    $users = mfar(dbquery("SELECT * FROM users WHERE id = '" . $id . "' LIMIT 1"));

    $m_perm = mfa(dbquery("SELECT * FROM admin_perm WHERE id = '" . $users['level_us'] . "'"));
    if ($m_perm['id'] < 2) {
        $user_m_perm = '<span></span>';
    } else {
        $user_m_perm = '<span class="admin_icon"><i class="fas fa-screwdriver-wrench" style="color: ' . $m_perm['color_prefix'] . '; margin-left: 5px; font-size: 15px;"></i></span>';
    }

    if ($users['verified'] == 0) {
        $verified = '';
    } elseif ($users['verified'] == 1) {
        $verified = '<span class="scam_prefix" data-hint-prefix="На данного пользователя поступило много жалоб о мошенничестве">SCAM</span>';
    }

    $user_nick = '<a class="name_in-ank--user" href="' . homeLink() . '/id' . $users['id'] . '" style="' . $users['color_nick'] . '">' . $users['login'] . '</a> ' . $verified;

    return empty($users) ? 'Удален' : $user_nick;
}

function nick($id)
{
    global $HOME;
	
	$user = checkCookie();
    $users = mfa(dbquery("SELECT * FROM users WHERE id = '" . $id . "' LIMIT 1"));

    $m_perm = mfa(dbquery("SELECT * FROM admin_perm WHERE id = '" . $users['level_us'] . "'"));
    if ($m_perm['id'] < 2) {
        $user_m_perm = '<span></span>';
    } else {
        $user_m_perm = '<span class="admin_icon"><i class="fas fa-screwdriver-wrench" style="color: ' . $m_perm['color_prefix'] . '; margin-left: 5px; font-size: 15px;"></i></span>';
    }

    if ($users['verified'] == 0) {
        $verified = '';
    } elseif ($users['verified'] == 1) {
        $verified = '<span class="scam_prefix" data-hint-prefix="На данного пользователя поступило много жалоб о мошенничестве">SCAM</span>';
    }
	
	    $count_friends = msres(dbquery("SELECT COUNT(*) FROM `friends` WHERE `us_a` = '" . $users['id'] . "' AND `status` = '1'"), 0);
        $t_forum = msres(dbquery("SELECT COUNT(*) FROM `forum_tema` WHERE `us` = '" . $users['id'] . "'"), 0);
        $p_forum = msres(dbquery("SELECT COUNT(*) FROM `forum_post` WHERE `us` = '" . $users['id']. "'"), 0);
        $like_forum = msres(dbquery("SELECT count(*) as `total` from `forum_like` where `themus` = '" . $users['id'] . "'"), 0);

    $user_nick = '<a class="name_in-ank--user" data-bs-toggle="modal" data-bs-target="#user' . $id . '" style="' . $users['color_nick'] . '">' . $users['login'] . '</a> ' . $verified;
	if (isset($users['background'])) {
		$head_back = 'background-image: linear-gradient(rgba(var(--blackout-them-head)), rgba(var(--blackout-them-head))), url(' . $users['background'] . ')';
	} else {
		$head_back = '';
	}
	$modal = '
	<div class="modal fade" id="user' . $id . '" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="max-width: 600px;">
      <div class="modal-content">
        <div class="user-modal-header" style="' . $head_back . ';">
          <div class="head-ank-modal">
            <div class="avatar-modal" id="exampleModalLabel">' . UserAvatar($users, 80, 80) . '</div>
			<div class="modal-ank_but-and-text">
            <div class="name-and-stat--ank-modal">
              <div class="name-in-ank-modal" style="font-size: 16px !important">' . nickLink($users['id']) . '</div>
              <div class="stat-in-ank-modal">' . $users['stat'] . '</div>
            </div>
			' . (empty($user['id']) ? '<span></span>' : '<div class="btn-group">
			' . ($users['id'] == $user['id'] ? '<span></span>' :
			  '<a class="button prm" href="' . homeLink() . '/mes/dialog' . $users['id'] . '" style="width: -webkit-fill-available;">Сообщение</a>
			  <a class="button plm minbtn" href="' . homeLink() . '/perevod' . $users['id'] . '"><i class="fas fa-dollar" style="line-height: 34px; font-size: 17px;"></i></a>') . '
		    </div>') . '
		  </div>
          </div>
        </div>
        <div class="modal-body" style="padding: 0px;">
		<div class="menu_nb">
          <div class="anc_function_buttons" style="margin-top: 0px;">
            <a class="long-lite" href="' . homeLink() . '/them' . $users['id'] . '">
              <span class="farier"><i class="far fa-comment-alt" style="margin: 0 8px 0px 6px; color: #525050; font-size: 15px; display: inline;"></i></span> Темы от ' . $users['login'] . '
            </a>
			 ' . (empty($user['id']) ? '<span></span>' : '<a class="long-lite" href="' . homeLink() . '/reputation' . $users['id'] . '"><span class="farier"><i class="far fa-arrow-alt-circle-up" style="margin: 0 8px 0px 6px; color: #525050; font-size: 15px; display: inline;"></i></span> Поднять репутацию</a>') . '
          </div>
		  <div class="menu_nb">
		  <b style="font-size: 14px;">Активность</b>
		  <div style="margin-top: 5px;">' . vremja($users['viz']) . '</div>
		  </div>
		  </div>
		  <div class="menu_nb" style="border-top: 1px solid var(--all-border-color);">
		  <center><div class="tablo-prof-ind" style="overflow-x: scroll; white-space: nowrap;">
		  <a class="ank-indikation" href="/them' . $id . '"><span style="color: var(--items-color)">' . $t_forum . '</span></br>темы</a>
		  <a class="ank-indikation" href="/reputation' . $id . '"><span style="color: var(--items-color)">' . msres(dbquery('select count(`id`) from `repa_user` where `komy` = "' . $users['id'] . '" and `repa` = "+"'), 0) . '</span></br>репутация</a>
		  <a class="ank-indikation"><span style="color: var(--items-color)">' . $like_forum . '</span></br>лайки</a>
		  <a class="ank-indikation" href="/post' . $id . '"><span style="color: var(--items-color)">' . $p_forum . '</span></br>ответы</a>
		  <a class="ank-indikation" href="/friends' . $id . '"><span style="color: var(--items-color)">' . $count_friends . '</span></br>друзья</a>
		  </div></center>
		  </div>
		
        </div>
      </div>
    </div>
	</div>';

    return empty($users) ? 'Удален' : $user_nick . $modal;
}

function RewriteUrl($rules, $currentUrl) {
    // Обрабатываем правила реврайта
    foreach ($rules as $rule) {
        if ($currentUrl == $rule['to']) {
            // Устанавливаем новый URL
            $newUrl = $rule['from'];

            // Загружаем содержимое по новому URL
            $content = file_get_contents($_SERVER['DOCUMENT_ROOT'] . $newUrl);

            // Выводим содержимое
            echo $content;

            // Завершаем выполнение скрипта
            exit();
        }
    }
}

function file_upload($type) {
	echo '<div class="modal fade" id="load_file" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog">
	<div class="modal-content">
	
	<div class="modal-header">
	<h5 class="modal-title" id="exampleModalLabel">Добавить файл</h5>
	<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i class = "fas fa-xmark"></i></button>
	</div>
	
	<div class="modal-body">
	<h2>Загрузка документа</h2>
	<div id="' . $type . '-container" class="drop-target"><div class="menu_cont" style="font-size: 30px;"><i class="fas fa-plus"></i></div>Перетащите сюда файл или нажмите чтобы выбрать</div>
	</div>
	
	<div id="' . $type . '-list"></div>
	
	<div class="modal-footer">
	<a class="button" id="' . $type . '-upload">Загрузить</a>
	<a class="button" data-bs-dismiss="modal">Отмена</a>
	</div>
	
	</div>
	</div>
	</div>';
}

function complaint_status($id) {
	if($id == 0) {
		return '<span>На рассмотрении</span>';
	}
	if($id == 1) {
		return '<span>Рассмотрена</span>';
	}
}

class SeoItems
{
	public function get_breadcrumbs($separator = ' / ') {
		// Проверяем, на главной ли мы странице
		if ($_SERVER['REQUEST_URI'] === '/' || $_SERVER['REQUEST_URI'] === '') {
			return '';
		}
		
		$id = abs(intval($_GET['id']));
		$razdel = mfa(dbquery("SELECT * FROM `forum_razdel` WHERE `id` = '" . $id . "'"));
		$kat = mfa(dbquery("SELECT * FROM `forum_kat` WHERE `id` = '" . $id . "'"));
		$tema = mfa(dbquery("SELECT * FROM `forum_tema` WHERE `id` = '" . $id . "'"));
		$tema_to_kat = mfa(dbquery("SELECT * FROM `forum_kat` WHERE `id` = '" . $tema['kat'] . "'"));
		$kat_to_razdel = mfa(dbquery("SELECT * FROM `forum_razdel` WHERE `id` = '" . $kat['razdel'] . "'"));
		$users = mfa(dbquery("SELECT * FROM `users` WHERE `id` = '" . $id . "'"));
		$prev = mfa(dbquery("SELECT * FROM `user_prevs` WHERE `id` = '" . $id . "'"));
		$tooltip = mfa(dbquery("SELECT * FROM `forum_tooltips` WHERE `id` = '" . $id . "'"));
		$rule = mfa(dbquery("SELECT * FROM `rules` WHERE `id` = '" . $id . "'"));
		
		// Кастомные ссылки (убраны URL)
		$custom_links = [
		'razdel' . $id => ['title' => $razdel['name']],
        'id' . $id => ['title' => $users['login']],
		'dialog' . $id => ['title' => 'Чат с ' . $users['login']],
		'pay-upgrade' . $id => ['title' => 'Покупка ' . $prev['name']],
		'edit-upgrade' . $id => ['title' => 'Редактирование повышения ' . $prev['name']],
        'perevod' . $id => ['title' => 'Перевод средств'],
		'edit-faq' . $id => ['title' => 'Редактирование ответа'],
		'edit-tooltip' . $id => ['title' => 'Редактирование ключевого слова ' . $tooltip['definition']],
		'edit-rules' . $id => ['title' => 'Редактирование правила ' . $rule['kat']],
		'red_kat' . $id => ['title' => 'Редактирование категории ' . $kat['name']],
		'del_kat' . $id => ['title' => 'Удаление категории ' . $kat['name']],
		'red_razdel' . $id => ['title' => 'Редактирование раздела ' . $razdel['name']],
		'del_razdel' . $id => ['title' => 'Удаление раздела ' . $razdel['name']],
		'edit-vk' . $id => ['title' => 'Редактирование авторизации через VK'],
        'search' => ['title' => 'Поиск по форуму'],
        'newp' => ['title' => 'Новые посты'],
        'zakl' => ['title' => 'Закладки'],
        'online' => ['title' => 'Пользователи в сети'],
        'admin' => ['title' => 'Админ панель'],
        'settings' => ['title' => 'Настройки'],
        'users' => ['title' => 'Пользователи'],
        'ras' => ['title' => 'Рассылка'],
        'bonus' => ['title' => 'Бонусы'],
        'aspam' => ['title' => 'Антиспам'],
        'search-ip' => ['title' => 'Поиск по IP'],
        'ban' => ['title' => 'Бан лист'],
        'rek' => ['title' => 'Реклама'],
        'groups' => ['title' => 'Группы пользователей'],
        'faq' => ['title' => 'F.A.Q'],
        'new-faq' => ['title' => 'Новый ответ'],
        'smile' => ['title' => 'Смайлы'],
        'addpapka' => ['title' => 'Новый раздел'],
        'prefix' => ['title' => 'Префиксы'],
        'style' => ['title' => 'Оформление'],
        'tooltips' => ['title' => 'Ключевые слова'],
		'new-tooltip' => ['title' => 'Новое ключевое слово'],
        'rules' => ['title' => 'Правила форума'],
		'new-rules' => ['title' => 'Новое правило'],
		'auth' => ['title' => 'Авторизация'],
        'registr' => ['title' => 'Регистрация'],
        'privacy' => ['title' => 'Политика конфиденциальности'],
        'account' => ['title' => 'Редактировать профиль'],
        'security' => ['title' => 'Безопасность'],
        'upgrade' => ['title' => 'Повышение прав'],
		'newt' => ['title' => 'Новые темы'],
		'forum' => ['title' => 'Узлы форума'],
		'balance' => ['title' => 'Мой баланс'],
		'mes' => ['title' => 'Сообщения'],
		'lenta' => ['title' => 'Уведомления'],
		'avatar' => ['title' => 'Аватар'],
		'dellenta' => ['title' => 'Очистка уведомлений'],
		'up-level' => ['title' => 'Повышение прав'],
        'new-upgrade' => ['title' => 'Новое повышение'],
		'doska' => ['title' => 'Мошенники'],
		'vk' => ['title' => 'VK'],
		'set-vk' => ['title' => 'Авторизация через VK'],
		'perevod' => ['title' => 'Перевод'],
		'payments' => ['title' => 'Оплата'],
		'ignor_list' => ['title' => 'Игнор лист'],
		'save' => ['title' => 'Избранные'],
		'nr' => ['title' => 'Новый раздел'],
		'complaints' => ['title' => 'Жалобы'],
		'complaint' => ['title' => 'Жалоба'],
		'administration' => ['title' => 'Администрация']
		];
		
		// Связки крошек
		$breadcrumb_combinations = [
			'tema' . $id => [
				['title' => $tema_to_kat['name'], 'url' => homeLink() . '/kat' . $tema_to_kat['id']],
				['title' => $tema['name']]
			],
			'kat' . $id => [
				['title' => $kat_to_razdel['name'], 'url' => homeLink() . '/razdel' . $kat_to_razdel['id']],
				['title' => $kat['name']]
			],
			'forum/new-kat-' . $id => [
				['title' => 'Узлы форума', 'url' => homeLink() . '/forum'],
				['title' => $razdel['name'], 'url' => homeLink() . '/razdel' . $razdel],
				['title' => 'Новая категория']
			],
			'them' . $id => [
				['title' => $users['login'], 'url' => homeLink() . '/id' . $id],
				['title' => 'Темы пользователя']
			],
			'reputation' . $id => [
				['title' => $users['login'], 'url' => homeLink() . '/id' . $id],
				['title' => 'Репутация пользователя']
			],
			'post' . $id => [
				['title' => $users['login'], 'url' => homeLink() . '/id' . $id],
				['title' => 'Ответы пользователя']
			],
			'new-them-' . $id => [
				['title' => $kat['name'], 'url' => homeLink() . '/kat' . $id],
				['title' => 'Новая тема']
			],
			'friends' . $id => [
				['title' => $users['login'], 'url' => homeLink() . '/id' . $id],
				['title' => 'Друзья пользователя']
			]
		];
		
		$path = array_filter(explode('/', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)));
		$base_url = (isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/';
		$breadcrumbs = array("<a href=\"$base_url\" class=\"breadcrumb-link\">Главная</a>");
		
		$schema_data = [
			"@context" => "https://schema.org",
			"@type" => "BreadcrumbList",
			"itemListElement" => []
		];
		
		$position = 1;
		
		foreach ($path as $key => $crumb) {
			// Формируем текущий путь
			$current_path = implode('/', array_slice($path, 0, $key + 1));
			
			// Если есть кастомная комбинация для текущего пути, используем её
			if (isset($breadcrumb_combinations[$current_path])) {
				foreach ($breadcrumb_combinations[$current_path] as $custom_crumb) {
					$breadcrumbs[] = "<a href=\"{$custom_crumb['url']}\" class=\"breadcrumb-link\">{$custom_crumb['title']}</a>";
					$schema_data['itemListElement'][] = [
						"@type" => "ListItem",
						"position" => $position,
						"name" => $custom_crumb['title'],
						"item" => $custom_crumb['url']
					];
					$position++;
				}
				break;
			}
			
			// Формируем URL для каждой части пути
			$url = $base_url . $crumb;
			
			// Если есть кастомная ссылка для текущего пути, используем её
			if (isset($custom_links[$crumb])) {
				$title = $custom_links[$crumb]['title'];
			} else {
				// В противном случае используем часть пути как заголовок
				$title = ucwords(str_replace('-', ' ', $crumb));
			}
			
			if ($key == array_key_last($path)) {
				$breadcrumbs[] = "<span class=\"breadcrumb-current\">$title</span>"; // Последний элемент не является ссылкой
			} else {
				$breadcrumbs[] = "<a href=\"$url\" class=\"breadcrumb-link\">$title</a>";
			}
			
			$schema_data['itemListElement'][] = [
				"@type" => "ListItem",
				"position" => $position,
				"name" => $title,
				"item" => $url
			];
			
			$position++;
			$base_url .= $crumb . '/';
		}
		
		echo '<script type="application/ld+json">' . json_encode($schema_data) . '</script>';
		return implode($separator, $breadcrumbs);
	}
	
	public function get_description() {
		$id = abs(intval($_GET['id']));
		$forum_t = mfa(dbquery("SELECT * FROM `forum_tema` WHERE `id` = '" . $id . "'"));
		$forum_k = mfa(dbquery("SELECT * FROM `forum_kat` WHERE `id` = '" . $id . "'"));
		$forum_r = mfa(dbquery("SELECT * FROM `forum_razdel` WHERE `id` = '" . $id . "'"));
		$users = mfa(dbquery("SELECT * FROM `users` WHERE `id` = '" . $id . "'"));
		$index = mfa(dbquery("SELECT * FROM `settings` WHERE `id` = '1'"));
		
		$descriptions = [
			"/" => "" . $index['des'] . "",
			"/tema" . $id . "" => "" . $forum_t['text_col'] . "",
			"/kat" . $id . "" => "" . $forum_k['opisanie'] . "",
			"/razdel" . $id . "" => "" . $forum_r['opis'] . "",
			"/new-them-" . $id . "" => "Создать тему в категории " . $forum_k['name'] . "",
			"/id" . $id . "" => "" . $users['login'] . " - " . $index['name'] . " - " . $index['podname'] . "",
			"/forum/newp" => "Новые посты - " . $index['name'] . "",
			"/login" => "Авторизация - " . $index['name'] . " - " . $index['podname'] . "",
			"/registr" => "Регистрация - " . $index['name'] . " - " . $index['podname'] . "",
			"/faq" => "Ответы на часто задаваемые вопросы",
			"/rules" => "Правила форума " . $index['name'] . "",
			"/mes" => "Личные сообщения - " . $index['name'] . " - " . $index['podname'] . "",
			"/lenta" => "Уведомления - " . $index['name'] . " - " . $index['podname'] . "",
			"/settings" => "Личные настройки - " . $index['name'] . " - " . $index['podname'] . "",
			"/security" => "Параметры безопасности - " . $index['name'] . " - " . $index['podname'] . "",
			"/search" => "Поиск по сайту - " . $index['name'] . " - " . $index['podname'] . "",
			"/online" => "Список пользователей - " . $index['name'] . " - " . $index['podname'] . "",
			"/usedit" => "Настройки профиля - " . $index['name'] . " - " . $index['podname'] . "",
			"/upgrade" => "Покупка повышения прав - " . $index['name'] . " - " . $index['podname'] . "",
			"/forum/zakl" => "Закладки - " . $index['name'] . " - " . $index['podname'] . "",
			"/them" . $id . "" => "Темы пользователя - " . $users['login'] . "",
			"/post" . $id . "" => "Ответы пользователя - " . $users['login'] . "",
			"/reputation" . $id . "" => "Репутация пользователя пользователя - " . $users['login'] . "",
			"/friends" . $id . "" => "Друзья пользователя пользователя - " . $users['login'] . "",
		];
		
		$currentPath = $_SERVER['REQUEST_URI'];
		return htmlspecialchars($descriptions[$currentPath] ?? "", ENT_QUOTES, 'UTF-8');
	}
}

class SelectField
{
	public function createSelectField($name, $label) {
		$options = array('Нет' => '0', 'Да' => '1');
		$html = '<div class="setting_punkt"><label>' . $label . '</label></div><div class="gt-select"><select name="' . $name . '">';
		foreach ($options as $key => $value) {
			$html .= '<option value="' . $value . '">' . $key . '</option>';
		}
		$html .= '</select></div>';
		return $html;
	}
	
	public function updateSelectField($name, $label, $selectedValue) {
		$options = array('Нет' => '0', 'Да' => '1');
		$html = '<div class="setting_punkt"><label>' . $label . '</label></div><div class="gt-select"><select name="' . $name . '">';
		foreach ($options as $key => $value) {
			$html .= '<option value="' . $value . '"' . ($value == $selectedValue ? ' selected="selected"' : '') . '>' . $key . '</option>';
		}
		$html .= '</select></div>';
		return $html;
	}
}

class Transact
{
	public function ts_status($id) {
		if($id == 1) {
			return '<span>Успешно</span>';
		} else {
			return '<span>Ошибка</span>';
		}
	}
	
	public function ts_type($id) {
		if($id == 1) {
			return '<span>Перевод</span>';
		}
		if($id == 2) {
			return '<span>Бонус</span>';
		}
		if($id == 3) {
			return '<span>Пополнение</span>';
		}
	}
}
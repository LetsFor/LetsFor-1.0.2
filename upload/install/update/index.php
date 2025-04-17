<?
include($_SERVER['DOCUMENT_ROOT'] . '/install/core/head.php');
echo '<title>Обновление LetsFor</title>';
$act = isset($_GET['act']) ? $_GET['act'] : null;
switch ($act) {
    default:
        echo '<div class="content">';
        echo '<div class="menu">';
        echo '<div class="title">Добро пожаловать!</div>';
        echo '<div class="pod_title">
		Вас приветствует мастер обновления LetsFor!<br />
		Перед началом обновления убедитесь что архов с обновлением расположен в /install/update/up, иначе обновление не произойдет. Чтобы начать обновление нажмите на кнопку ниже.
		</div>';
        echo '<a class="button" href="' . homeLink() . '/install/update/?act=update">Начать обновление</a>';
        echo '</div>';
        echo '</div>';
        break;

    case 'update':
	
        require_once('' . $_SERVER['DOCUMENT_ROOT'] . '/LFcore/config.php');

        $servername = $config['host'];
        $username = $config['user'];
        $password = $config['pass'];
        $dbname = $config['base'];

        $dsn = 'mysql:host=' . $servername . ';dbname=' . $dbname . ';charset=utf8';

        function find_zip_file($directory)
        {
            $files = scandir($directory);
            $zip_files = [];

            foreach ($files as $file) {
                if (pathinfo($file, PATHINFO_EXTENSION) == 'zip') {
                    $zip_files[] = $directory . '/' . $file;
                }
            }

            if (count($zip_files) > 1) {
                throw new Exception('Multiple zip files found in directory.');
            } elseif (count($zip_files) == 1) {
                return $zip_files[0];
            } else {
                return null;
            }
        }

        function extract_zip($zip_file, $extract_to)
        {
            $zip = new ZipArchive;
            if ($zip->open($zip_file) === TRUE) {
                $zip->extractTo($extract_to);
                $zip->close();
            }
        }

        function encode_base64_chars($value)
        {
            $search = ['.', ',', ':', '#', '*', ';'];
            $replace = array_map('base64_encode', $search);
            return str_replace($search, $replace, $value);
        }

        function decode_base64_chars($value)
        {
            $replace = ['.', ',', ':', '#', '*', ';'];
            $search = array_map('base64_encode', $replace);
            return str_replace($search, $replace, $value);
        }

        function backup_all_tables($connection, $backup_dir)
        {
            $tables = $connection->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);

            foreach ($tables as $table) {
                // Проверяем, что таблица не пустая
                $row_count = $connection->query("SELECT COUNT(*) FROM $table")->fetchColumn();
                if ($row_count > 0) {
                    $backup_file = $backup_dir . '/' . $table . '_backup.sql';
                    $data = $connection->query("SELECT * FROM $table")->fetchAll(PDO::FETCH_ASSOC);

                    $backup_sql = "";

                    foreach ($data as $row) {
                        $columns = array_keys($row);
                        $values = array_map(function ($value) use ($connection) {
                            return $value === null ? 'NULL' : $connection->quote(encode_base64_chars($value));
                        }, array_values($row));

                        $escaped_columns = array_map(function ($column) {
                            return "$column";
                        }, $columns);

                        $updates = [];
                        foreach ($columns as $column) {
                            $updates[] = "$column=VALUES($column)";
                        }

                        $backup_sql .= "INSERT INTO $table (" . implode(", ", $escaped_columns) . ") VALUES (" . implode(", ", $values) . ") ON DUPLICATE KEY UPDATE " . implode(", ", $updates) . ";\n";
                    }

                    file_put_contents($backup_file, $backup_sql);
                }
            }
        }

        try {
            $connection = new PDO($dsn, $username, $password);
            $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die('Connection failed: ' . $e->getMessage());
        }

        function update_database($connection, $sql_file)
        {
            $sql = file_get_contents($sql_file);
            $queries = explode(';', $sql);
            foreach ($queries as $query) {
                if (trim($query)) {
                    $connection->exec($query);
                }
            }
        }

        function restore_data($connection, $backup_dir)
        {
            $tables = scandir($backup_dir);

            foreach ($tables as $table_file) {
                if (pathinfo($table_file, PATHINFO_EXTENSION) == 'sql') {
                    $backup_sql = file_get_contents($backup_dir . '/' . $table_file);
                    $queries = explode(';', $backup_sql);
                    foreach ($queries as $query) {
                        if (trim($query)) {
                            try {
                                // Декодирование символов из base64 перед выполнением запроса
                                $decoded_query = decode_base64_chars($query);
                                $connection->exec($decoded_query);
                            } catch (PDOException $e) {
                                // Обработка ошибок
                            }
                        }
                    }
                }
            }

            // Удаление резервных копий после завершения восстановления данных
            foreach ($tables as $table_file) {
                if (pathinfo($table_file, PATHINFO_EXTENSION) == 'sql') {
                    unlink($backup_dir . '/' . $table_file);
                }
            }
        }

        function copy_files($source, $destination, $excluded)
        {
            $dir = opendir($source);
            @mkdir($destination);

            while (false !== ($file = readdir($dir))) {
                if ($file != '.' && $file != '..') {
                    $src_file = $source . '/' . $file;
                    $dest_file = $destination . '/' . $file;

                    // Проверяем, находится ли файл или папка в списке исключений
                    $relative_path = str_replace($_SERVER['DOCUMENT_ROOT'], '', $dest_file);
                    if (in_array($relative_path, $excluded)) {
                        continue;
                    }

                    if (is_dir($src_file)) {
                        copy_files($src_file, $dest_file, $excluded);
                    } else {
                        copy($src_file, $dest_file);
                    }
                }
            }

            closedir($dir);
        }

        // Найти zip-архив в указанной директории
        $directory = $_SERVER['DOCUMENT_ROOT'] . '/install/update/up';
		try {
			$zip_file = find_zip_file($directory);
			
			if ($zip_file) {
				// Создание резервной копии всех таблиц
				$backup_dir = $_SERVER['DOCUMENT_ROOT'] . '/install/update/reserv';
				backup_all_tables($connection, $backup_dir);
				
				// Извлечение файлов из zip-архива
				mkdir($_SERVER['DOCUMENT_ROOT'] . '/install/update/up/data');
				$extract_to = $_SERVER['DOCUMENT_ROOT'] . '/install/update/up/data';
				extract_zip($zip_file, $extract_to);
				
				// Обновление базы данных
				$sql_file = $extract_to . '/upload/install/db/db.sql';
				update_database($connection, $sql_file);
				
				// Восстановление данных из резервных копий
				restore_data($connection, $backup_dir);
				
				// Копирование файлов и папок из /install/update/up/data/upload в главную директорию
				$source_dir = $_SERVER['DOCUMENT_ROOT'] . '/install/update/up/data/upload';
				$dest_dir = $_SERVER['DOCUMENT_ROOT'];
				$excluded = ['/install', '/files', '/plugins', '/favicon.ico'];
				copy_files($source_dir, $dest_dir, $excluded);
				
				function clear_folder($folder) {
					if (is_dir($folder)) {
						$files = array_diff(scandir($folder), array('.', '..'));
						foreach ($files as $file) {
							$path = $folder . DIRECTORY_SEPARATOR . $file;
							if (is_dir($path)) {
								clear_folder($path);
								rmdir($path);
							} else {
								unlink($path);
							}
						}
					}
				}
				
				// Очистка папки data
				clear_folder($extract_to);
				rmdir($extract_to); // Удаление самой папки
				
				header("Location: " . homeLink() . "/install/update/info/mess_page.php?act=success");
				exit();
			} else {
				header("Location: " . homeLink() . "/install/update/info/mess_page.php?act=nonzip");
				exit();
			}
		} catch (Exception $e) {
			header("Location: " . homeLink() . "/install/update/info/mess_page.php?act=nonzip");
			exit();
		}

        break;
}

include($_SERVER['DOCUMENT_ROOT'] . '/install/core/footer.php');

<script>
document.addEventListener('DOMContentLoaded', function() {
  // Ваш код здесь
  function toggleClasses(elements) {
    elements.forEach(([id, className]) => {
      document.getElementById(id).classList.toggle(className);
    });
  }

  document.getElementById('overlay').onclick =
  document.getElementById('hamburger').onclick = function() {
    toggleClasses([
      ['nav-icon', 'open_burger'],
      ['sidebar', 'opened'],
      ['overlay', 'opened']
    ]);
  };

  document.getElementById('overlay-onew').onclick =
  document.getElementById('allthems').onclick = function() {
    toggleClasses([
      ['allthem-open', 'opened'],
      ['overlay-onew', 'opened']
    ]);
  };
});

  $('#content').on('input', function() {
    this.style.height = '0px';
    this.style.height = (this.scrollHeight + 5) + 'px';
  });

  setTimeout(function() {
    document.body.classList.add('body');
  }, 25);
  setTimeout(function() {
    document.body.classList.add('body');
  }, 25);

  $(document).on({
    'click': function(e) {
      var target,
        href;
      if (!e.isDefaultPrevented() && (e.which === 1 || e.which === 2)) {
        target = $(this).data('target') || '_self';
        href = $(this).data('href');
        if (e.ctrlKey || e.shiftKey || e.which === 2) {
          target = '_blank'; //close enough
        }
        open(href, target);
      }
    },
    'keydown': function(e) {
      if (e.which === 13 && !e.isDefaultPrevented()) {
        $(this).trigger({
          type: 'click',
          ctrlKey: e.ctrlKey,
          altKey: e.altKey,
          shiftKey: e.shiftKey
        });
      }
    }
  }, '[role="link"]');

  $('[aria-disabled="true"]').on('click', function(e) {
    e.preventDefault();
  });

  var need_old_jquery = jQuery.noConflict();

  (function() {
    var pre = document.getElementsByTagName('pre'),
      pl = pre.length;
    for (var i = 0; i < pl; i++) {
      pre[i].innerHTML = '<span class="line-number"></span>' + pre[i].innerHTML + '<span class="cl"></span>';
      var num = pre[i].innerHTML.split(/\n/).length;
      for (var j = 0; j < num; j++) {
        var line_num = pre[i].getElementsByTagName('span')[0];
        line_num.innerHTML += '<span>' + (j + 1) + '</span>';
      }
    }
  })();

  function up() {
    var top = Math.max(document.body.scrollTop, document.documentElement.scrollTop);
    if (top > 0) {
      window.scrollBy(0, ((top + 100) / -10));
      t = setTimeout('up()', 20);
    } else clearTimeout(t);
    return false;
  }
  jQuery(function(f) {
    var element = f('#back-top');
    f(window).scroll(function() {
      element['fade' + (f(this).scrollTop() > 100 ? 'In' : 'Out')](250);
    });
  });

  $(document).ready(function() {
    // Убедимся, что элемент #test виден в начале
    $('#test').show();

    // Обработка клика на кнопках внутри .buttons
    $('.buttons button').click(function() {
      // Переключаем видимость элемента #test
      $('#test').toggle();

      // Получаем ID нажатой кнопки
      var id = $(this).attr('id');

      // Скрываем нажатую кнопку
      $(this).hide();

      // Показываем противоположную кнопку
      if (id === 'hide') {
        $('#show').show();
      } else {
        $('#hide').show();
      }
    });
  });

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

  $(document).ready(function() {
    // Проверяем, загружены ли уже тултипы
    if (typeof window.wordsWithTooltips === 'undefined') {
      $.getScript('/LFcore/src/tooltips.php', function() {
        applyTooltipsOnce();
      });
    }
  });

  function applyTooltipsOnce() {
    if (!window.tooltipsApplied) {
      addCustomTooltips(window.wordsWithTooltips);
      window.tooltipsApplied = true; // Устанавливаем флаг, что тултипы применены
    }
  }

  function addCustomTooltips(wordsWithTooltips) {
    var textElements = document.querySelectorAll('#text');
    textElements.forEach(function(textElement) {
      var content = textElement.innerHTML;
      for (var word in wordsWithTooltips) {
        if (wordsWithTooltips.hasOwnProperty(word)) {
          var regex = new RegExp('\\b' + word + '\\b', 'gi');
          content = content.replace(regex, function(match) {
            if (!match.includes('tooltip')) {
              return '<span class="tooltip">' + match + '<span class="tooltiptext">' + wordsWithTooltips[word] + '</span></span>';
            }
            return match;
          });
        }
      }
      textElement.innerHTML = content;
    });
  }

  function togglePassword() {
    var passwordInput = document.getElementById("password");
    var toggleIcon = document.querySelector(".toggle-password");

    if (passwordInput.type === "password") {
      passwordInput.type = "text";
      toggleIcon.classList.remove("fa-eye");
      toggleIcon.classList.add("fa-eye-slash");
    } else {
      passwordInput.type = "password";
      toggleIcon.classList.remove("fa-eye-slash");
      toggleIcon.classList.add("fa-eye");
    }
  }

  function insertTag(startTag, endTag) {
    var input = document.message.msg;
    var start = input.selectionStart;
    var end = input.selectionEnd;
    var selectedText = input.value.substring(start, end);
    var newText = startTag + selectedText + endTag;
    input.value = input.value.substring(0, start) + newText + input.value.substring(end);
    input.focus();
    input.selectionStart = start + startTag.length;
    input.selectionEnd = start + startTag.length + selectedText.length;
  }

  function DoSmilie(smilie) {
    // Предположим, что у вас есть текстовое поле с id 'message'
    var messageField = document.getElementById('content');

    // Добавляем смайлик в текущее положение курсора в текстовом поле
    if (messageField) {
      var startPos = messageField.selectionStart;
      var endPos = messageField.selectionEnd;
      var textBefore = messageField.value.substring(0, startPos);
      var textAfter = messageField.value.substring(endPos, messageField.value.length);

      messageField.value = textBefore + smilie + textAfter;

      // Устанавливаем курсор после вставленного смайлика
      messageField.selectionStart = startPos + smilie.length;
      messageField.selectionEnd = startPos + smilie.length;
      messageField.focus();
    }
  }

  document.addEventListener('DOMContentLoaded', function() {
    var box = document.getElementById('myBox');
    if (box) {
      new SimpleBar(box);
    }
  });

  function escapeHtml(str) {
    return str
      .replace(/&/g, '&a34bx;')
      .replace(/</g, '&lk4dv;')
      .replace(/>/g, '&h94mj;')
      .replace(/"/g, '&q78ht;')
      .replace(/'/g, '&#39vd;')
      .replace(/`/g, '&#x60s;');
  }

document.addEventListener('DOMContentLoaded', function () {
    var element = document.getElementById('multiple_label_example');

    // Получаем значение атрибута limit из select
    var limit = element.hasAttribute('limit') ? parseInt(element.getAttribute('limit'), 10) : Infinity;

    // Инициализация Choices
    var choices = new Choices(element, {
        removeItemButton: true,
        shouldSort: false // Отключаем встроенную сортировку Choices
    });

    // Обработка события добавления элементов
    element.addEventListener('addItem', function () {
        var selectedOptions = [...element.options].filter(option => option.selected);

        // Сортировка и обновление контейнера внутри выборов
        updateContainer(selectedOptions, element);

        if (selectedOptions.length >= limit) {
            // Скрываем контейнер выбора
            const dropdown = element.closest('.choices').querySelector('.choices__list--dropdown');
            if (dropdown) {
                dropdown.style.display = 'none';
            }
        }
    });

    // Обработка события удаления элементов
    element.addEventListener('removeItem', function () {
        var selectedOptions = [...element.options].filter(option => option.selected);

        // Сортировка и обновление контейнера внутри выборов
        updateContainer(selectedOptions, element);

        if (selectedOptions.length < limit) {
            // Показываем контейнер выбора
            const dropdown = element.closest('.choices').querySelector('.choices__list--dropdown');
            if (dropdown) {
                dropdown.style.display = '';
            }
        }
    });

    // Функция для сортировки и обновления контейнера
    function updateContainer(selectedOptions, element) {
        selectedOptions.sort((a, b) => parseInt(a.value, 10) - parseInt(b.value, 10)); // Сортировка по value

        const container = element.closest('.choices').querySelector('.choices__list--multi');
        if (container) {
            container.innerHTML = ''; // Очищаем контейнер перед обновлением

            selectedOptions.forEach(option => {
                const item = document.createElement('div');
                item.className = 'choices_item choices_item--selectable'; // Классы для стилей
                item.textContent = option.textContent; // Текст элемента
                container.appendChild(item);
            });
        }
    }
});

$(document).ready(function () {
    $('.modal').appendTo('#modal-container');
});

$(function() {
    $(document).on('click', '.spoiler-title', function() {
        const content = $(this).siblings('.spoiler-content');
        if (content.length > 0) {
            content.stop(true, true).slideToggle('fast');
        }
    });
});

const fixedElement = document.querySelector('.head');
const overlay = document.querySelector('#overlay');
const overlayOnew = document.querySelector('#overlay-onew');

// Проверка прокрутки
const handleScroll = () => {
  if (window.scrollY > 30) {
    fixedElement.classList.add('scrolled');
  } else {
    fixedElement.classList.remove('scrolled');
  }
};

// Проверка наличия класса 'opened' у overlay-элементов
const checkOverlays = () => {
  const isOverlayOpen =
    overlay.classList.contains('opened') ||
    overlayOnew.classList.contains('opened');

  if (isOverlayOpen) {
    fixedElement.classList.add('has-overlay');
  } else {
    fixedElement.classList.remove('has-overlay');
  }
};

// Обработчики событий
window.addEventListener('scroll', handleScroll);
setInterval(checkOverlays, 100);

// Инициализация
handleScroll();
checkOverlays();

document.addEventListener('DOMContentLoaded', () => {
    Fancybox.bind('[data-fancybox="gallery"]', {
        Toolbar: {
            display: [
                {id: 'counter', position: 'center'},  // отображение количества медиафайлов
                {id: 'close', position: 'right'}     // кнопка закрытия
            ]
        },
        Thumbs: false
    });
});

window.refreshContent = function() {
    const container = document.querySelector('.thems-lenta'); // Контейнер для обновления данных

    if (!container) {
        console.error('Контейнер не найден.');
        return;
    }

    fetch(`/LFcore/src/load_more.php?offset=0&limit=30`) // Запрос к серверу
        .then(response => response.text())
        .then(html => {
            container.innerHTML = html; // Полностью заменяем содержимое контейнера
        })
        .catch(error => {
            console.error('Ошибка при обновлении содержимого:', error);
        });
};


document.addEventListener('DOMContentLoaded', function () {
    // Конфигурация через PHP:
    // Для avatar:
    // Если $prv_us['set_gif_ava'] != 1 – разрешаем: image/png, image/jpg, image/jpeg, image/bmp;
    // Иначе – разрешаем: image/png, image/jpg, image/jpeg, image/bmp, image/webp, image/gif.
    // Для forum-document: проверку типов пропускаем (разрешены все).
    var uploaderConfig = {
        "avatar": {
            allowedTypes: <?php
                if ($prv_us['set_gif_ava'] != 1) {
                    echo json_encode(['image/png', 'image/jpg', 'image/jpeg', 'image/bmp']);
                } else {
                    echo json_encode(['image/png', 'image/jpg', 'image/jpeg', 'image/bmp', 'image/webp', 'image/gif']);
                }
            ?>,
            maxFiles: <?php echo 1; ?>
        },
        "forum-document": {
            allowedTypes: [], // Пустой массив – проверку типов пропускаем, позволяем все
            maxFiles: <?php echo 5; ?>
        }
    };

    function initializeUploader(targetUrl, dropTargetId, uploadButtonId, listId, fileType) {
        var config = uploaderConfig[fileType] || {};
        var allowedTypes = config.allowedTypes || [];
        var maxFiles = config.maxFiles || Infinity;

        var dropTarget = document.getElementById(dropTargetId);
        var flow = new Flow({
            target: targetUrl,
            chunkSize: 1024 * 1024,        // Размер частей (1 МБ)
            testChunks: false,             // Отключаем проверку частей
            query: function () {
                return { type: fileType }; // Передаем тип файла на сервер
            },
            autoUpload: false             // Автозагрузку отключаем
        });

        if (dropTarget) {
            // Привязываем контейнер для выбора файла
            flow.assignBrowse(dropTarget);

            // Drag and Drop: добавляем обработчики событий
            dropTarget.addEventListener('dragover', function (event) {
                event.preventDefault();
                dropTarget.classList.add('dragover');
            });

            dropTarget.addEventListener('dragleave', function () {
                dropTarget.classList.remove('dragover');
            });

            dropTarget.addEventListener('drop', function (event) {
                event.preventDefault();
                dropTarget.classList.remove('dragover');

                var files = event.dataTransfer.files;
                // Если список разрешенных типов не пустой – проверяем каждый файл
                if (allowedTypes.length > 0) {
                    for (var i = 0; i < files.length; i++) {
                        if (allowedTypes.indexOf(files[i].type) === -1) {
                            alert('Файл ' + files[i].name + ' имеет недопустимый тип.');
                            return;
                        }
                    }
                }
                flow.addFiles(files);
            });
        }

        var uploadButton = document.getElementById(uploadButtonId);
        if (uploadButton) {
            uploadButton.addEventListener('click', function () {
                flow.upload();
            });
        }

        // Обработка добавления файла: проверка типа и количества файлов
        flow.on('fileAdded', function (file) {
            // Если файл добавлен через выбор файла (не через drop), проверяем его MIME-тип.
            if (allowedTypes.length > 0 && allowedTypes.indexOf(file.file.type) === -1) {
                alert('Файл ' + file.name + ' имеет недопустимый тип.');
                flow.removeFile(file);
                return;
            }
            var fileList = document.getElementById(listId);
            if (fileList) {
                // Проверка: если число добавленных файлов уже достигло лимита, удаляем новый
                if (fileList.children.length >= maxFiles) {
                    alert('Можно загрузить не более ' + maxFiles + ' файлов.');
                    flow.removeFile(file);
                    return;
                }
                var listItem = document.createElement('div');
                listItem.setAttribute('data-file-id', file.uniqueIdentifier);
                listItem.innerHTML = `
                    <div class="menu_cont">
                        ${file.name}
                        <a class="remove-file-button" data-file-id="${file.uniqueIdentifier}">
                            <i class="fas fa-close" style="top: 2px;"></i>
                        </a>
                    </div>
                `;
                fileList.appendChild(listItem);

                listItem.querySelector('.remove-file-button')
                    .addEventListener('click', function () {
                        flow.removeFile(file);
                        fileList.removeChild(listItem);
                    });
            }
        });

        // После успешной загрузки перезагружаем страницу
        flow.on('fileSuccess', function (file, message) {
            location.reload();
        });
    }

    // Инициализация загрузчиков для каждого типа
    initializeUploader('/LFcore/src/upload_file.php', 'avatar-container', 'avatar-upload', 'avatar-list', 'avatar');
    initializeUploader('/LFcore/src/upload_file.php?id=<?php echo $id; ?>',
        'forum-document-container', 'forum-document-upload', 'forum-document-list', 'forum-document');
});

</script>
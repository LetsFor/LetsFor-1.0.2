<div class="teg">
    <div class="menu_bb">
        <a class='panel' href="javascript:insertTag('[B]', '[/B]')"><i class="fas fa-bold" title="Жирный шрифт"></i></a>
        <a class='panel' href="javascript:insertTag('[I]', '[/I]')"><i class="fas fa-italic" title="Курсив"></i></a>
        <span class="dropdown">
            <a class="panel btn-secondary dropdown-toggle" data-bs-display="static" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="true"><i class="fas fa-text-height" title="Размер текста"></i></a>
            <ul class="dropdown-menu" style="inset: auto auto 0 auto;min-width: 45px;transform: translate(0px, -35px);">
                <li><a class='dropdown-item' onclick="insertTag('[SIZE=9]', '[/SIZE]')">9</a></li>
                <li><a class='dropdown-item' onclick="insertTag('[SIZE=10]', '[/SIZE]')">10</a></li>
                <li><a class='dropdown-item' onclick="insertTag('[SIZE=12]', '[/SIZE]')">12</a></li>
                <li><a class='dropdown-item' onclick="insertTag('[SIZE=13]', '[/SIZE]')">13</a></li>
                <li><a class='dropdown-item' onclick="insertTag('[SIZE=15]', '[/SIZE]')">15</a></li>
                <li><a class='dropdown-item' onclick="insertTag('[SIZE=18]', '[/SIZE]')">18</a></li>
                <li><a class='dropdown-item' onclick="insertTag('[SIZE=22]', '[/SIZE]')">22</a></li>
                <li><a class='dropdown-item' onclick="insertTag('[SIZE=26]', '[/SIZE]')">26</a></li>
            </ul>
        </span>

        <a class='panel' href="javascript:insertTag('[U]', '[/U]')"><i class="fas fa-underline" title="Подчёркнутый"></i></a>
        <a class='panel' href="javascript:insertTag('[S]', '[/S]')"><i class="fas fa-strikethrough" title="Зачёркнутый"></i></a>
        <a class='panel' href="javascript:insertTag('[COLOR=#000]', '[/COLOR]')"><i class="fas fa-palette" title="Цвет"></i></a>

        <span class="dropdown">
            <a class="panel btn-secondary dropdown-toggle" data-bs-display="static" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="true"><i class="fas fa-align-center" title="Выровнять"></i></a>
            <ul class="dropdown-menu" style="inset: auto auto 0 0;transform: translate(-160px, -35px);">
                <li><a class='dropdown-item' href="javascript:insertTag('[LEFT]', '[/LEFT]')">По левому краю<span class="icon-menu"><i class="fas fa-align-left" title="Выровнять по левому краю"></i></span></a></li>
                <li><a class='dropdown-item' href="javascript:insertTag('[CENTER]', '[/CENTER]')">По центру<span class="icon-menu"><i class="fas fa-align-center" title="Выровнять по центру"></i></span></a></li>
                <li><a class='dropdown-item' href="javascript:insertTag('[RIGHT]', '[/RIGHT]')">По правому краю<span class="icon-menu"><i class="fas fa-align-right" title="Выровнять по праому краю"></i></span></a></li>
            </ul>
        </span>

        <span class="dropdown">
            <a class="panel btn-secondary dropdown-toggle" data-bs-display="static" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="true"><i class="fas fa-plus" title="Дополнительно"></i></a>
            <ul class="dropdown-menu" style="inset: auto auto 0 0;transform: translate(-160px, -35px);">
                <li><a class='dropdown-item' href="javascript:insertTag('[IMG]', '[/IMG]')">Изображение<span class="icon-menu"><i class="fas fa-image" title="Изображение"></i></span></a></li>
                <li><a class='dropdown-item' href="javascript:insertTag('[VIDEO]', '[/VIDEO]')">Видео<span class="icon-menu"><i class="fas fa-video" title="Видео"></i></span></a></li>
                <li><a class='dropdown-item' href="javascript:insertTag('[QUOTE]', '[/QUOTE]')">Цитата<span class="icon-menu"><i class="fas fa-quote-left" title="Цитата"></i></span></a></li>
                <li><a class='dropdown-item' href="javascript:insertTag('[CODE=Язык кода]', '[/CODE]')">Код<span class="icon-menu"><i class="fas fa-code" title="Код"></i></span></a>
                <li><a class='dropdown-item' href="javascript:insertTag('[spoiler=Название спойлера]', '[/spoiler]')">Спойлер<span class="icon-menu"><i class="fas fa-flag" title="Спойлер"></i></span></a></li>
                <li><a class='dropdown-item' href="javascript:insertTag('[URL=https://ссылка]', '[/URL]')">Ссылка<span class="icon-menu"><i class="fas fa-link" title="Ссылка"></i></span></a></li>
            </ul>
        </span>
    </div>
</div>


<script>
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
</script>
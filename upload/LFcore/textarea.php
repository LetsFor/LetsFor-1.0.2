<?
echo '<textarea style="margin: 0 3px 0 0" id="content" type="pole" placeholder="Введите сообщение..." name="msg" class="amemenu-kamesan"></textarea>';

echo '<div class="block-icon">';
echo '<div class="dropdown">
<a class="drop btn-secondary dropdown-toggle" data-bs-display="static" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="true">
<svg xmlns="http://www.w3.org/2000/svg" width="24px" height="25px" viewBox="0 0 24 24" fill="none">
<path d="M9 16C9.85038 16.6303 10.8846 17 12 17C13.1154 17 14.1496 16.6303 15 16" stroke="" stroke-width="1.5" stroke-linecap="round" fill="none"></path>
<path d="M16 10.5C16 11.3284 15.5523 12 15 12C14.4477 12 14 11.3284 14 10.5C14 9.67157 14.4477 9 15 9C15.5523 9 16 9.67157 16 10.5Z" stroke="none"></path>
<ellipse cx="9" cy="10.5" rx="1" ry="1.5" stroke="none"></ellipse>
<path d="M7 3.33782C8.47087 2.48697 10.1786 2 12 2C17.5228 2 22 6.47715 22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12C2 10.1786 2.48697 8.47087 3.33782 7" stroke="" stroke-width="1.5" stroke-linecap="round" fill="none"></path>
</svg>
</a>
<ul class="dropdown-menu" style="inset: auto auto 0 0;width: 280px;height: 300px;transform: translate( -199px, -46px);padding: 3px;">';
echo '<div class="box" id="myBox">';
echo '<div class="smile-block" style="padding: 9px">';
$sm_p = dbquery("SELECT * FROM `smile_p` ORDER BY `id` ASC");
while ($ap = mfa($sm_p)) {
    echo '<a style="font-weight: bold; font-size: 14px; color: #949494; border-bottom: 0px; padding: 10px 7px; pointer-events: none; display: block;">' . $ap['name'] . '</a>';

    echo '<div class="smiles">';
    $sm_s = dbquery("SELECT * FROM `smile` WHERE `papka` = '" . intval($ap['id']) . "' ORDER BY `id`");
    while ($s = mfa($sm_s)) {
        $sql_papka = "SELECT name FROM smile_p WHERE id = '" . $s['papka'] . "'";
        $result_papka = dbquery($sql_papka);
        $row_papka = mfa($result_papka);
        echo '<a class="smil" href="javascript:void(0);" onclick="DoSmilie(\' ' . $s['name'] . ' \');">
    <img src="/files/smile/' . $row_papka['name'] . '/' . $s['icon'] . '" style="margin: 0px; padding: 5px; width: 30px; height: 30px;">
</a>';
    }
    echo '</div>';
}
echo '</div>';
echo '</div>';
echo '</ui>
</div>';
?>
<a class="drop" href="#" onclick="$('.teg').toggle();return false;">
    <svg xmlns="http://www.w3.org/2000/svg" width="25px" height="23px" viewBox="200 70 700 800" class="icon">
        <path fill="" d="M572.235 205.282v600.365a30.118 30.118 0 11-60.235 0V205.282L292.382 438.633a28.913 28.913 0 01-42.646 0 33.43 33.43 0 010-45.236l271.058-288.045a28.913 28.913 0 0142.647 0L834.5 393.397a33.43 33.43 0 010 45.176 28.913 28.913 0 01-42.647 0l-219.618-233.23z"></path>
    </svg>
</a>

<button class="drop" type="submit" name="submit">
    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="25px" height="30px" viewBox="0 0 28 28" version="1.1">
        <g stroke="none" stroke-width="1" fill="" fill-rule="evenodd">
            <g id="ic_fluent_send_28_filled" fill="" fill-rule="nonzero">
                <path d="M3.78963301,2.77233335 L24.8609339,12.8499121 C25.4837277,13.1477699 25.7471402,13.8941055 25.4492823,14.5168992 C25.326107,14.7744476 25.1184823,14.9820723 24.8609339,15.1052476 L3.78963301,25.1828263 C3.16683929,25.4806842 2.42050372,25.2172716 2.12264586,24.5944779 C1.99321184,24.3238431 1.96542524,24.015685 2.04435886,23.7262618 L4.15190935,15.9983421 C4.204709,15.8047375 4.36814355,15.6614577 4.56699265,15.634447 L14.7775879,14.2474874 C14.8655834,14.2349166 14.938494,14.177091 14.9721837,14.0981464 L14.9897199,14.0353553 C15.0064567,13.9181981 14.9390703,13.8084248 14.8334007,13.7671556 L14.7775879,13.7525126 L4.57894108,12.3655968 C4.38011873,12.3385589 4.21671819,12.1952832 4.16392965,12.0016992 L2.04435886,4.22889788 C1.8627142,3.56286745 2.25538645,2.87569101 2.92141688,2.69404635 C3.21084015,2.61511273 3.51899823,2.64289932 3.78963301,2.77233335 Z"></path>
            </g>
        </g>
    </svg>
</button>
<?
echo '</div>';
?>
<?
echo '<style>
.menu {
	border-radius: var(--all-border-radius);
	border-bottom: 0px;
	margin-top: 15px;
}

.instruction {
	margin-top: 10px;
	line-height: 1.4;
}
</style>';

echo '<div class="menu"><center>Нет доступа к базе данных</center></div>';
echo '<div class="menu"><b style="font-size: 14px;">Варианты решения проблемы: </b><br />
<div class="instruction">
<li>Проверьте введенные данные в config.php</li>
<li>Очистите кэш браузера</li>
<li>Обратитесь к хост-провайдеру вашего хостинга</li>
<li>Удалите config.php чтобы переустановить систему</li>
</div>

</div>';
?>
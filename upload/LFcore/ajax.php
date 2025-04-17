<script>
document.getElementById('saveButton').addEventListener('click', function () {
    const formData = new FormData(document.getElementById('excludedKatForm'));

    fetch('/excluded_kat', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text()) // Получаем текстовый ответ
    .then(data => {
        // После выполнения POST-запроса обновляем содержимое с помощью refreshContent
        window.refreshContent();

        // Удаляем ссылки на стили из обновлённого содержимого
        const themsKat = document.querySelector('.thems-lenta'); // Добавлен правильный селектор
        if (themsKat) {
            const cssLinks = themsKat.querySelectorAll('link[rel="stylesheet"]');
            cssLinks.forEach(link => link.remove());
        }
    })
    .catch(error => console.error('Ошибка:', error));
});
</script>
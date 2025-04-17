const updateCounts = async () => {
    try {
        // Единственный запрос к серверу
        const response = await fetch('/readlen?act=all');
        const data = await response.json();

        // Обновляем количество сообщений
        const messageContainer = document.querySelector('#message-count');
        if (parseInt(data.mes) > 0) {
            messageContainer.innerHTML = `<span class="uv">${data.mes}</span>`;
        } else {
            messageContainer.innerHTML = '';
        }

        // Обновляем значение len
        const lenContainer = document.querySelector('#lenta-count');
        if (parseInt(data.len) > 0) {
            lenContainer.innerHTML = `<span class="uv">${data.len}</span>`;
        } else {
            lenContainer.innerHTML = '';
        }
    } catch (error) {
        console.error('Ошибка при обновлении данных:', error);
    }
};

// Сначала обновляем данные сразу после загрузки страницы
updateCounts();

// Устанавливаем интервал для обновления данных
setInterval(updateCounts, 7000);
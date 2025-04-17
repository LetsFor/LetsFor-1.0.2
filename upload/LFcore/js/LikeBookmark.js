window.onload = function () {   
    // Обработка лайков
    const likeElements = document.querySelectorAll(".likeElement");
    likeElements.forEach(element => {
        const postId = element.getAttribute("data-post-id");
        fetchState(element, `/likes?id=${postId}&getState=true`, updateLikeInterface, 'like', postId);
    });

    // Обработка закладок
    const bookmarkElements = document.querySelectorAll(".bookmarkElement");
    bookmarkElements.forEach(element => {
        const themId = element.getAttribute("data-them-id");
        if (!themId) {
            console.error("Элемент без data-them-id:", element);
            return;
        }
        fetchState(element, `bookmark?id=${themId}&getState=true`, updateBookmarkInterface, 'bookmark', themId);
    });
};

// Универсальная функция для загрузки состояния
function fetchState(element, url, updateInterface, type, id) {
    const xhr = new XMLHttpRequest();
    xhr.open("GET", url, true);

    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            const tempDiv = document.createElement("div");
            tempDiv.innerHTML = xhr.responseText;
            removeStyles(tempDiv);

            if (type === 'like') {
                const response = tempDiv.textContent.split('|');
                const likeCount = response[0];
                const hasLiked = response[1] === '1';
                updateInterface(element, hasLiked, likeCount);
            } else if (type === 'bookmark') {
                const hasBookmarked = tempDiv.textContent === '1';
                updateInterface(element, hasBookmarked);
            }
        }
    };
    xhr.send();

    const buttonSelector = type === 'like' ? ".like" : ".bookmark";
    const button = element.querySelector(buttonSelector);
    button.addEventListener("click", function () {
        toggleAction(element, id, updateInterface, type);
    });
}

// Универсальная функция для обновления состояния
function toggleAction(element, id, updateInterface, type) {
    const xhr = new XMLHttpRequest();
    const url = type === 'like' ? `/likes?id=${id}` : `/bookmark?id=${id}`;
    xhr.open("POST", url, true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            const tempDiv = document.createElement("div");
            tempDiv.innerHTML = xhr.responseText;
            removeStyles(tempDiv);

            if (type === 'like') {
                const response = tempDiv.textContent.split('|');
                const likeCount = response[0];
                const hasLiked = response[1] === '1';
                updateInterface(element, hasLiked, likeCount);
            } else if (type === 'bookmark') {
                const hasBookmarked = tempDiv.textContent === '1';
                updateInterface(element, hasBookmarked);
            }
        }
    };

    const buttonSelector = type === 'like' ? ".like" : ".bookmark";
    const button = element.querySelector(buttonSelector);
    const action = button.innerHTML.includes("far") ? (type === 'like' ? "like" : "add") : (type === 'like' ? "unlike" : "remove");
    xhr.send(`action=${action}`);
}

// Удаление стилей
function removeStyles(container) {
    const cssLinks = container.querySelectorAll('link[rel="stylesheet"]');
    cssLinks.forEach(link => link.remove());
}

// Интерфейс обновления лайков
function updateLikeInterface(element, hasLiked, likeCount) {
    const likeCountElement = element.querySelector(".likeCount");
    if (likeCountElement) {
        likeCountElement.textContent = likeCount;
    }

    const likeButton = element.querySelector(".like");
    if (likeButton) {
        likeButton.innerHTML = hasLiked 
            ? '<span class="ficon_like" style="margin: 0 7px 0 0;"><i class="fas fa-heart"></i></span>' 
            : '<span class="ficon_like" style="margin: 0 7px 0 0;"><i class="far fa-heart"></i></span>';
    }
}

// Интерфейс обновления закладок
function updateBookmarkInterface(element, hasBookmarked) {
    const bookmarkButton = element.querySelector(".bookmark");
    if (bookmarkButton) {
        bookmarkButton.innerHTML = hasBookmarked 
            ? '<span class="ficon_bookmark"><i class="fas fa-star"></i></span><span class="text_in_menu">Из закладок</span>' 
            : '<span class="ficon_bookmark"><i class="far fa-star"></i></span><span class="text_in_menu">В закладки</span>';
    }
}
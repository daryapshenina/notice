window.onload = function(){
    var socket = new WebSocket("ws://localhost:8082");
    var status = document.querySelector("#status");
    socket.onopen = function() {

        status.innerHTML = "cоединение установлено<br>";
    };

    socket.onclose = function(event) {
        if (event.wasClean) {
            status.innerHTML = 'cоединение закрыто';
        } else {
            status.innerHTML = 'соединения как-то закрыто';
        }
        status.innerHTML += '<br>код: ' + event.code + ' причина: ' + event.reason;
    };

    socket.onmessage = function(event) {
        sendNotification('Верните Линуса!', {
            body: "пришли данные " + event.data,
            icon: 'icon.jpg',
            dir: 'auto'
        });

    };

    socket.onerror = function(event) {
        status.innerHTML = "ошибка " + event.message;
    };
    document.forms["messages"].onsubmit = function(){
        var message = {
            name:this.fname.value,
            msg: this.msg.value
        }

        socket.send(JSON.stringify(message));
        return false;
    }


}

function sendNotification(title, options) {
// Проверим, поддерживает ли браузер HTML5 Notifications
    if (!("Notification" in window)) {
        alert('Ваш браузер не поддерживает HTML Notifications, его необходимо обновить.');
    }

// Проверим, есть ли права на отправку уведомлений
    else if (Notification.permission === "granted") {
// Если права есть, отправим уведомление
        var notification = new Notification(title, options);

        function clickFunc() {
            alert('Пользователь кликнул на уведомление');
        }

        notification.onclick = clickFunc;
    }

// Если прав нет, пытаемся их получить
    else if (Notification.permission !== 'denied') {
        Notification.requestPermission(function (permission) {
// Если права успешно получены, отправляем уведомление
            if (permission === "granted") {
                var notification = new Notification(title, options);

            } else {
                alert('Вы запретили показывать уведомления'); // Юзер отклонил наш запрос на показ уведомлений
            }
        });
    } else {
// Пользователь ранее отклонил наш запрос на показ уведомлений
// В этом месте мы можем, но не будем его беспокоить. Уважайте решения своих пользователей.
    }
}
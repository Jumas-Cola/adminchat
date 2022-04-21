$("#send").click(sendMessage);

$("#search").click(search);

$("#txtSearch").keypress(search);

clearInterval(intervalID);
var intervalID = setInterval(() => {
    if (!findGetParameter("page") || findGetParameter("page") == 1) {
        let path = document.location.pathname.split("/");
        updateChatData(path[path.length - 1]);
    }
}, 2000);

function updateChatData(userId) {
    let messagesEl = $("#messages");

    axios.get(`/admin/adminchat/ajax/${userId}`).then((resp) => {
        let messages = resp.data.data;
        messages.sort((a, b) => {
            return a.id - b.id;
        });

        let messagesInner = "";
        messages.forEach(function (message) {
            let messageHtml = '<li class="clearfix">';

            if (message.from_user) {
                messageHtml += `<div class="message-data">
                        <span class="message-data-time">${message.created_at}</span>
                    </div>
                    <div class="message my-message">
                        <div>${message.text}</div>`;
                if (message.file) {
                    messageHtml += `<div>
                            <a href="${message.file}" target="_blank" download>File: ${message.file}</a>                                  
                        </div>`;
                }
                messageHtml += `</div>`;
            } else {
                messageHtml += `<div class="message-data text-right">
                        <span class="message-data-time">${message.created_at}</span>
                    </div>
                    <div class="message other-message float-right">
                        <div>${message.text}</div>`;
                if (message.file) {
                    messageHtml += `<div>
                            <a href="${message.file}" target="_blank" download>File: ${message.file}</a>                                  
                        </div>`;
                }
                messageHtml += `</div>`;
            }
            messageHtml += "</li>";

            messagesInner += messageHtml;
        });
        messagesEl.html(messagesInner);
    });
}

function findGetParameter(parameterName) {
    let result = null,
        tmp = [];
    let items = location.search.substr(1).split("&");
    for (let index = 0; index < items.length; index++) {
        tmp = items[index].split("=");
        if (tmp[0] === parameterName) result = decodeURIComponent(tmp[1]);
    }
    return result;
}

function sendMessage() {
    let path = document.location.pathname.split("/");
    let form = document.getElementById("message-form");
    if (!form.text.value) {
        alert("Text should not be empty!");
        return;
    }
    let data = new FormData(form);

    axios
        .post(`/admin/adminchat/${path[path.length - 1]}`, data)
        .then((resp) => {
            console.log(resp.data);
            form.reset();
            location.reload();
        });
}

function search() {
    let query = $("#txtSearch").val();
    let usersEl = $("#user-list");

    axios.get(`/admin/adminchat/ajax/search?q=${query}`).then((resp) => {
        let users = resp.data;

        let usersInner = "";
        users.forEach((user) => {
            usersInner += `<li class="clearfix">
                               <a href="/admin/adminchat/${user.id}">
                                   <div class="about">
                                       <div class="name">${user.name} ${
                user.surname || ""
            }</div>
                                       <div class="status">${
                                           user.email
                                       }</div>                                            
                                   </div>
                               </a>
                           </li>`;
        });
        usersEl.html(usersInner);
    });
}

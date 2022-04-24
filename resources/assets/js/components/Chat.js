export default {
    data() {
        return {
            messages: [],
            userId: 0,
            page: 1,
            meta: {},
            links: [],
        };
    },
    methods: {
        renewData() {
            fetch(
                `/admin/adminchat/ajax/${this.userId}?page=${this.page}`
            ).then(async (resp) => {
                const data = await resp.json();
                let messages = data.data;

                messages.sort((a, b) => {
                    return a.id - b.id;
                });

                this.messages = messages;
                this.meta = data.meta;
                let links = data.meta.links;
                links.shift();
                links.pop();
                this.links = links;
            });
        },
        intervalRenew() {
            setInterval(this.renewData, 5000);
        },
        sendMessage() {
            const form = this.$refs.messageForm;

            if (!form.text.value) {
                alert("Сообщение не должно быть пустым!");
                return;
            }

            let data = new FormData(form);
            fetch(`/admin/adminchat/${this.userId}`, {
                method: "post",
                body: data,
            }).then(async (resp) => {
                const data = await resp.json();
                form.reset();
                this.renewData();
            });
        },
    },
    mounted() {
        const path = document.location.pathname.split("/");
        this.userId = path[path.length - 1];
        this.renewData();
        this.intervalRenew();
    },
    template: `
        <div class="chat-history">
            <ul id="messages" class="m-b-0">
                <li v-for="message in messages" class="clearfix">
                    <div v-if="message.from_user">
                        <div class="message-data">
                            <span class="message-data-time">{{ message.created_at }}</span>
                        </div>
                        <div class="message my-message">
                            <div>{{ message.text }}</div>
                            <div v-if="message.file">
                                <a :href="message.file" target="_blank" download>File: {{ message.file }}</a>                                  
                            </div>
                        </div>                                    
                    </div>                                    
                    <div v-else>
                        <div class="message-data text-right">
                            <span class="message-data-time">{{ message.created_at }}</span>
                        </div>
                        <div class="message other-message float-right">
                            <div>{{ message.text }}</div>
                            <div v-if="message.file">
                                <a :href="message.file" target="_blank" download>File: {{ message.file }}</a>                                  
                            </div>
                        </div>
                    </div>                                    
                </li>
            </ul>
        </div>
        <nav>
          <ul class="pagination">
            <li class="page-item">
              <a class="page-link" @click="page = +page > 1 ? +page - 1 : 1; renewData()" aria-label="Previous">
                <span aria-hidden="true">&laquo;</span>
                <span class="sr-only">Previous</span>
              </a>
            </li>
            <li v-for="link in links" class="page-item" :class="{ active: +link.label == +page }">
                <a class="page-link" @click="page=link.label; renewData()">{{ link.label }}</a>
            </li>
            <li class="page-item">
              <a class="page-link" @click="page = +page < meta.last_page ? +page + 1 : page; renewData()" aria-label="Next">
                <span aria-hidden="true">&raquo;</span>
                <span class="sr-only">Next</span>
              </a>
            </li>
          </ul>
        </nav>
        <div class="chat-message clearfix">
            <form id="message-form" ref="messageForm">
                <div class="row">
                    <div class="form-group col-md-8">
                        <textarea class="form-control" id="text" name="text" rows="3"></textarea>
                    </div>
                    <div class="col-md-4">
                        <input type="file" class="form-control" id="file" name="file">
                    </div>
                </div>
                <button id="send-message" type="button" class="btn btn-primary mb-2" @click="sendMessage()">
                    <span class="input-group-text"><i class="fa fa-send"></i></span>
                </button>
            </form>
        </div>
  `,
};

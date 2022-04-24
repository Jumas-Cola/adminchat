export default {
    data() {
        return {
            users: [],
            search: "",
        };
    },
    mounted() {
        this.handleSearch();
    },
    methods: {
        handleSearch() {
            fetch(`/admin/adminchat/ajax/search?q=${this.search}`).then(
                async (resp) => {
                    const data = await resp.json();
                    this.users = data;
                }
            );
        },
        openChat(id) {
            window.location.href = `/admin/adminchat/${id}`;
        },
    },
    template: `
        <div id="plist" class="people-list">
            <div class="input-group">
                <input type="text" class="form-control input-group-text" placeholder="Search" v-model="search" @keyup="handleSearch"/>
                <div class="input-group-btn">
                    <button class="btn btn-primary" type="button" id="search">
                        <span class="glyphicon glyphicon-search"></span>
                    </button>
                </div>
            </div>
            <ul class="list-unstyled chat-list mt-2 mb-0" id="user-list">
                <a @click="openChat(user.id)" v-for="user in users" >
                    <li class="clearfix">
                            <img :src="user.image" alt="avatar">
                            <div class="about">
                                <div class="name">{{ user.name }} {{ user.surname }}</div>
                                <div class="status">{{ user.email }}</div>                                            
                                <div class="status"> <i class="fa fa-circle offline"></i> new messages </div>
                            </div>
                    </li>
                </a>
            </ul>
        </div>
  `,
};

import ChatHeader from "./components/ChatHeader.js";
import Chat from "./components/Chat.js";
import Search from "./components/Search.js";

const app = Vue.createApp({});
app.component("chat-header", ChatHeader).mount("#chat-header");
Vue.createApp(Chat).mount("#chat");
Vue.createApp(Search).mount("#search");

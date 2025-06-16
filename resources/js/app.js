import { createApp } from "vue";

import App from "./App.vue";
// import router from "./router";
import { OhVueIcon, addIcons } from 'oh-vue-icons';
import { MdSaveas } from "oh-vue-icons/icons";
addIcons(MdSaveas)
const app = createApp(App);
app.component('v-icon', OhVueIcon);
// app.use(router);
app.mount("#app");


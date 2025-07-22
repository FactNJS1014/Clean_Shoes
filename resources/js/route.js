import { createRouter, createWebHistory } from "vue-router";
import Setting_Wrist from './components/Wristtap.vue'
import HistoryCheck from "./components/HistoryCheck.vue";
import HistoryBySection from "./components/HistoryCheckBySection.vue"


const routes = [
    {
        path: "/",
        name: "index",
        component: Setting_Wrist,
    },
    {
        path: "/historyAll",
        name: "history1",
        component: HistoryCheck,
    },
    {
        path: "/historybysection",
        name: "history2",
        component: HistoryBySection,
    }
]

const router = createRouter({
  history: createWebHistory('Cleaning_Shoes'),
  routes
});

export default router;

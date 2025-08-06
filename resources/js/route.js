import { createRouter, createWebHistory } from "vue-router";
// import Setting_Wrist from './components/Wristtap.vue'
import HistoryCheck from "./components/HistoryCheck.vue";
import HistoryBySection from "./components/HistoryCheckBySection.vue"
import Dashboard from "./components/Dashboard.vue";


const routes = [

    {
        path: "/",
        name: "dashboard",
        component: Dashboard,
    },
    {
        path: "/historycheck",
        name: "historycheck",
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

import { createApp } from "vue";
import router from "./route.js";

import App from "./App.vue";
import PrimeVue from 'primevue/config';
import Aura from '@primeuix/themes/aura';


import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import ColumnGroup from 'primevue/columngroup';   // optional
import Row from 'primevue/row';                   // optional
import Tag from 'primevue/tag';
import DatePicker from 'primevue/datepicker';
import Select from 'primevue/select';




// import router from "./router";
import { OhVueIcon, addIcons } from 'oh-vue-icons';
import { MdSaveas , FcSettings ,FaHistory ,CiColorIcn,BiCalendarDateFill ,BiTable   } from "oh-vue-icons/icons";
addIcons(MdSaveas,FcSettings,FaHistory,CiColorIcn ,BiCalendarDateFill ,BiTable )
const app = createApp(App);
app.component('v-icon', OhVueIcon);
app.use(PrimeVue, {
    theme: {
        preset: Aura
    }
});
app.component('DataTable',DataTable)
app.component('Column', Column)
app.component('ColumnGroup', ColumnGroup)
app.component('Row', Row)
app.component('Tag',Tag)
app.component('DatePicker',DatePicker)
app.component('Select',Select)
app.use(router);
app.mount("#app");


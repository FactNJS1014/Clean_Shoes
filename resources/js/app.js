import { createApp } from "vue";
import router from "./route.js";

import App from "./App.vue";
import PrimeVue from 'primevue/config';
import Aura from '@primeuix/themes/aura';
import { definePreset } from '@primeuix/themes';

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
const MyPreset = definePreset(Aura, {
    //Your customizations, see the following sections for examples
    semantic: {
        primary: {
            50: '{zinc.50}',
            100: '{zinc.100}',
            200: '{zinc.200}',
            300: '{zinc.300}',
            400: '{zinc.400}',
            500: '{zinc.500}',
            600: '{zinc.600}',
            700: '{zinc.700}',
            800: '{zinc.800}',
            900: '{zinc.900}',
            950: '{zinc.950}'
        }
    },

});

app.use(PrimeVue, {
    theme: {
        preset: MyPreset
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


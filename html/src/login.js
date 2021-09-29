import { createApp } from 'vue';
import ElementPlus from 'element-plus';
import locale from 'element-plus/lib/locale/lang/tr';
import subutai from './lib/SubutaiVue';

import comp from './components/Login.vue';

let app = document.getElementById("app");
app.innerHTML = "";
createApp(comp).use(ElementPlus, { locale }).use(subutai).mount(app);





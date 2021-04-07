import { createApp } from 'vue'
import login from './Login.vue'
import ElementPlus from 'element-plus'
import locale from 'element-plus/lib/locale/lang/tr'
import subutai from './lib/SubutaiVue';

createApp(login).use(ElementPlus, { locale }).use(subutai).mount('#app')
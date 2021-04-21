import { createApp } from 'vue'
import welcome from './components/Welcome.vue';
import ElementPlus from 'element-plus'
import locale from 'element-plus/lib/locale/lang/tr'
import subutai from './lib/SubutaiVue';

createApp(welcome).use(ElementPlus, { locale }).use(subutai).mount('#app')
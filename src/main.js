import { createApp } from 'vue';
import main from './components/Main.vue';
import ElementPlus from 'element-plus';
import locale from 'element-plus/lib/locale/lang/tr';

createApp(main).use(ElementPlus, { locale }).mount('#app')

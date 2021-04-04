import { createApp } from 'vue'
import login from './Login.vue'
import ElementPlus from 'element-plus'
import locale from 'element-plus/lib/locale/lang/tr'

createApp(login).use(ElementPlus, { locale }).mount('#app')
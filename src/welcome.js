import { createApp } from 'vue'
import welcome from './components/Welcome.vue';
import ElementPlus from 'element-plus'
import locale from 'element-plus/lib/locale/lang/tr'
import subutai from './lib/SubutaiVue';

window["__INIT__"] = (data) => {
    document.body.innerHTML = "";
    document.body.style.margin = "0";
    document.body.style.padding = "0";
    var container = document.createElement("div");
    document.body.appendChild( container );
    window["__DATA__"] = (data ? data : null);
    createApp(welcome).use(ElementPlus, { locale }).use(subutai).mount(container);
}
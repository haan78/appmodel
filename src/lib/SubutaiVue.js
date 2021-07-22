import Subutai from "./Subutai";
export default {
    install: (app) => {
        // Plugin code goes here
        app.config.globalProperties.$subutai = Subutai;
    }
}
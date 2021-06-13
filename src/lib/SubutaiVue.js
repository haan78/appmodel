import Subutai from "./Subutai";
export default {
    install: (app) => {
        // Plugin code goes here
        Subutai.__init__();
        app.config.globalProperties.$subutai = Subutai;
    }
}
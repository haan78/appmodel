import cd from './lib/ClearDom';

window.INIT = data=>{
    setTimeout( ()=>{
        let app = cd();
        app.innerHTML = "Tamam";
    },4000 );    
    console.log(document.cookie);
    console.log(data);
};
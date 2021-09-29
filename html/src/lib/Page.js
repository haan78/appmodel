export default (options) =>{
    let defop = (options) =>{
        var def = {
            meta:[],
            preload:[],
            css:[],
            js:[],
            icon:[],
            id:"app",
            tagName:"div"
        };
        if ( typeof options === "object" && options !== null ) {
            for(var k in def) {
                if (typeof options[k] == typeof def[k]) {
                    def[k] = options[k];
                }
            }
        }
        return def;
    };

    return {
        "options": defop(options),
        "rnd":rnd = Math.random().toString(36).substring(7),
        "css":(path,preload)=>{
            if (preload) {
                this.options.preload.push({
                    "tag" : "link",
                    "attributes":{
                        "href" : path,
                        "rel" : "preload",
                        "as" : "style"
                    }                    
                });
            }
            this.options.css.push({
                "tag" : "link",
                "attributes":{
                    "href" : path,
                    "rel" : "stylesheet"
                }                    
            });
            return this;
        },
        "js":(path,preload) => {
            if (preload) {
                this.options.preload.push({
                    "tag" : "link",
                    "attributes":{
                        "href" : path,
                        "rel" : "preload",
                        "as" : "script"
                    }                    
                });
            }
            this.options.js.push({
                "tag" : "script",
                "attributes":{
                    "src" : path
                }                    
            });
            return this;
        },
        "meta":(attributes)=>{
            this.options.meta.push({
                "tag" : "meta",
                "attributes":attributes              
            });
            return this;
        },
        "icon":(path,attributes) => {
            var att = attributes;
            att.href = path;
            this.options.icon.push({
                "tag":"link",
                "attributes":attributes
            });
            return this;
        }

        /*var head = document.getElementsByTagName('head')[0];
        var container = document.createElement(op.tagName);
        document.body.innerHTML = "";
        container.id = op.id;
        document.body.appendChild( container );
        document.body.style.margin = "0";
        document.body.style.padding = "0";*/
    }
}
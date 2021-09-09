import axios from "axios";

export default {
    useDataTransform:true,
    ajaxActivationCount: 0,
    COOKIE_NAME : "SUBUTAI",
    COOKIE_DATA : false,

    onStart : function() {},
    onEnd : function() {},

    isLoading() {
        return this.ajaxActivationCount > 0;
    },

    get(options) {
        var op = {
            "dontremove":false,
            "refresh":false
        };

        if ( typeof options == "object" && typeof options !== null ) {
            Object.keys(op).forEach((key)=>{
                if ( options.hasOwnProperty(key) ) {
                    op[key] = options[key];
                }
            });
        }        
        
        if ( op.refresh || this.COOKIE_DATA === false ) {
            this.COOKIE_DATA = this.cookie(op.dontremove);
        }
        return this.COOKIE_DATA;
    },

    cookie(dontremove) {
        const value = "; " + window.document.cookie;
        const parts = value.split("; " + this.COOKIE_NAME + "=");
        if (parts.length == 2) {
            const vlu = parts.pop().split(";").shift();
            const decode_vlu = decodeURIComponent(vlu);
            const replace_vlu = decode_vlu.replace(/[+]/g, ' ');
            if (!dontremove) {
                window.document.cookie = encodeURIComponent(this.COOKIE_NAME) + "=; Max-Age=0";
            }
            
            var result = false;
            try {
                result = JSON.parse(replace_vlu);
            } catch {
                result = replace_vlu;
            }            
            return result;
        } else {
            return false;
        }
    },

    ajaxDefaultError: (msg, details) => {
        console.log([msg, details]);
    },

    up() {        
        if (this.ajaxActivationCount === 0) {
            if (  typeof this.onStart ===  "function") {
                this.onStart();
            }
        } 
        this.ajaxActivationCount += 1;       
    },

    down() {
        if ( this.ajaxActivationCount > 0 ) {
            this.ajaxActivationCount -= 1;       
        } 
        if ( this.ajaxActivationCount === 0 ) {
            if (  typeof this.onEnd ===  "function") {
                this.onEnd();
            }
        }
    },

    dataTransform(data) {
        if ( typeof data === "object" && data !== null ) {
            for( var k in data ) {
                data[k] = this.dataTransform( data[k] );
            }
        } else if (typeof data === "string" ) {
            var str = data.trim();
            if ( str === "" ) {
                return null;
            } else {
                return str;
            }
        }
        return data;
    },

    ajax(url, data, onSuccess, onError) {
        let self = this;
        let config = {};
        var err = (typeof onError === "function" ? onError : self.ajaxDefaultError);
        self.up();
        var d = ( typeof data !== "undefined" ? this.dataTransform(data) : null );
        axios.post(url, d, config).then((response) => {            
            if (typeof response.data === "object" && typeof response.data.success === "boolean") {
                if (response.data.success) {
                    if (typeof onSuccess === "function") {
                        onSuccess(response.data.data);
                    }
                } else {
                    err(response.data.data.message, response.data);
                }
            } else {
                err("Ajax response is unexpected", response.data);
            }
            self.down();
        }).catch((error) => {
            err(error.message, error);
            self.down();
        });
    }
}
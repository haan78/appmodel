import axios from "axios";

export default {
    ajaxActivationCount: 0,
   
    isLoading() {
        return this.ajaxActivationCount > 0;
    },

    cookie(name,dontremove) {
        const value = "; " + document.cookie;
            const parts = value.split("; " + name + "=");
            if (parts.length == 2) {
                const vlu = parts.pop().split(";").shift();
                const decode_vlu = decodeURIComponent(vlu);
                const replace_vlu = decode_vlu.replace(/[+]/g, ' ');
                if ( !dontremove ) {
                    window.document.cookie = encodeURIComponent(name)+"=; Max-Age=0";
                }
                return JSON.parse(replace_vlu);
            } else {
                return false;
            }
    },

    ajaxDefaultError:(msg, details) => {
        console.log([msg,details]);
    },

    ajax(url, data, onSuccess, onError) {
        let self = this;
        let config = {};
        var err = (typeof onError === "function" ? onError : self.ajaxDefaultError);
        self.ajaxActivationCount += 1;
        axios.post(url, (data ? data : null), config).then((response) => {
            if (self.ajaxActivationCount > 0) {
                self.ajaxActivationCount -= 1;
            }
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
        }).catch((error) => {
            if (self.ajaxActivationCount > 0) {
                self.ajaxActivationCount -= 1;
            }
            err(error.message, error);
        });
    }
}
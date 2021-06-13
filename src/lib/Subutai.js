import axios from "axios";

export default {
    ajaxActivationCount: 0,
    __TICKET__ : "",

    __init__() {
        this.__TICKET__ = this.cookie("TICKET");
        //console.log(this.__TICKET__);
    },
    isLoading() {
        return this.ajaxActivationCount > 0;
    },

    defaultError(msg, details) {
        console.log(details);
    },

    cookie(name) {
        //return document.cookie.match('(^|;)\\s*' + name + '\\s*=\\s*([^;]+)')?.pop() || ''
        const value = "; " + document.cookie;
        const parts = value.split("; " + name + "=");
        if (parts.length == 2) {
            const vlu = parts.pop().split(";").shift();
            const decode_vlu = decodeURIComponent(vlu);
            const replace_vlu = decode_vlu.replace(/[+]/g, ' ');
            return replace_vlu;
        }
        else
            return '';
    },

    ajax(url, data, onSuccess, onError) {

        let self = this;
        let config = { headers: { "TICKET": self.__TICKET__ } };
        var err = (typeof onError === "function" ? onError : self.defaultError);
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
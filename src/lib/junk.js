export default {
    __decodeBase64(s) {
        var e = {}, i, b = 0, c, x, l = 0, a, r = '', w = String.fromCharCode, L = s.length;
        var A = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/";
        for (i = 0; i < 64; i++) { e[A.charAt(i)] = i; }
        for (x = 0; x < L; x++) {
            c = e[s.charAt(x)]; b = (b << 6) + c; l += 6;
            while (l >= 8) { ((a = (b >>> (l -= 8)) & 0xff) || (x < (L - 2))) && (r += w(a)); }
        }
        return r;
    },
    cloneObject(source,target) {
        if ( typeof target === "object" && target !== null ) {
            for (var k in target) {
                if ( typeof target[k] === "object" && target[k] !== null ) {
                    this.cloneObject((typeof source[k] !== "undefined" ? source[k] : null),target[k]);
                } else {
                    target[k] = ( source && typeof source[k] !== "undefined" ? source[k] : null);
                }
            }
        }
    }
}
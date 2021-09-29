/******/ (function(modules) { // webpackBootstrap
/******/ 	// install a JSONP callback for chunk loading
/******/ 	function webpackJsonpCallback(data) {
/******/ 		var chunkIds = data[0];
/******/ 		var moreModules = data[1];
/******/ 		var executeModules = data[2];
/******/
/******/ 		// add "moreModules" to the modules object,
/******/ 		// then flag all "chunkIds" as loaded and fire callback
/******/ 		var moduleId, chunkId, i = 0, resolves = [];
/******/ 		for(;i < chunkIds.length; i++) {
/******/ 			chunkId = chunkIds[i];
/******/ 			if(Object.prototype.hasOwnProperty.call(installedChunks, chunkId) && installedChunks[chunkId]) {
/******/ 				resolves.push(installedChunks[chunkId][0]);
/******/ 			}
/******/ 			installedChunks[chunkId] = 0;
/******/ 		}
/******/ 		for(moduleId in moreModules) {
/******/ 			if(Object.prototype.hasOwnProperty.call(moreModules, moduleId)) {
/******/ 				modules[moduleId] = moreModules[moduleId];
/******/ 			}
/******/ 		}
/******/ 		if(parentJsonpFunction) parentJsonpFunction(data);
/******/
/******/ 		while(resolves.length) {
/******/ 			resolves.shift()();
/******/ 		}
/******/
/******/ 		// add entry modules from loaded chunk to deferred list
/******/ 		deferredModules.push.apply(deferredModules, executeModules || []);
/******/
/******/ 		// run deferred modules when all chunks ready
/******/ 		return checkDeferredModules();
/******/ 	};
/******/ 	function checkDeferredModules() {
/******/ 		var result;
/******/ 		for(var i = 0; i < deferredModules.length; i++) {
/******/ 			var deferredModule = deferredModules[i];
/******/ 			var fulfilled = true;
/******/ 			for(var j = 1; j < deferredModule.length; j++) {
/******/ 				var depId = deferredModule[j];
/******/ 				if(installedChunks[depId] !== 0) fulfilled = false;
/******/ 			}
/******/ 			if(fulfilled) {
/******/ 				deferredModules.splice(i--, 1);
/******/ 				result = __webpack_require__(__webpack_require__.s = deferredModule[0]);
/******/ 			}
/******/ 		}
/******/
/******/ 		return result;
/******/ 	}
/******/
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// object to store loaded and loading chunks
/******/ 	// undefined = chunk not loaded, null = chunk preloaded/prefetched
/******/ 	// Promise = chunk loading, 0 = chunk loaded
/******/ 	var installedChunks = {
/******/ 		"activate": 0
/******/ 	};
/******/
/******/ 	var deferredModules = [];
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "/";
/******/
/******/ 	var jsonpArray = window["webpackJsonp"] = window["webpackJsonp"] || [];
/******/ 	var oldJsonpFunction = jsonpArray.push.bind(jsonpArray);
/******/ 	jsonpArray.push = webpackJsonpCallback;
/******/ 	jsonpArray = jsonpArray.slice();
/******/ 	for(var i = 0; i < jsonpArray.length; i++) webpackJsonpCallback(jsonpArray[i]);
/******/ 	var parentJsonpFunction = oldJsonpFunction;
/******/
/******/
/******/ 	// add entry module to deferred list
/******/ 	deferredModules.push([0,"chunk-vendors"]);
/******/ 	// run deferred modules when ready
/******/ 	return checkDeferredModules();
/******/ })
/************************************************************************/
/******/ ({

/***/ "./node_modules/cache-loader/dist/cjs.js?!./node_modules/babel-loader/lib/index.js!./node_modules/vue-loader-v16/dist/templateLoader.js?!./node_modules/cache-loader/dist/cjs.js?!./node_modules/vue-loader-v16/dist/index.js?!./src/components/Activate.vue?vue&type=template&id=3ecb78ba":
/*!******************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/cache-loader/dist/cjs.js??ref--12-0!./node_modules/babel-loader/lib!./node_modules/vue-loader-v16/dist/templateLoader.js??ref--6!./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader-v16/dist??ref--0-1!./src/components/Activate.vue?vue&type=template&id=3ecb78ba ***!
  \******************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! exports provided: render */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "render", function() { return render; });
function render(_ctx, _cache) {
  return " Activate ";
}

/***/ }),

/***/ "./src/activate.js":
/*!*************************!*\
  !*** ./src/activate.js ***!
  \*************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var vue__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! vue */ "./node_modules/vue/dist/vue.runtime.esm-bundler.js");
/* harmony import */ var element_plus__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! element-plus */ "./node_modules/element-plus/es/index.js");
/* harmony import */ var element_plus_lib_locale_lang_tr__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! element-plus/lib/locale/lang/tr */ "./node_modules/element-plus/lib/locale/lang/tr.js");
/* harmony import */ var element_plus_lib_locale_lang_tr__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(element_plus_lib_locale_lang_tr__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _lib_SubutaiVue__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./lib/SubutaiVue */ "./src/lib/SubutaiVue.js");
/* harmony import */ var _components_Activate_vue__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./components/Activate.vue */ "./src/components/Activate.vue");





let app = document.getElementById("app");
app.innerHTML = "";
Object(vue__WEBPACK_IMPORTED_MODULE_0__["createApp"])(_components_Activate_vue__WEBPACK_IMPORTED_MODULE_4__["default"]).use(element_plus__WEBPACK_IMPORTED_MODULE_1__["default"], {
  locale: (element_plus_lib_locale_lang_tr__WEBPACK_IMPORTED_MODULE_2___default())
}).use(_lib_SubutaiVue__WEBPACK_IMPORTED_MODULE_3__["default"]).mount(app);

/***/ }),

/***/ "./src/components/Activate.vue":
/*!*************************************!*\
  !*** ./src/components/Activate.vue ***!
  \*************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _Activate_vue_vue_type_template_id_3ecb78ba__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./Activate.vue?vue&type=template&id=3ecb78ba */ "./src/components/Activate.vue?vue&type=template&id=3ecb78ba");
/* harmony import */ var _app_node_modules_vue_loader_v16_dist_exportHelper_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./node_modules/vue-loader-v16/dist/exportHelper.js */ "./node_modules/vue-loader-v16/dist/exportHelper.js");
/* harmony import */ var _app_node_modules_vue_loader_v16_dist_exportHelper_js__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_app_node_modules_vue_loader_v16_dist_exportHelper_js__WEBPACK_IMPORTED_MODULE_1__);

const script = {}


const __exports__ = /*#__PURE__*/_app_node_modules_vue_loader_v16_dist_exportHelper_js__WEBPACK_IMPORTED_MODULE_1___default()(script, [['render',_Activate_vue_vue_type_template_id_3ecb78ba__WEBPACK_IMPORTED_MODULE_0__["render"]],['__file',"src/components/Activate.vue"]])
/* hot reload */
if (false) {}


/* harmony default export */ __webpack_exports__["default"] = (__exports__);

/***/ }),

/***/ "./src/components/Activate.vue?vue&type=template&id=3ecb78ba":
/*!*******************************************************************!*\
  !*** ./src/components/Activate.vue?vue&type=template&id=3ecb78ba ***!
  \*******************************************************************/
/*! exports provided: render */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_cache_loader_dist_cjs_js_ref_12_0_node_modules_babel_loader_lib_index_js_node_modules_vue_loader_v16_dist_templateLoader_js_ref_6_node_modules_cache_loader_dist_cjs_js_ref_0_0_node_modules_vue_loader_v16_dist_index_js_ref_0_1_Activate_vue_vue_type_template_id_3ecb78ba__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../node_modules/cache-loader/dist/cjs.js??ref--12-0!../../node_modules/babel-loader/lib!../../node_modules/vue-loader-v16/dist/templateLoader.js??ref--6!../../node_modules/cache-loader/dist/cjs.js??ref--0-0!../../node_modules/vue-loader-v16/dist??ref--0-1!./Activate.vue?vue&type=template&id=3ecb78ba */ "./node_modules/cache-loader/dist/cjs.js?!./node_modules/babel-loader/lib/index.js!./node_modules/vue-loader-v16/dist/templateLoader.js?!./node_modules/cache-loader/dist/cjs.js?!./node_modules/vue-loader-v16/dist/index.js?!./src/components/Activate.vue?vue&type=template&id=3ecb78ba");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_cache_loader_dist_cjs_js_ref_12_0_node_modules_babel_loader_lib_index_js_node_modules_vue_loader_v16_dist_templateLoader_js_ref_6_node_modules_cache_loader_dist_cjs_js_ref_0_0_node_modules_vue_loader_v16_dist_index_js_ref_0_1_Activate_vue_vue_type_template_id_3ecb78ba__WEBPACK_IMPORTED_MODULE_0__["render"]; });



/***/ }),

/***/ "./src/lib/Subutai.js":
/*!****************************!*\
  !*** ./src/lib/Subutai.js ***!
  \****************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _SubutaiHttp__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./SubutaiHttp */ "./src/lib/SubutaiHttp.js");
/* harmony import */ var _SubutaiCookie__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./SubutaiCookie */ "./src/lib/SubutaiCookie.js");


/* harmony default export */ __webpack_exports__["default"] = ({
  "cookie": _SubutaiCookie__WEBPACK_IMPORTED_MODULE_1__["default"],
  "http": _SubutaiHttp__WEBPACK_IMPORTED_MODULE_0__["default"]
});

/***/ }),

/***/ "./src/lib/SubutaiCookie.js":
/*!**********************************!*\
  !*** ./src/lib/SubutaiCookie.js ***!
  \**********************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony default export */ __webpack_exports__["default"] = ({
  COOKIE_NAME: "SUBUTAI",
  COOKIE_DATA: false,

  get(options) {
    var op = {
      "removeAfterRead": false,
      "refresh": false
    };

    if (typeof options === "object" && options !== null) {
      Object.keys(op).forEach(key => {
        if (typeof options[key] !== "undefined") {
          op[key] = options[key];
        }
      });
    }

    if (op.refresh || this.COOKIE_DATA === false) {
      this.COOKIE_DATA = this.__cookie(op.removeAfterRead);
    }

    return this.COOKIE_DATA;
  },

  __remove() {
    window.document.cookie = encodeURIComponent(this.COOKIE_NAME) + "=; Max-Age=0;";
  },

  __cookie(remove) {
    const value = "; " + window.document.cookie;
    const parts = value.split("; " + this.COOKIE_NAME + "="); //console.log(parts);

    if (parts.length == 2) {
      const vlu = parts.pop().split(";").shift();
      const decode_vlu = decodeURIComponent(vlu);
      const replace_vlu = decode_vlu.replace(/[+]/g, ' ');

      if (remove) {
        this.__remove();
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
  }

});

/***/ }),

/***/ "./src/lib/SubutaiHttp.js":
/*!********************************!*\
  !*** ./src/lib/SubutaiHttp.js ***!
  \********************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony default export */ __webpack_exports__["default"] = ({
  default: {
    charset: "UTF-8",
    useDataTransform: true
  },
  activeRequestCount: 0,
  onStart: function () {},
  onStop: function () {},

  isLoading() {
    return this.activeRequestCount > 0;
  },

  validate(response) {
    if (typeof response === "object" && typeof response.success === "boolean" && typeof response.data !== "undefined") {
      return true;
    } else {
      return false;
    }
  },

  up() {
    if (this.activeRequestCount === 0) {
      if (typeof this.onStart === "function") {
        this.onStart();
      }
    }

    this.activeRequestCount += 1;
  },

  down() {
    if (this.activeRequestCount > 0) {
      this.activeRequestCount -= 1;
    }

    if (this.activeRequestCount === 0) {
      if (typeof this.onStop === "function") {
        this.onStop();
      }
    }
  },

  dataTransform(data, level) {
    var l = level ? level : 0;

    if (data === null) {
      return null;
    } else if (typeof data === "object") {
      //object
      if (Array.isArray(data)) {
        var arr = [];

        for (var i = 0; i < data.length; i++) {
          arr.push(this.dataTransform(data[i]), l + 1);
        }

        return arr;
      } else {
        var obj = {};

        for (var k in data) {
          obj[k] = this.dataTransform(data[k], l + 1);
        }

        return obj;
      }
    } else if (typeof data === "function") {
      return this.dataTransform(data(l), l + 1);
    } else if (typeof data === "string") {
      var str = data.trim();

      if (str === "") {
        return null;
      } else {
        return str;
      }
    } else if (data instanceof Date) {
      return data.toISOString();
    } else {
      return data;
    }
  },

  request(url, data, settings) {
    let self = this;
    let sett = self.default;

    if (typeof settings === "object" && settings !== null) {
      for (var k in self.default) {
        if (typeof settings[k] !== "undefined") {
          sett[k] = settings[k];
        }
      }
    }

    return new Promise((resolve, reject) => {
      var HTTP = new XMLHttpRequest();

      HTTP.onreadystatechange = () => {
        if (HTTP.readyState === 4) {
          if (HTTP.status === 200) {
            var response;

            try {
              response = JSON.parse(HTTP.responseText);
            } catch (ex) {
              reject(ex, HTTP.responseText);
              return;
            }

            if (self.validate(response)) {
              if (response.success) {
                resolve(response.data);
              } else {
                var msg = response.data.message ? response.data.message : "Undefined error message";
                reject(new Error(msg), response.data);
              }
            } else {
              reject(new Error("Response is unexpected"), response);
            }
          } else {
            reject(new Error(HTTP.statusText), HTTP.status);
          }

          self.down();
        }
      };

      HTTP.open("POST", url, true);
      HTTP.setRequestHeader("Content-type", "application/json;charset=" + sett.charset);
      var d;

      if (sett.useDataTransform) {
        d = self.dataTransform(data);
      } else {
        d = data;
      }

      self.up();
      HTTP.send(JSON.stringify(d));
    });
  }

});

/***/ }),

/***/ "./src/lib/SubutaiVue.js":
/*!*******************************!*\
  !*** ./src/lib/SubutaiVue.js ***!
  \*******************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _Subutai__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./Subutai */ "./src/lib/Subutai.js");

/* harmony default export */ __webpack_exports__["default"] = ({
  install: app => {
    // Plugin code goes here
    app.config.globalProperties.$subutai = _Subutai__WEBPACK_IMPORTED_MODULE_0__["default"];
  }
});

/***/ }),

/***/ 0:
/*!*******************************!*\
  !*** multi ./src/activate.js ***!
  \*******************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! /app/src/activate.js */"./src/activate.js");


/***/ })

/******/ });
//# sourceMappingURL=activate.js.map
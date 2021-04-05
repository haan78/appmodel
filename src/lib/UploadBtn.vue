<template>
  <label style="cursor: pointer" :title="title">
    <input
      ref="uploadField"
      type="file"
      @change="change()"
      style="display: none"
      :accept="accept"
    />
    <slot></slot>
  </label>
</template>
<style lang="scss" scoped>
$colorhover : dodgerblue;
$colorfront : black;
$colorback : white;
$minborder : 0.1em;

label {
  background-color: $colorback;
  display: inline-block;
  font-size: 14px;
  white-space: nowrap;
  color: $colorfront;
  padding: 5 * $minborder;
  border: $minborder solid $colorfront;
  border-radius: 7 * $minborder;

  &:hover {
    border-color: $colorhover;
    color: $colorhover;
  }
}
</style>

<script>
export default {
  props: {
    fieldPrefix: {
      type: String,
      default: "file",
    },
    limit: {
      type: Number,
      default: 1,
    },
    title: {
      type: String,
      default: null,
    },
    accept: {
      type: String,
      default: "*/*",
    },
  },
  data() {
    return {};
  },
  emits: ["request","error"],
  methods: {
    change() {
      let self = this;
      var input = self.$refs.uploadField;
      
      if ( input.files.length > self.limit ) {
        self.$emit("error",new Error("Maximum file upload limit is "+self.limit));
      }
      
      if (input.files.length > 0) {
        var fileNames = [];
        const formData = new FormData();
        if ( input.files.length > 1 ) {
          for (var i = 0; i < input.files.length; i++) {
            fileNames.push(input.files[i].name);
            formData.append(self.fieldPrefix + (i + 1), input.files[i]);
          }
        } else { //if its is only one
          fileNames.push(input.files[0].name);
          formData.append(self.fieldPrefix, input.files[0]);
        }
        
        self.$emit("request", self.factory(formData, fileNames));
      }
    },
    factory(formData, fileNames) {
      return {
        limit: this.limit,
        formData: formData,
        fileNames: fileNames,
        send(url, data, callback) {
          if (typeof data === "object" && data !== null) {
            for (var k in data) {
              this.formData.append(k, data[k]);
            }
          }
          var request = new XMLHttpRequest();
          if (typeof callback === "function") {
            request.onreadystatechange = function () {
              if (this.readyState == 4 && this.status == 200) {
                callback(this.response,this.responseType);
              }
            };
            request.open("POST", url, true);
          } else {
            request.open("POST", url);
          }

          request.send(this.formData);
        },
      };
    }
  }
}
</script>
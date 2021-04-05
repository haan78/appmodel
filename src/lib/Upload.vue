<template>
    <label style="cursor: pointer;" :title="title">
      
      <input
        ref="uploadField"
        type="file"
        @change="change()"
        style="display: none"
        :accept="accept"
      />
      <span style="displa">&#128228;&nbsp;{{ caption }}</span>
    </label>
</template>

<style scoped>
  label {
    
    background-color: white;
    
    font-size: small;
  }
  label span:hover {
    border-color: dodgerblue;
  }

  label span  {
    display: inline;
    padding: 0.5em;
    border: 2px solid black;
    display: inline-block;
  }
</style>

<script>
export default {
  props: {
    fieldPrefix: {
      type: String,
      default: "file_",
    },
    limit: {
      type: Number,
      default: 1,
    },
    title: {
      type: String,
      default: null
    },
    caption:{
      type: String,
      default:""
    },
    accept:{
      type: String,
      default: "*/*"
    }
  },
  data() {
    return {
    };
  },
  emits: ["request"],
  methods: {
    change() {
      let self = this;
      var input = self.$refs.uploadField;
      var fileNames = [];
      if (input.files.length > 0) {
        const formData = new FormData();
        for (var i = 0; i < input.files.length; i++) {
          fileNames.push(input.files[i].name);
          formData.append(self.fieldPrefix + (i + 1), input.files[i]);
        }
        self.$emit("request", self.factory(formData,fileNames));
      }
    },
    factory(formData,fileNames) {
      return {
        limit: this.limit,
        formData: formData,
        "fileNames": fileNames,
        send(url, data, callback) {
          if (typeof data === "object" && data !== null) {
            var count = 0;
            for (var k in data) {
              this.formData.append(k, data[k]);
              count++;
              if (count > this.limit) {
                if ( typeof callback === "function" ) {
                  callback(new Error("Maximum file limit is "+this.limit));
                }
                return;
              }
            }
          }
          var request = new XMLHttpRequest();
          if (typeof callback === "function") {
            request.onreadystatechange = function () {
              if (this.readyState == 4 && this.status == 200) {
                callback(this.responseText);
              }
            };
            request.open("POST", url, true);
          } else {
            request.open("POST", url);
          }

          request.send(this.formData);
        },
      };
    },
  },
};
</script>
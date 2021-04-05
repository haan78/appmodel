<template>
    <label style="cursor: pointer;">
      <input
        ref="uploadField"
        type="file"
        @change="change()"
        style="display: none"
      />
      <slot></slot>
    </label>
</template>

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
    }
  },
  data() {
    return {};
  },
  emits: ["request"],
  methods: {
    change() {
      let self = this;
      var input = self.$refs.uploadField;
      if (input.files.length > 0) {
        const formData = new FormData();
        for (var i = 0; i < input.files.length; i++) {
          formData.append(self.fieldPrefix + (i + 1), input.files[i]);
        }
        self.$emit("request", self.factory(formData));
      }
    },
    factory(formData) {
      return {
        limit: this.limit,
        formData: formData,
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
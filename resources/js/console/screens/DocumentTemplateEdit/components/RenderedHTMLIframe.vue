<template>
  <iframe v-bind="$attrs" @load="renderChildren" />
</template>

<script>
import {defineComponent} from "vue";

export default defineComponent({
  name: 'RenderedHTMLIframe',
  props: {
    html: {
      type: String,
      required: true,
    },
  },
  watch: {
    html() {
      // next tick
      setTimeout(() => {
        console.log('aa');
        this.renderChildren();
      }, 0);
    },
  },
  methods: {
    renderChildren() {
      const body = this.$el.contentDocument.body;

      body.innerHTML = this.html;
    },
  },
});
</script>

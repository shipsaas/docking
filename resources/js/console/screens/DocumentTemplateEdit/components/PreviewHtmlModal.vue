<template>
  <Modal
    :is-open="isOpen"
    title="Preview HTML"
    width-size="5xl"
  >
    <div class="my-2 border-t"></div>
    <RenderedHTMLIframe :html="html" height="500px" width="100%" />
    <template #bottom-buttons>
      <Button @click="$emit('close')">Close</Button>
    </template>
  </Modal>
</template>

<script setup>
import Modal from '../../../components/Modal/Modal.vue';
import Button from '../../../components/Button/Button.vue';
import RenderedHTMLIframe from "./RenderedHTMLIframe.vue";

const props = defineProps({
  html: {
    type: String,
    required: true,
  },
  isOpen: {
    type: Boolean,
    default: false,
  },
});

defineEmits(['close']);
</script>

<style scoped></style>

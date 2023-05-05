<template>
  <Modal
    :is-open="isOpen"
    title="Preview HTML"
    width-size="5xl"
  >
    <p class="text-sm text-gray-500 mt-2">
      Note: this is browser-rendering. For more accurate result, use the PDF
      mode.
    </p>
    <div class="my-2 border-t"></div>
    <RenderedHTMLIframe
      :html="html"
      height="500px"
      width="100%"
    />
    <template #bottom-buttons>
      <Button @click="$emit('close')">Close</Button>
    </template>
  </Modal>
</template>

<script setup>
import Modal from '../../../components/Modal/Modal.vue';
import Button from '../../../components/Button/Button.vue';
import RenderedHTMLIframe from './RenderedHTMLIframe.vue';

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

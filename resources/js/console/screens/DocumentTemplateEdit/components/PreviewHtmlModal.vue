<template>
  <Modal
    :is-open="isOpen"
    title="Preview HTML"
    width-size="5xl"
  >
    <div class="my-2 border-t"></div>
    <div v-html="html" />
    <template #bottom-buttons>
      <Button @click="$emit('close')">Close</Button>
    </template>
  </Modal>
</template>

<script setup>
import Modal from "../../../components/Modal/Modal.vue";
import Button from "../../../components/Button/Button.vue";

defineProps({
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

<style scoped>

</style>

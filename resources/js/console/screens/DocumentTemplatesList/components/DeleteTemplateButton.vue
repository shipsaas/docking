<template>
  <Button type="error">Delete</Button>
</template>

<script setup>
import Button from '../../../components/Button/Button.vue';

defineProps({
  template: {
    type: Object,
    required: true,
  },
});

const emits = defineEmits(['deleted']);
</script>

<style scoped></style>

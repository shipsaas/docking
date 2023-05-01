<template>
  <button
    type="button"
    class="block rounded-md px-3 py-2 text-center text-sm font-semibold text-white shadow-sm focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2"
    :class="typeClass"
    :disabled="disabled"
  >
    <slot />
  </button>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
  type: {
    type: String,
    default: 'neutral',
    validator(value) {
      return ['neutral', 'secondary', 'success', 'error', 'warning'].includes(
        value
      );
    },
  },
  disabled: {
    type: Boolean,
    default: false,
  },
});

const typeClass = computed(() => {
  switch (props.type) {
    case 'neutral':
      return 'bg-indigo-600 hover:bg-indigo-500 focus-visible:outline-indigo-600';

    case 'secondary':
      return 'bg-neutral-600 hover:bg-neutral-500 focus-visible:outline-neutral-600';

    case 'success':
      return 'bg-emerald-600 hover:bg-emerald-500 focus-visible:outline-emerald-600';

    case 'error':
      return 'bg-red-600 hover:bg-red-500 focus-visible:outline-red-600';

    case 'warning':
      return 'bg-yellow-600 hover:bg-yellow-500 focus-visible:outline-yellow-600';

    default:
      return '';
  }
});
</script>

<style scoped></style>

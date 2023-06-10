<template>
  <div>
    <label
      class="block text-sm font-medium leading-6 text-gray-900"
      v-text="label"
    />
    <div class="mt-2">
      <input
        v-if="type !== 'file'"
        v-bind="$attrs"
        :type="type"
        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
        :placeholder="placeholder"
        :value="modelValue"
        @input="onInput"
      />
      <input
        v-else
        v-bind="$attrs"
        :type="type"
        class="px-4 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
        :placeholder="placeholder"
        @change="onInput"
      />
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
  type: {
    type: String,
    default: 'text',
  },
  label: {
    type: String,
    required: true,
  },
  placeholder: {
    type: String,
    default: '',
  },
  modelValue: {
    type: [String, File],
    default: '',
  },
});

const emits = defineEmits(['update:modelValue']);

/**
 * @param {EventTarget & { files: FileList, target: HTMLInputElement }} event
 */
const onInput = (event) => {
  emits(
    'update:modelValue',
    props.type !== 'file' ? event.target.value : event.target.files[0]
  );
};
</script>

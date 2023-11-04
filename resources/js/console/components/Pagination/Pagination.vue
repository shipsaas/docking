<template>
  <nav
    class="-mx-2 sm:-mx-6 lg:-mx-8 gap-2 flex items-center justify-between bg-white py-3"
    aria-label="Pagination"
  >
    <div class="hidden sm:block pl-3">
      <p class="text-sm text-gray-700">
        <span class="font-medium">{{ totalRecords }}</span>
        {{ ' ' }}
        results
      </p>
    </div>
    <div class="flex flex-1 gap-3 justify-between sm:justify-end">
      <button
        title="To first page"
        class="relative inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus-visible:outline-offset-0 disabled:text-gray-400"
        :disabled="current <= 1"
        @click="$emit('goToPage', 1)"
      >
        <ForwardIcon class="w-6 rotate-180" />
      </button>
      <button
        v-if="current - 3 >= 1"
        class="relative inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus-visible:outline-offset-0 disabled:text-gray-400"
        disabled
      >
        ...
      </button>
      <button
        v-if="current - 2 >= 1"
        class="relative inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus-visible:outline-offset-0 disabled:text-gray-400"
        @click="$emit('goToPage', current - 2)"
      >
        {{ current - 2 }}
      </button>
      <button
        v-if="current - 1 >= 1"
        class="relative inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus-visible:outline-offset-0 disabled:text-gray-400"
        @click="$emit('goToPage', current - 1)"
      >
        {{ current - 1 }}
      </button>
      <button
        disabled
        class="relative inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus-visible:outline-offset-0 disabled:text-gray-400"
      >
        {{ current }}
      </button>
      <button
        v-if="current + 1 <= totalPages"
        class="relative inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus-visible:outline-offset-0 disabled:text-gray-400"
        @click="$emit('goToPage', current + 1)"
      >
        {{ current + 1 }}
      </button>
      <button
        v-if="current + 2 <= totalPages"
        class="relative inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus-visible:outline-offset-0 disabled:text-gray-400"
        @click="$emit('goToPage', current + 2)"
      >
        {{ current + 2 }}
      </button>
      <button
        v-if="current + 3 <= totalPages"
        class="relative inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus-visible:outline-offset-0 disabled:text-gray-400"
        disabled
      >
        ...
      </button>
      <button
        title="To last page"
        class="relative inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus-visible:outline-offset-0 disabled:text-gray-400"
        :disabled="current === totalPages"
        @click="$emit('goToPage', totalPages)"
      >
        <ForwardIcon class="w-6" />
      </button>
    </div>
  </nav>
</template>

<script setup>
import { ForwardIcon } from '@heroicons/vue/24/outline';

defineProps({
  totalPages: {
    type: Number,
    required: true,
  },
  totalRecords: {
    type: Number,
    required: true,
  },
  current: {
    type: Number,
    required: true,
  },
});

defineEmits(['goToPage']);
</script>

<template>
  <div>
    <div class="sm:hidden">
      <label
        for="tabs"
        class="sr-only"
      >
        Select a tab
      </label>
      <select
        id="tabs"
        name="tabs"
        class="block w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
        @change="changeTab($event.target.value)"
      >
        <option
          v-for="(tab, index) in tabs"
          :key="tab.key"
          :selected="currentTab === index"
          :value="index"
        >
          {{ tab.label }}
        </option>
      </select>
    </div>
    <div class="hidden sm:block">
      <div class="border-b border-gray-200">
        <nav
          class="-mb-px flex space-x-8"
          aria-label="Tabs"
        >
          <a
            v-for="(tab, index) in tabs"
            :key="tab.key"
            href="javascript:void(0);"
            :class="[
              currentTab === index
                ? 'border-indigo-500 text-indigo-600'
                : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700',
              'group inline-flex items-center border-b-2 py-4 px-1 text-sm font-medium',
            ]"
            :aria-current="currentTab === index ? 'page' : undefined"
            @click="changeTab(index)"
          >
            <component
              :is="tab.icon"
              :class="[
                tab.current
                  ? 'text-indigo-500'
                  : 'text-gray-400 group-hover:text-gray-500',
                '-ml-0.5 mr-2 h-5 w-5',
              ]"
              aria-hidden="true"
            />
            <span>{{ tab.label }}</span>
          </a>
        </nav>
      </div>
    </div>
    <div>
      <slot :name="tabs[currentTab].key" />
    </div>
  </div>
</template>

<script setup>
/**
 * @typedef {Object} MyComponentProps
 * @property {Array<{ key: String, label: String, icon?: Object }>} tabs
 */

import { ref } from 'vue';

defineProps({
  tabs: {
    type: Array,
    required: true,
    validator(value) {
      return (value?.length || 0) > 0;
    },
  },
});

const currentTab = ref(0);

const changeTab = (tabIndex) => (currentTab.value = tabIndex);
</script>

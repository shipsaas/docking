<template>
  <div class="px-2 sm:px-4 lg:px-6">
    <div class="sm:flex sm:items-center">
      <div class="sm:flex-auto">
        <h1
          v-if="title"
          class="text-base font-semibold leading-6 text-gray-900"
          v-text="title"
        />
        <p
          v-if="subTitle"
          class="mt-2 text-sm text-gray-700"
          v-text="subTitle"
        />
      </div>
    </div>
    <div class="mt-8 flow-root">
      <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
        <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
          <table class="min-w-full divide-y divide-gray-300">
            <thead>
              <tr>
                <th
                  v-for="(column, index) in columns"
                  :key="index"
                  scope="col"
                  :class="{
                    'text-left text-sm font-semibold text-gray-900': true,
                    'py-3.5 pl-4 pr-3 sm:pl-3': index === 0,
                    'px-3 py-3.5': index > 0,
                  }"
                >
                  {{ column.label }}
                </th>
              </tr>
            </thead>
            <tbody class="bg-white">
              <tr
                v-for="(record, recordIdx) in records"
                :key="`record-${recordIdx}`"
                :class="recordIdx % 2 === 0 ? undefined : 'bg-gray-50'"
              >
                <td
                  v-for="(column, columnIdx) in columns"
                  :key="`record-column-${recordIdx}-${columnIdx}`"
                  :class="{
                    'whitespace-nowrap text-sm font-medium text-gray-900': true,
                    'py-4 pl-4 pr-3 sm:pl-3': recordIdx === 0,
                    'px-3 py-4': recordIdx > 0,
                  }"
                >
                  {{ record[column.key] || '-' }}
                </td>
              </tr>
              <tr v-if="!records.length">
                <td
                  :colspan="columns.length"
                  class="whitespace-nowrap text-sm text-gray-400 text-center px-3 py-4"
                >
                  No records / out of records
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { defineProps } from 'vue';

defineProps({
  columns: {
    type: Array,
    required: true,
  },
  records: {
    type: Array,
    required: true,
  },
  title: {
    type: String,
    default: '',
  },
  subTitle: {
    type: String,
    default: '',
  },
});
</script>

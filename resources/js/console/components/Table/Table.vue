<template>
  <div class="px-2 sm:px-4 lg:px-6">
    <div class="sm:flex sm:items-center -mx-2 -my-2 sm:-mx-6 lg:-mx-8">
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
      <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
        <slot name="action-buttons" />
      </div>
    </div>
    <div class="mt-8 flow-root">
      <slot name="before-table" />

      <div class="-mx-2 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
        <div class="inline-block min-w-full py-2 align-middle">
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
                    [column.headerClass]: !!column.headerClass,
                  }"
                >
                  {{ column.label }}
                </th>
                <th
                  v-if="slots['record-actions']"
                  scope="col"
                  class="relative py-3.5 pl-3 pr-4 sm:pr-0"
                >
                  <span class="sr-only">Edit</span>
                </th>
              </tr>
            </thead>
            <tbody class="bg-white">
              <tr
                v-for="(record, recordIdx) in records"
                :key="`record-${recordIdx}`"
                :class="[recordIdx % 2 === 0 ? undefined : 'bg-gray-50']"
              >
                <td
                  v-for="(column, columnIdx) in columns"
                  :key="`record-column-${recordIdx}-${columnIdx}`"
                  :class="{
                    'whitespace-nowrap text-sm font-medium text-gray-900': true,
                    'py-4 pl-4 pr-3 sm:pl-3': recordIdx === 0,
                    'px-3 py-4': recordIdx > 0,
                    [column.contentClass]: !!column.contentClass,
                  }"
                >
                  <component
                    v-if="column.transformType === 'component'"
                    :is="transformColumnValue(column, record)"
                  />
                  <span
                    v-else
                    v-text="transformColumnValue(column, record)"
                  />
                </td>
                <td
                  v-if="slots['record-actions']"
                  class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-0"
                >
                  <slot
                    name="record-actions"
                    :record="record"
                  />
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
import { useSlots } from 'vue';

const slots = useSlots();

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

/**
 * @param {{transform?: Function, label: String, key: String}} column
 * @param {{[key]: any}} record
 * @returns {unknown}
 */
const transformColumnValue = (column, record) => {
  if (!record[column.key]) {
    return '-';
  }

  const value = record[column.key];

  if (typeof column.transform === 'function') {
    return column.transform(value, record);
  }

  return value;
};
</script>

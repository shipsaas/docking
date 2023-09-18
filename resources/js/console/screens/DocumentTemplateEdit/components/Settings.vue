<template>
  <form>
    <div class="space-y-12 py-2">
      <div class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
        <div class="col-span-full">
          <label class="block text-sm font-medium leading-6 text-gray-900">
            PDF Rendering Driver (Default: Gotenberg)
          </label>
          <div class="mt-2">
            <Dropdown
              :label="selectedDriver"
              :items="availableDrivers"
              class="min-w-[18rem]"
              @selected="(value) => $emit('update', 'driver', value)"
            />
          </div>
        </div>
        <div
          v-if="parsedMetadata?.driver === 'gotenberg'"
          class="col-span-full"
        >
          <label class="block text-sm font-medium leading-6 text-gray-900">
            Gotenberg Engine (Default: Chromium)
          </label>
          <div class="mt-2">
            <Dropdown
              :label="selectedEngine"
              :items="gotenbergEngines"
              class="min-w-[18rem]"
              @selected="(value) => $emit('update', 'engine', value)"
            />
          </div>
        </div>
        <div class="col-span-full">
          <label class="block text-sm font-medium leading-6 text-gray-900">
            Templating Driver (Default: Blade)
          </label>
          <div class="mt-2">
            <Dropdown
              :label="selectedTemplating"
              :items="templatingDrivers"
              class="min-w-[18rem]"
              @selected="(value) => $emit('update', 'templating', value)"
            />
          </div>
        </div>
      </div>
    </div>
  </form>
</template>

<script setup>
import Dropdown from '../../../components/Dropdown/Dropdown.vue';
import { computed } from 'vue';
import { validateJson } from '../DocumentTemplateEdit.methods';

const props = defineProps({
  template: {
    type: Object,
    required: true,
  },
});

const emit = defineEmits(['update']);

const availableDrivers = [
  { key: 'gotenberg', label: 'Gotenberg' },
  { key: 'wkhtmltopdf', label: 'WkHtmlToPDF' },
  { key: 'mpdf', label: 'mPDF' },
];

const gotenbergEngines = [
  { key: 'chromium', label: 'Chromium' },
  { key: 'libreoffice', label: 'LibreOffice' },
];

const templatingDrivers = [
  { key: 'blade', label: 'Blade Templating' },
  { key: 'markdown', label: 'Markdown' },
];

const parsedMetadata = computed(
  () => validateJson(props.template.metadata) || {}
);

const selectedDriver = computed(() => {
  if (!parsedMetadata.value.driver) {
    return 'Select Driver (Default: Gotenberg)';
  }

  const driver = availableDrivers.find(
    (driver) => driver.key === parsedMetadata.value['driver']
  );

  return driver.label;
});

const selectedEngine = computed(() => {
  if (!parsedMetadata.value.engine) {
    return 'Select Engine (Default: Chromium)';
  }

  const engine = gotenbergEngines.find(
    (driver) => driver.key === parsedMetadata.value['engine']
  );

  return engine.label;
});

const selectedTemplating = computed(() => {
  if (!parsedMetadata.value.templating) {
    return 'Select Templating (Default: Blade)';
  }

  const templating = templatingDrivers.find(
    (driver) => driver.key === parsedMetadata.value['templating']
  );

  return templating.label;
});
</script>

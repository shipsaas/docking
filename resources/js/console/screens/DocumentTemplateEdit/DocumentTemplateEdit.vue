<template>
  <Card v-if="isLoading"> Loading template... </Card>
  <Card v-else-if="!isLoading && !template">
    <div class="flex flex-col">
      <span>Template is not exists</span>
      <div class="mt-2">
        <Button @click="$router.replace({ name: 'document-templates-list' })">
          Go back to Document Templates List
        </Button>
      </div>
    </div>
  </Card>
  <Card v-else>
    <div class="sm:flex sm:items-center">
      <div class="sm:flex-auto">
        <h1 class="text-base font-semibold leading-6 text-gray-900">
          Edit Document Template: {{ template.title }}
        </h1>
        <p class="mt-2 text-sm text-gray-700">
          Configure the Document Template
        </p>
      </div>
      <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
        <div class="flex gap-1">
          <component :is="Dropdown" />
          <Button @click="saveTemplate">Save</Button>
          <Button
            @click="$router.go(-1)"
            type="secondary"
            >Cancel</Button
          >
        </div>
      </div>
    </div>
    <Tabs
      class="mt-4"
      :tabs="tabs"
    >
      <template #info>
        <div class="py-2 flex flex-col gap-2">
          <Input
            label="Unique Key"
            :model-value="template.key"
            readonly
          />
          <Input
            v-model="template.category"
            label="Category"
          />
          <Input
            v-model="template.title"
            label="Title"
          />
        </div>
      </template>
      <template #template>
        <div class="flex gap-2 lg:flex-row sm:flex-col md:flex-col">
          <div class="py-2 flex-1">
            <label
              class="block text-sm font-medium leading-6 text-gray-900 mb-1"
              v-text="'Template HTML'"
            />
            <codemirror
              v-model="template.template"
              :style="{ height: '400px' }"
              :autofocus="true"
              :indent-with-tab="true"
              :tab-size="4"
              :extensions="htmlExtensions"
            />
            <p class="text-sm text-gray-500 mt-2">
              Learn more rendering syntax:
              <a
                href="https://laravel.com/docs/10.x/blade#blade-directives"
                target="_blank"
                class="text-indigo-600 font-medium"
                rel="nofollow"
              >
                Laravel Blade
              </a>
              or this
              <a
                href="https://gist.github.com/CiprianSpiridon/f4d7fe0d8a51f0714b62"
                target="_blank"
                class="text-indigo-600 font-medium"
                rel="nofollow"
              >
                Blade Cheatsheet
              </a>
              <br />
              <strong>Save</strong> before Preview.
            </p>
          </div>
          <div class="py-2 flex-1">
            <label
              class="block text-sm font-medium leading-6 text-gray-900 mb-1"
              v-text="'Default Variables (JSON)'"
            />
            <codemirror
              v-model="template.default_variables"
              :style="{ height: '400px' }"
              :autofocus="true"
              :indent-with-tab="true"
              :tab-size="4"
              :extensions="jsonExtensions"
            />
          </div>
        </div>
      </template>
      <template #metadata>
        <div class="py-2">
          <label
            class="block text-sm font-medium leading-6 text-gray-900 mb-1"
            v-text="'Metadata (JSON)'"
          />
          <codemirror
            v-model="template.metadata"
            :style="{ height: '400px' }"
            :autofocus="true"
            :indent-with-tab="true"
            :tab-size="4"
            :extensions="jsonExtensions"
          />
          <p class="text-sm text-gray-500 mt-2">
            Learn more about metadata variables:
            <a
              href="https://docking.shipsaas.tech/getting-started/document-template"
              target="_blank"
              class="text-indigo-600 font-medium"
            >
              Metadata
            </a>
          </p>
        </div>
      </template>
    </Tabs>
  </Card>

  <PreviewHtmlModal
    :is-open="!!renderedHtml"
    :html="renderedHtml"
    @close="renderedHtml = ''"
  />
</template>

<script setup>
import Card from '../../components/Card/Card.vue';
import { onMounted, ref } from 'vue';
import { useLoading } from '../../composable/useLoading';
import { documentTemplateRepository } from '../../repositories/documentTemplate.repository';
import Button from '../../components/Button/Button.vue';
import Tabs from '../../components/Tabs/Tabs.vue';
import Input from '../../components/Input/Input.vue';
import { Codemirror } from 'vue-codemirror';
import { html } from '@codemirror/lang-html';
import { json } from '@codemirror/lang-json';
import { toJsonString, validateJson } from './DocumentTemplateEdit.methods';
import { usePreviewTemplate } from './composable/usePreviewTemplate.js';
import PreviewHtmlModal from './components/PreviewHtmlModal.vue';
import { notify } from '@kyvg/vue3-notification';

const props = defineProps({
  uuid: {
    type: String,
    required: true,
  },
});

const { isLoading, startLoading, stopLoading } = useLoading();

const template = ref({
  uuid: null,
  key: '',
  category: '',
  title: '',
  template: '',
  default_variables: {},
  metadata: {},
});

const { Dropdown, renderedHtml } = usePreviewTemplate(template);

const tabs = ref([
  {
    key: 'info',
    label: 'Info',
  },
  {
    key: 'template',
    label: 'Template & Default Variables',
  },
  {
    key: 'metadata',
    label: 'Metadata',
  },
]);

const htmlExtensions = [html()];
const jsonExtensions = [json()];

const loadRecord = async () => {
  startLoading();

  const data = await documentTemplateRepository.show(props.uuid);

  stopLoading();

  if (!data) {
    return;
  }

  template.value = {
    ...data.data,
    default_variables: toJsonString(data.data.default_variables),
    metadata: toJsonString(data.data.metadata),
  };
};

const saveTemplate = async () => {
  const defaultVariables = validateJson(template.value.default_variables);
  if (defaultVariables === false) {
    notify({
      type: 'error',
      title: 'Validation Error',
      text: 'Default Variable must be a valid JSON. Put "{}" for empty JSON',
    });

    return;
  }

  const metadata = validateJson(template.value.metadata);
  if (metadata === false) {
    notify({
      type: 'error',
      title: 'Validation Error',
      text: 'Metadata must be a valid JSON. Put "{}" for empty JSON',
    });

    return;
  }

  const result = await documentTemplateRepository.update(template.value.uuid, {
    ...template.value,
    default_variables: defaultVariables,
    metadata,
  });

  if (!result) {
    return;
  }

  notify({
    type: 'success',
    title: 'Action OK',
    text: 'Document Template has been saved. You can now preview the template.',
  });
};

onMounted(() => loadRecord());
</script>

<style scoped></style>

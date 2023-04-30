<template>
  <Card v-if="isLoading">
    Loading template...
  </Card>
  <Card v-else-if="!isLoading && !template">
    <div class="flex flex-col">
      <span>Template is not exists</span>
      <div class="mt-2">
        <Button @click="$router.replace({ name: 'document-templates-list' })">Go back to Document Templates List</Button>
      </div>
    </div>
  </Card>
  <Card v-else>
    <div class="sm:flex sm:items-center">
      <div class="sm:flex-auto">
        <h1
          class="text-base font-semibold leading-6 text-gray-900"
        >
          Edit Document Template: {{ template.title }}
        </h1>
        <p
          class="mt-2 text-sm text-gray-700"
        >
          Configure the Document Template
        </p>
      </div>
    </div>
  </Card>
</template>

<script setup>
import Card from '../../components/Card/Card.vue';
import {onMounted, ref} from 'vue';
import {useLoading} from "../../composable/useLoading";
import {documentTemplateRepository} from "../../repositories/documentTemplate.repository";
import Button from "../../components/Button/Button.vue";

const props = defineProps({
  uuid: {
    type: String,
    required: true,
  },
});

const { isLoading, startLoading, stopLoading } = useLoading();
const template = ref(null);

const loadRecord = async () => {
  startLoading();

  const data = await documentTemplateRepository.show(props.uuid);

  stopLoading();

  if (!data) {
    return;
  }

  template.value = { ...data.data };
};

onMounted(() => loadRecord());
</script>

<style scoped></style>

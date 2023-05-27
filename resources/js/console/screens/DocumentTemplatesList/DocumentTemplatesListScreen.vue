<template>
  <Card>
    <Table
      title="Document Templates"
      sub-title="Manage all document templates across your product."
      :columns="columns"
      :records="records"
    >
      <template #action-buttons>
        <CreateNewTemplate />
      </template>
      <template #record-actions="{ record }">
        <span class="isolate inline-flex rounded-md shadow-sm gap-1">
          <Button
            @click="
              $router.push({
                name: 'document-template-edit',
                params: { uuid: record.uuid },
              })
            "
          >
            Edit
          </Button>
          <DuplicateTemplateButton
            :template="record"
            @duplicated="onTemplateDuplicated"
          />
          <DeleteTemplateButton
            :template="record"
            @deleted="onTemplateDeleted"
          />
        </span>
      </template>
    </Table>
  </Card>
</template>

<script setup>
import Table from '../../components/Table/Table.vue';
import Card from '../../components/Card/Card.vue';
import { ref } from 'vue';
import { documentTemplateRepository } from '../../repositories/documentTemplate.repository';
import Button from '../../components/Button/Button.vue';
import CreateNewTemplate from './components/CreateNewTemplate.vue';
import DeleteTemplateButton from './components/DeleteTemplateButton.vue';
import DuplicateTemplateButton from './components/DuplicateTemplateButton.vue';
import { useRouter } from 'vue-router';

const router = useRouter();

const columns = [
  {
    key: 'uuid',
    label: 'ID',
  },
  {
    key: 'key',
    label: 'Key',
  },
  {
    key: 'category',
    label: 'Category',
  },
  {
    key: 'title',
    label: 'Title',
  },
  {
    key: 'updated_at',
    label: 'Last Updated At',
    transform(value) {
      return new Date(value).toLocaleString();
    },
  },
];

const records = ref([]);
const page = ref(1);

const loadRecords = async () => {
  const documentTemplates = await documentTemplateRepository.index({
    limit: 20,
    page: page.value,
  });

  records.value = [...documentTemplates.data];
};

const onTemplateDeleted = () => loadRecords();
const onTemplateDuplicated = (result) =>
  router.push({
    name: 'document-template-edit',
    params: { uuid: result.template.uuid },
  });

// inits
loadRecords();
</script>

<style scoped></style>

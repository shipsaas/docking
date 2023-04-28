<template>
  <Card>
    <Table
      title="Document Templates"
      sub-title="Manage all document templates across your product."
      :columns="columns"
      :records="records"
    >
      <template #action-buttons>
        <Button>Create New Template</Button>
      </template>
      <template #record-actions="{ record }">
        <span class="isolate inline-flex rounded-md shadow-sm gap-1">
          <Button>Edit</Button>
          <Button type="error">Delete</Button>
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

loadRecords();
</script>

<style scoped></style>

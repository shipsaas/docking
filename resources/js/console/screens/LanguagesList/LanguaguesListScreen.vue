<template>
  <Card>
    <Table
      title="Languages"
      sub-title="The available languages for your documents"
      :columns="columns"
      :records="records"
    >
      <template #action-buttons>
        <CreateNewLanguage @created="loadRecords" />
      </template>
      <template #record-actions="{ record }">
        <div class="flex gap-x-2">
          <UpdateLanguageButton
            :language="record"
            @updated="loadRecords"
          />
          <DeleteLanguageButton
            :language="record"
            @deleted="loadRecords"
          />
        </div>
      </template>
    </Table>
  </Card>
</template>

<script setup>
import Card from '../../components/Card/Card.vue';
import Table from '../../components/Table/Table.vue';
import { ref } from 'vue';
import CreateNewLanguage from './components/CreateNewLanguage.vue';
import DeleteLanguageButton from './components/DeleteLanguageButton.vue';
import { languageRepository } from '../../repositories/language.repository';
import UpdateLanguageButton from './components/UpdateLanguageButton.vue';

const columns = [
  {
    key: 'code',
    label: 'Code',
    headerClass: 'w-60',
  },
  {
    key: 'name',
    label: 'Name',
    headerClass: 'w-60',
  },
  {
    key: 'created_at',
    label: 'Created At',
    transform(value) {
      return new Date(value).toLocaleString();
    },
  },
];

const records = ref([]);
const loadRecords = async () => {
  const data = await languageRepository.index({
    sortBy: 'name',
  });

  if (!data) {
    return;
  }

  records.value = [...data.data];
};

loadRecords();
</script>

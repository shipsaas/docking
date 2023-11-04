<template>
  <Card>
    <Table
      title="Translation Groups"
      sub-title="The group of translations"
      :columns="columns"
      :records="records"
    >
      <template #action-buttons>
        <CreateNewTranslationGroup @created="loadRecords" />
      </template>
      <template #record-actions="{ record }">
        <div class="flex gap-x-2">
          <UpdateTranslationGroupButton
            :translation-group="record"
            @updated="loadRecords"
          />
          <DeleteTranslationGroupButton
            :translation-group="record"
            @deleted="loadRecords"
          />
        </div>
      </template>
      <template #after-table>
        <Pagination
          v-if="paginationMeta"
          :total-records="paginationMeta.total"
          :total-pages="paginationMeta.last_page"
          :current="page"
          @go-to-page="loadRecords"
        />
      </template>
    </Table>
  </Card>
</template>

<script setup>
import Card from '../../components/Card/Card.vue';
import Table from '../../components/Table/Table.vue';
import { ref } from 'vue';
import DeleteTranslationGroupButton from './components/DeleteTranslationGroupButton.vue';
import UpdateTranslationGroupButton from './components/UpdateTranslationGroupButton.vue';
import { translationGroupRepository } from '../../repositories/translationGroup.repository';
import Pagination from '../../components/Pagination/Pagination.vue';
import CreateNewTranslationGroup from './components/CreateNewTranslationGroup.vue';

const columns = [
  {
    key: 'key',
    label: 'Key',
    headerClass: 'w-60',
  },
  {
    key: 'name',
    label: 'Name',
    headerClass: 'w-60',
  },
  {
    key: 'description',
    label: 'Description',
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
const page = ref(1);
const paginationMeta = ref(null);

const loadRecords = async () => {
  const data = await translationGroupRepository.index({
    sortBy: 'name',
  });

  if (!data) {
    return;
  }

  if (!data) {
    return;
  }

  records.value = [...data.data];
  paginationMeta.value = { ...data.meta };
};

loadRecords();
</script>

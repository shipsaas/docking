<template>
  <Card>
    <Table
      title="Fonts"
      sub-title="Custom fonts for your PDF Template"
      :columns="columns"
      :records="records"
    >
      <template #action-buttons>
        <CreateNewFont @created="loadRecords(1)" />
      </template>
      <template #record-actions="{ record }">
        <DeleteFontButton
          :font="record"
          @deleted="loadRecords(1)"
        />
      </template>
      <template #after-table>
        <Pagination
          v-if="paginationMeta"
          :from="paginationMeta.from || 0"
          :to="paginationMeta.to || 0"
          :total="paginationMeta.total"
          @next="loadRecords(page + 1)"
          @prev="loadRecords(page - 1)"
        />
      </template>
    </Table>
  </Card>
</template>

<script setup>
import Card from '../../components/Card/Card.vue';
import Table from '../../components/Table/Table.vue';
import { ref } from 'vue';
import { fontRepository } from '../../repositories/font.repository';
import CreateNewFont from './components/CreateNewFont.vue';
import DeleteFontButton from './components/DeleteFontButton.vue';
import Pagination from '../../components/Pagination/Pagination.vue';

const columns = [
  {
    key: 'uuid',
    label: 'ID',
    headerClass: 'w-20',
  },
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

const loadRecords = async (wantedPage) => {
  page.value = wantedPage || page.value;

  const data = await fontRepository.index({
    limit: 20,
    page: page.value,
    sortBy: 'name',
  });

  if (!data) {
    return;
  }

  records.value = [...data.data];
  paginationMeta.value = { ...data.meta };
};

loadRecords();
</script>

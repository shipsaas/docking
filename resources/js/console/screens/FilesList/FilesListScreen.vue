<template>
  <Card>
    <Table
      title="Files"
      sub-title="Manage all rendered PDF files"
      :columns="columns"
      :records="records"
    >
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
import { ref, h } from 'vue';
import { documentFileRepository } from '../../repositories/documentFile.repository';
import Pagination from '../../components/Pagination/Pagination.vue';

const columns = [
  {
    key: 'uuid',
    label: 'ID',
  },
  {
    key: 'template_name',
    label: 'From Template',
  },
  {
    key: 'path',
    label: 'Path',
  },
  {
    key: 'url',
    label: 'URL',
    transformType: 'component',
    transform(value) {
      return h('a', {
        href: value,
        target: '_blank',
        text: 'Link',
        className: 'text-indigo-600 font-medium',
      });
    },
  },
  {
    key: 'created_at',
    label: 'Rendered At',
    transform(value) {
      return new Date(value).toLocaleString();
    },
  },
];

const records = ref([]);
const page = ref(1);
const paginationMeta = ref(null);

const loadRecords = async (forcePage) => {
  page.value = forcePage || page.value;

  const data = await documentFileRepository.index({
    limit: 20,
    page: page.value,
  });

  if (!data) {
    return;
  }

  records.value = [...data.data];
  paginationMeta.value = { ...data.meta };

  window.scrollTo({ top: 0, behavior: 'smooth' });
};

loadRecords();
</script>

<style scoped></style>

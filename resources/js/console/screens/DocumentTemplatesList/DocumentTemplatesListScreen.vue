<template>
  <Card>
    <Table
      title="Document Templates"
      sub-title="Manage all document templates across your product."
      :columns="columns"
      :records="records"
    />
  </Card>
</template>

<script setup>
import Table from '../../components/Table/Table.vue';
import Card from '../../components/Card/Card.vue';

const columns = [
  {
    key: 'id',
    label: 'ID',
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

const records = [
  {
    id: '123456',
    category: 'Invoices',
    title: 'Invoice Template',
    updated_at: '16:02:11 Feb 28th',
  },
];
</script>

<style scoped></style>

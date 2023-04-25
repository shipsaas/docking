<template>
  <Card>
    <Table
      title="Files"
      sub-title="Manage all rendered PDF files"
      :columns="columns"
      :records="records"
    />
  </Card>
</template>

<script setup>
import Card from "../../components/Card/Card.vue";
import Table from "../../components/Table/Table.vue";


const columns = [
  {
    key: 'id',
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
  },
  {
    key: 'created_at',
    label: 'Rendered At',
  },
];

const records = [];
</script>

<style scoped></style>

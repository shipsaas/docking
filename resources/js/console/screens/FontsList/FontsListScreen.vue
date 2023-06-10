<template>
  <Card>
    <Table
      title="Fonts"
      sub-title="Custom fonts for your PDF Template"
      :columns="columns"
      :records="records"
    >
      <template #record-actions="{ record }">
        <Button
          type="error"
          @click="onClickRemove(record)"
        >
          Remove
        </Button>
      </template>
    </Table>
  </Card>
</template>

<script setup>
import Card from '../../components/Card/Card.vue';
import Table from '../../components/Table/Table.vue';
import { ref } from 'vue';
import { fontRepository } from '../../repositories/font.repository';
import Button from '../../components/Button/Button.vue';

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

const loadRecords = async () => {
  const data = await fontRepository.index({
    limit: 20,
    page: page.value,
    sortBy: 'name',
  });

  if (!data) {
    return;
  }

  records.value = [...data.data];
};

loadRecords();

const onClickRemove = (record) => {
  console.log(record);
};
</script>

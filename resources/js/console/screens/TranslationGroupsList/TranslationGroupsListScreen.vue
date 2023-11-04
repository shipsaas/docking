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
      <template #before-table>
        <div class="py-4 flex -mx-2 -my-2 sm:-mx-6 lg:-mx-8 gap-2">
          <div class="flex-1">
            <Input
              v-model="keyword"
              label=""
              placeholder="Search by keyword"
              @keyup.enter="search"
            />
          </div>
          <div class="flex-1 mt-2">
            <Button @click="search">Search</Button>
          </div>
        </div>
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
import Input from '../../components/Input/Input.vue';
import Button from '../../components/Button/Button.vue';
import { notify } from '@kyvg/vue3-notification';

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
const keyword = ref(null);
const isSearching = ref(false);
const isLoading = ref(false);

const loadRecords = async () => {
  isLoading.value = true;

  const data = await translationGroupRepository.index({
    sortBy: 'name',
    search: keyword.value || '',
  });

  isLoading.value = false;

  if (!data) {
    return;
  }

  records.value = [...data.data];
  paginationMeta.value = { ...data.meta };
};

const search = () => {
  if (isLoading.value) {
    return;
  }

  if (!keyword.value) {
    // clear search state
    if (isSearching.value) {
      isSearching.value = false;
      loadRecords(1);

      return;
    }

    notify({
      type: 'error',
      title: 'Missing Info',
      text: 'Please enter some keywords in order to search.',
    });

    return;
  }

  isSearching.value = true;

  // refetch
  loadRecords(1);
};

loadRecords();
</script>

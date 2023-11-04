<template>
  <Card>
    <Table
      title="Translations"
      sub-title="The available translations for your document templates"
      :columns="columns"
      :records="records"
    >
      <template #action-buttons>
        <CreateNewTranslation
          :languages="languages"
          :translation-groups="translationGroups"
          @created="loadRecords(1)"
        />
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
        <div class="flex gap-2">
          <UpdateTranslationButton
            :languages="languages"
            :translation-groups="translationGroups"
            :translation="record"
            @updated="loadRecords(page)"
          />
          <DeleteTranslationButton
            :font="record"
            @deleted="loadRecords(page)"
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
import { onMounted, ref } from 'vue';
import { fontRepository } from '../../repositories/font.repository';
import Pagination from '../../components/Pagination/Pagination.vue';
import CreateNewTranslation from './components/CreateNewTranslation.vue';
import DeleteTranslationButton from './components/DeleteTranslationButton.vue';
import { languageRepository } from '../../repositories/language.repository';
import { translationGroupRepository } from '../../repositories/translationGroup.repository';
import { translationRepository } from '../../repositories/translation.repository';
import UpdateTranslationButton from './components/UpdateTranslationButton.vue';
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

const languages = ref([]);
const translationGroups = ref([]);

const loadRecords = async (wantedPage) => {
  isLoading.value = true;

  page.value = wantedPage || page.value;

  const data = await translationRepository.index({
    limit: 20,
    page: page.value,
    sortBy: 'created_at',
    sortDirection: 'asc',
    search: keyword.value || '',
  });

  isLoading.value = false;

  if (!data) {
    return;
  }

  records.value = [...data.data];
  paginationMeta.value = { ...data.meta };
};

const loadLanguages = async () => {
  const data = await languageRepository.index({
    sortBy: 'name',
    sortDirection: 'asc',
  });

  if (!data) {
    return;
  }

  languages.value = [...data.data];
};

const loadTranslationGroups = async () => {
  const data = await translationGroupRepository.index({
    sortBy: 'name',
    sortDirection: 'asc',
    limit: 100, // unlikely people would create more than 100 groups lol
  });

  if (!data) {
    return;
  }

  translationGroups.value = [...data.data];
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

onMounted(() => {
  loadRecords();
  loadLanguages();
  loadTranslationGroups();
});
</script>

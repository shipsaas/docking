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
            @updated="loadRecords(page)"
          />
        </div>
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
import { onMounted, ref } from 'vue';
import { fontRepository } from '../../repositories/font.repository';
import Pagination from '../../components/Pagination/Pagination.vue';
import CreateNewTranslation from './components/CreateNewTranslation.vue';
import DeleteTranslationButton from './components/DeleteTranslationButton.vue';
import { languageRepository } from '../../repositories/language.repository';
import { translationGroupRepository } from '../../repositories/translationGroup.repository';
import { translationRepository } from '../../repositories/translation.repository';
import UpdateTranslationButton from './components/UpdateTranslationButton.vue';

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

const languages = ref([]);
const translationGroups = ref([]);

const loadRecords = async (wantedPage) => {
  page.value = wantedPage || page.value;

  const data = await translationRepository.index({
    limit: 20,
    page: page.value,
    sortBy: 'created_at',
    sortDirection: 'asc',
  });

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

onMounted(() => {
  loadRecords();
  loadLanguages();
  loadTranslationGroups();
});
</script>

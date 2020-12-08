<template>
  <v-app>
    <v-overlay :value="loader.loading" :light="true" color="#fff">
      <v-progress-circular
        indeterminate
        color="primary"
        size="100"
      ></v-progress-circular>
    </v-overlay>
    <div class="pa-10">
      <CsvImportComponent v-on:close="handleImport($event)"/>
      <v-divider class="my-10"></v-divider>
      <h1 class="pb-5">Contacts</h1>
      <ContactsTableComponent :contacts="contacts"/>
    </div>
  </v-app>
</template>
<script>

import CsvImportComponent from './modules/contact/components/csv-import.component.vue';
import ContactsTableComponent from './modules/contact/components/contacts-table.component.vue';
import { ContactApiService } from './modules/common/services/contact-api.service';
import loaderState from './modules/common/state/loader.state';

const service = new ContactApiService();

export default {
  components: { CsvImportComponent, ContactsTableComponent },
  data: () => ({
    contacts: [],
    loader: loaderState.state
  }),
  methods: {
    async handleImport(file) {
      if (!file) {
        return;
      }
      try {
        const res = await service.import(file);
        this.loadData();
      } catch (e) {
        console.log(e);
      }
    },
    async loadData(){
      try {
        const { data } = await service.list();
        this.contacts = data;
      } catch (e) {
        console.log(e);
      }
    }
  },
  mounted() {
    this.loadData();
  }
};
</script>
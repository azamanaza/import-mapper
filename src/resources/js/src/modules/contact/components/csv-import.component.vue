<template>
<div class="d-inline-block">
  <v-dialog
    v-model="dialog"
    max-width="290"
  >
    <template v-slot:activator="{ on, attrs }">
      <v-btn
        color="primary"
        dark
        v-bind="attrs"
        v-on="on"
      >
        Import Contacts
      </v-btn>
    </template>
    <v-card>
      <v-card-title class="headline">
        Import Contacts
      </v-card-title>
      <v-card-text>
        <div> Select the csv file to import. </div>
        <v-file-input
          truncate-length="15"
          :value="file"
          @change="handleChange($event)"
        ></v-file-input>
      </v-card-text>
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn
          color="green darken-1"
          text
          @click="cancelImport()"
        >
          Cancel
        </v-btn>
        <v-btn
          color="green darken-1"
          text
          @click="upload()"
        >
          Import
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</div>
</template>
<script>
export default {
  name: 'contact-csv-import',
  data() {
    return {
      file: undefined,
      dialog: false,
    }
  },
  methods: {
    handleChange(file) {
      try {
        this.file = file;
      } catch (e) {
        console.log(e);
      }
    },
    cancelImport() {
      console.log(this);
      this.file = undefined;
      this.dialog = false;
      this.$emit('close', false);
    },
    upload() {
      try {
        this.dialog = false;
        console.log('emit close:: ', this.file);
        this.$emit('close', this.file);
      } catch (e) {

      }
    }
  }
}
</script>
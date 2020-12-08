<template>
  <v-simple-table>
    <template v-slot:default>
      <thead>
        <tr>
          <th v-for="header in headers"
            :key="header.name"
            class="text-left"
          >
            {{ header.text }}
          </th>
        </tr>
      </thead>
      <tbody>
        <tr
          v-for="contact in contacts"
          :key="contact.id"
        >
          <td>{{ contact.id }}</td>
          <td>{{ contact.team_id }}</td>
          <td>{{ contact.name }}</td>
          <td>{{ contact.phone }}</td>
          <td>{{ contact.email }}</td>
          <td>{{ contact.sticky_phone_number_id }}</td>
          <td>
            <div v-for="attr in contact.custom_attrs" :key="attr.id" ><strong>{{ attr.key }}</strong>: {{ attr.value }}</div>
          </td>
        </tr>
        <tr v-if="!contacts || !contacts.length">
          <td v-bind:colspan="headers.length" class="text-center">No contacts yet.</td>
        </tr>
      </tbody>
    </template>
  </v-simple-table>
</template>
<script>
const headers = [
  {
    text: 'ID',
    value: 'id',
  },
  {
    text: 'Team ID',
    value: 'team_id',
  },
  { text: 'Name', value: 'name' },
  { text: 'Phone', value: 'phone' },
  { text: 'Email', value: 'email' },
  { text: 'Sticky Phone ID', value: 'sticky_phone_number_id' },
  { text: 'Other Attributes', value: 'customAttrs' }
];

export default {
  props: {
    contacts: {
      type: Array,
      default: []
    }
  },
  data () {
    return {
      headers
    }
  },
}
</script>
<!-- @format -->

<template>
  <div class="user-detail">
    <vs-input class="mb-2 w-1/5" label="ID" placeholder="id" v-if="user.id" :value="user.id" disabled />
    <vs-input class="mb-2 w-full" label="Name" placeholder="Name" v-model="user.name" />
    <vs-input class="mb-4 w-full" label="Email" placeholder="Email" v-model="user.email" />
    <vs-row>
      <label>Role:</label>
      <vs-radio class="mx-2" v-for="(name, index) of userType" :key="index" v-model="user.type" :vs-name="index" :vs-value="index">{{ name }}</vs-radio>
    </vs-row>
    <div class="mt-4 flex justify-end">
      <vs-button color="success" v-if="user.id">Save</vs-button>
      <vs-button color="danger" v-if="user.id" class="mx-4">Delete</vs-button>
      <vs-button color="primary" class="mr-2" v-else @click="$emit('actionCreate')">Create</vs-button>
      <vs-button color="lightgray" @click="onBack">Cancel</vs-button>
    </div>
  </div>
</template>

<script>
export default {
  name: 'user-detail',
  props: {
    user: Object,
    userType: Object
  },
  methods: {
    onBack() {
      this.$emit('clearEvent')
    }
  }
}
</script>

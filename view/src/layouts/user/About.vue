<template>
  <div class="layout-about flex justify-between py-1 px-4 text-sm text-gray-300 bg-gray-800 md:px-28">
    <div class="flex justify-center items-center">
      <div class="contact flex justify-center mx-2" v-for="(contact, index) in contacts" :key="index">
        <span class="material-icons-outlined text-lg mx-1">{{ contact.icon }}</span>
        <span>{{ contact.label }}</span>
      </div>
    </div>
    <div class="social flex justify-center items-center">
      <div class="flex mx-2" v-for="(connection, index) in connections" :key="index">
        <img :src="require(`@/assets/img/icon/${connection.icon}.svg`)" alt="" class="w-5 mx-2" />
        <!-- <a class="hover:text-red-600" :href="connection.link">{{ connection.label }}</a> -->
      </div>
    </div>
  </div>
</template>

<script>
import abouts from './about-connection'
export default {
  name: 'about-main',
  data() {
    return {
      contacts: abouts.slice(0, 2),
      connections: abouts.slice(2, abouts.length)
    }
  }
}
</script>
<style lang="scss" scoped>
.layout-about {
  background-color: #16182999;
  box-shadow: 0px 2px 8px 0px #000610e6;
}
</style>

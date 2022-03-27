<!-- @format -->

<template>
  <div class="title flex items-center border-b-2 bg-gray-200 p-2">
    <span class="material-icons mx-2"> {{ icon }} </span>
    <span class="font-bold text-sm w-full">{{ title }}</span>
  </div>
</template>

<script>
export default {
  name: 'TitlePage',
  props: {
    title: String,
    icon: {
      type: String,
      default: 'home'
    }
  }
}
</script>

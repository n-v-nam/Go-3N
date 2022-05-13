<template>
  <div class="fixed bottom-5 z-10 right-24 flex items-center gap-2">
    <div
      class="cursor-pointer hover:opacity-70 flex items-center justify-center w-10 h-10 bg-red-600 rounded-full drop-shadow-container"
    >
      <span class="material-icons text-red-60 text-white" @click="$router.push('/messenger')">contact_support</span>
    </div>
  </div>
</template>

<script>
export default {
  name: 'chat-component',
  data() {
    return {}
  }
}
</script>

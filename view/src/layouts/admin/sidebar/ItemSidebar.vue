<!-- @format -->

<template>
  <div
    @click="selectItemSidebar"
    class="sidebar-item rounded flex items-center p-2 mb-2 hover:bg-gray-200 cursor-pointer"
    :class="{ 'bg-gray-200': itemSelected.slug === item.slug }"
  >
    <span class="material-icons mr-4">{{ item.icon }}</span>
    <span class="label font-bold">{{ item.label }}</span>
  </div>
</template>

<script>
export default {
  name: 'SidebarItem',
  props: {
    item: Object,
    itemSelected: Object
  },
  methods: {
    selectItemSidebar() {
      if (this.itemSelected !== this.item) this.$emit('selectItemSidebar', this.item)
    }
  }
}
</script>

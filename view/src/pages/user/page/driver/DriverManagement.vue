<!-- @format -->

<template>
  <div id="driver-manage" class="my-20">
    <PostManage />
    <TruckManage />
  </div>
</template>

<script>
import PostManage from './PostManage.vue'
import TruckManage from './TruckManage.vue'
export default {
  name: 'DriverManagePage',
  data() {
    return {}
  },
  components: {
    PostManage,
    TruckManage
  },
  computed: {},
  methods: {}
}
</script>

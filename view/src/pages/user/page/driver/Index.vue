<template>
  <div id="driver-manage" class="my-20">
    <PostManage />
    <TruckManage />
  </div>
</template>

<script>
import PostManage from './Post.vue'
import TruckManage from './Truck.vue'
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

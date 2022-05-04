<template>
  <div class="order-information"></div>
</template>

<script>
import { mapActions } from 'vuex'
export default {
  name: 'order-information',
  data() {
    return {}
  },
  methods: {
    ...mapActions({
      getListOrder: 'driver/getListOrder'
    })
  },
  async created() {
    const { data } = await this.getListOrder()
    console.log(data)
  }
}
</script>

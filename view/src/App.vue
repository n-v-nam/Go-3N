<!-- @format -->

<template>
  <div id="app">
    <router-view />
  </div>
</template>

<script>
import { mapActions } from 'vuex'

export default {
  name: 'app',
  watch: {
    '$store.state.app.loading'(val) {
      if (val) this.$vs.loading()
      else this.$vs.loading.close()
    },
    '$store.state.app.notification': {
      handler(val) {
        if (val.show) {
          this.$vs.notify({
            title: '',
            time: 2500,
            text: val.message,
            color: val.type
          })
        }
      },
      deep: true
    }
  },
  methods: {
    ...mapActions({
      setLoading: 'app/setLoading',
      clearAdminToken: 'auth/setToken',
      clearClientToken: 'authClient/setToken'
    })
  }
}
</script>

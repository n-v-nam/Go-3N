<!-- @format -->

<template>
  <div class="dashboard-admin">
    <TitlePage title="Thống kê chung" icon="dashboard" />
    <div class="grid grid-cols-2 gap-x-20 mx-10 mt-10 gap-y-16">
      <div
        class="grid grid-cols-2 gap-x-20 bg-gray-100 rounded-md px-10"
        v-for="(data, index) in dashboardData"
        :key="index"
      >
        <BarChart :dataset="[data.by_time_x2, data.by_time]" :title="data.name" />
        <PieChart :dataset="[data.by_time_x2, data.by_time]" :title="data.name" />
        <p class="title text-center mt-5 col-span-full text-sm mb-4">
          <vs-divider></vs-divider>
          <span class="font-bold">Đồ thị:</span>
          {{ data.name }}
        </p>
      </div>
    </div>
  </div>
</template>

<script>
import { mapActions } from 'vuex'
import BarChart from '@/components/admin/dashboard/BarChart'
import PieChart from '@/components/admin/dashboard/PieChart'

export default {
  name: 'Dashboard',
  components: {
    BarChart,
    PieChart
  },
  data() {
    return {
      time_type: 1,
      dashboardData: []
    }
  },
  async created() {
    const { data } = await this.getDashboardInformation({ time_type: this.time_type })
    this.dashboardData = data
  },
  methods: {
    ...mapActions('dashboard', {
      getDashboardInformation: 'getDashboardInformation'
    })
  }
}
</script>

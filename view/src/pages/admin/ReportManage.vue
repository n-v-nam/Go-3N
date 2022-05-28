<!-- @format -->

<template>
  <div class="report-manage">
    <TitlePage title="Quản lý báo cáo" icon="report" />
    <div class="report-content">
      <vs-table
        noDataText="Chưa có dữ liệu báo cáo"
        v-model="selected"
        class="border-2 border-red-200 mt-4"
        pagination
        max-items="10"
        :data="reports"
      >
        <template slot="thead">
          <vs-th sort-key="report_driver_id">STT</vs-th>
          <vs-th sort-key="report">Người báo cáo</vs-th>
          <vs-th sort-key="target">Vấn đề</vs-th>
          <vs-th sort-key="title">Tiêu đề</vs-th>
          <vs-th sort-key="content">Nội dung</vs-th>
          <vs-th sort-key="status">Trạng thái</vs-th>
          <vs-th>Hành động</vs-th>
        </template>

        <template slot-scope="{ data }">
          <vs-tr :data="prop" :key="index" v-for="(prop, index) in data">
            <vs-td :data="data[index].report_driver_id">
              {{ data[index].report_driver_id }}
            </vs-td>
            <vs-td :data="data[index].report">
              {{ data[index].report }}
            </vs-td>
            <vs-td :data="data[index].target">
              {{ data[index].target }}
            </vs-td>
            <vs-td :data="data[index].title">
              {{ data[index].title }}
            </vs-td>
            <vs-td :data="data[index].content">
              {{ data[index].content }}
            </vs-td>
            <vs-td :data="data[index].status">
              {{ data[index].status ? 'Đã xử lý' : 'Chưa xử lý' }}
            </vs-td>
            <vs-td>
              <span
                v-if="!prop.status"
                class="material-icons mr-2 text-green-600 hover:text-black"
                @click="onRead(prop.report_driver_id)"
              >
                check
              </span>
              <span class="material-icons text-red-400 hover:text-black" @click="onDelete()">delete_forever</span>
            </vs-td>
          </vs-tr>
        </template>
      </vs-table>
    </div>
    <vs-popup title="Chỉnh sửa xe" :active.sync="isShowDialog" button-close-hidden>
      <ReportDetail :report="report" :owner="owner" @clearEvent="clearEvent" @actionDelete="onDelete" />
    </vs-popup>
  </div>
</template>

<script>
import { mapActions } from 'vuex'
import ReportDetail from '@/components/admin/report/View.vue'

export default {
  name: 'ReportManagePage',
  data() {
    return {
      isShowDialog: false,
      statusFilter: 0,
      reports: [],
      statusList: [
        {
          name: 'Chưa duyệt',
          value: 0
        },
        {
          name: 'Đã duyệt',
          value: 1
        }
      ],
      selected: null,
      report: {},
      searchFilter: null,
      owner: {}
    }
  },
  watch: {
    async statusFilter() {
      await this.onSearchByStatus()
    }
  },
  components: {
    ReportDetail
  },
  computed: {},
  methods: {
    ...mapActions('report', {
      getReports: 'getReports',
      readReport: 'readReport',
      createReport: 'createReport',
      deleteReport: 'deleteReport'
    }),
    onDelete() {
      this.$vs.dialog({
        type: 'confirm',
        color: 'danger',
        title: 'Xác nhận xoá ?',
        text: 'Bạn có chắc chắn muốn xoá báo cáo này ?',
        accept: this.actionDelete,
        acceptText: 'Xoá',
        cancelText: 'Thoát'
      })
    },
    clearEvent() {
      this.report = {}
      this.reports = []
      this.isShowDialog = false
      this.isDelete = false
    },
    async actionDelete() {
      await this.deleteReport(this.selected.report_driver_id)
      await this.onFetchReports()
      this.clearEvent()
    },
    async onRead(id) {
      await this.readReport(id)
      await this.onFetchReports()
    },
    async onFetchReports() {
      const { data } = await this.getReports()
      this.reports = data.map(report => {
        if (report.report_type > 1) {
          report.report = report.customer_name ?? report.driver_name
          report.target = 'Báo bug, đóng góp tính năng'
        } else if (report.report_type == 1) {
          report.report = report.customer_name
          report.report_id = report.customer_id
          report.target = 'Báo cáo SĐT ' + report.driver_phone
          report.target_id = report.driver_id
        } else {
          report.target = 'Báo cáo SĐT ' + report.customer_phone
          report.target_id = report.customer_id
          report.report = report.driver_name
          report.report_id = report.driver_id
        }
        return report
      })
    }
  },
  async created() {
    await this.onFetchReports()
  }
}
</script>

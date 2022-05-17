<!-- @format -->

<template>
  <div class="Report-manage">
    <TitlePage title="Quản lý báo cáo" icon="report" />
    <div class="Report-content">
      <vs-table
        noDataText="Chưa có dữ liệu xe"
        v-model="selected"
        class="border-2 border-red-200 mt-4"
        pagination
        max-items="10"
        :data="Reports"
      >
        <template slot="thead">
          <vs-th sort-key="report_id">STT</vs-th>
          <vs-th sort-key="customer_id">Người báo cáo</vs-th>
          <vs-th sort-key="driver_id">Báo cáo người</vs-th>
          <vs-th sort-key="title">Tiêu đề</vs-th>
          <vs-th sort-key="content">Nội dung</vs-th>
          <vs-th sort-key="status">Trạng thái</vs-th>
          <vs-th>Hành động</vs-th>
        </template>

        <template slot-scope="{ data }">
          <vs-tr :data="prop" :key="index" v-for="(prop, index) in data">
            <vs-td :data="data[index].report_id">
              {{ data[index].report_id }}
            </vs-td>
            <vs-td :data="data[index].license_plates">
              {{ data[index].license_plates }}
            </vs-td>
            <vs-td :data="data[index].report_name">
              {{ data[index].Report_name }}
            </vs-td>
            <vs-td :data="data[index].status">
              {{ data[index].status ? 'Đã duyệt' : 'Chưa duyệt' }}
            </vs-td>
            <vs-td :data="data[index].category_Report">
              {{ data[index].category_Report }}
            </vs-td>
            <vs-td :data="data[index].weight_items">
              {{ data[index].weight_items }}
            </vs-td>
            <vs-td>
              <span class="material-icons mr-2 text-blue-600 hover:text-black" @click="onRead(prop.Report_id)">
                visibility
              </span>
              <span class="material-icons text-red-400 hover:text-black" @click="onDelete()">delete_forever</span>
            </vs-td>
          </vs-tr>
        </template>
      </vs-table>
    </div>
    <vs-popup title="Chỉnh sửa xe" :active.sync="isShowDialog" button-close-hidden>
      <ReportDetail :Report="Report" :owner="owner" @clearEvent="clearEvent" @actionDelete="onDelete" />
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
        text: 'Bạn có chắc chắn muốn xoá xe này ?',
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
      await this.deleteReport(this.selected.Report_id)
      await this.onSearchByStatus()
      this.clearEvent()
    },
    async onFetchReports() {
      const { data } = await this.getReports()
      this.reports = data
    }
  },
  async created() {
    await this.onFetchReports()
  }
}
</script>

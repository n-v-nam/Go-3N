<!-- @format -->

<template>
  <div class="post-manage">
    <TitlePage title="Quản lý bài đăng" icon="assignment" />
    <div class="post-content">
      <vs-table
        noDataText="Không có dữ liệu bài đăng"
        v-model="selected"
        class="border-2 border-red-200 mt-4"
        pagination
        max-items="10"
        :data="posts"
      >
        <template slot="header">
          <div class="flex justify-between items-center m-2 mb-8 w-full">
            <div
              @click="onCreate"
              class="flex items-center justify-center p-2 rounded cursor-pointer bg-gray-100 hover:bg-gray-200 border-blue-400 border-2"
            >
              <span class="material-icons text-green-600 mx-2">post_add</span>
              <span class="font-bold">Thêm bài đăng</span>
            </div>
            <div class="flex items-center">
              <vs-select
                placeholder="VD: Xe 10 tấn"
                label="Lọc theo loại bài đăng"
                v-model="approveFilter"
                class="mb-4 pr-2 w-full"
              >
                <vs-select-item
                  :key="index"
                  :value="item.value"
                  :text="item.name"
                  v-for="(item, index) in approveList"
                />
              </vs-select>
              <vs-select
                placeholder="VD: Xe 10 tấn"
                label="Lọc theo trạng thái"
                v-model="statusFilter"
                class="mb-4 pr-2 w-full"
              >
                <vs-select-item
                  :key="index"
                  :value="item.value"
                  :text="item.name"
                  v-for="(item, index) in statusList"
                />
              </vs-select>
              <div class="mt-3 ml-2">
                <vs-button icon="search" @click="onSearch"></vs-button>
              </div>
            </div>
          </div>
        </template>
        <template slot="thead">
          <vs-th sort-key="post_id">Mã bài đăng</vs-th>
          <vs-th sort-key="tittle">Tiêu đề</vs-th>
          <vs-th sort-key="license_plates">Biển số xe</vs-th>
          <vs-th sort-key="phone">SĐT tài xế</vs-th>
          <vs-th sort-key="from_city">Vị trí ban đầu</vs-th>
          <vs-th sort-key="to_city">Vị trí cuối</vs-th>
          <vs-th sort-key="post_type">Loại bài đăng</vs-th>
          <vs-th sort-key="is_approve">Trạng thái duyệt</vs-th>
          <vs-th>Hành động</vs-th>
        </template>

        <template slot-scope="{ data }">
          <vs-tr :data="prop" :key="index" v-for="(prop, index) in data">
            <vs-td :data="data[index].post_id">
              {{ data[index].post_id }}
            </vs-td>
            <vs-td :data="data[index].tittle">
              {{ data[index].tittle }}
            </vs-td>
            <vs-td :data="data[index].license_plates">
              {{ data[index].license_plates }}
            </vs-td>
            <vs-td :data="data[index].phone">
              {{ data[index].phone }}
            </vs-td>
            <vs-td :data="data[index].from_city">
              {{ data[index].from_city }}
            </vs-td>
            <vs-td :data="data[index].to_city">
              {{ data[index].to_city }}
            </vs-td>
            <vs-td :data="data[index].is_approve">
              {{ data[index].is_approve ? 'Đã duyệt' : 'Chưa duyệt' }}
            </vs-td>
            <vs-td :data="data[index].post_type">
              {{ data[index].post_type ? 'Không ghép' : 'Chấp nhận ghép' }}
            </vs-td>
            <vs-td>
              <span class="material-icons mr-2 text-blue-600 hover:text-black" @click="onEdit(prop.post_id)">edit</span>
              <span class="material-icons text-red-400 hover:text-black" @click="onDelete()">delete_forever</span>
              <span
                v-if="isApproveable"
                class="material-icons text-green-400 hover:text-black"
                @click="onApprove(prop.post_id)"
              >
                assignment_return
              </span>
            </vs-td>
          </vs-tr>
        </template>
      </vs-table>
    </div>
    <vs-popup
      :title="isCreate ? 'Thêm bài đăng' : 'Chỉnh sửa bài đăng'"
      :active.sync="isShowDialog"
      button-close-hidden
    >
      <PostDetail
        :post="post"
        :owner="owner"
        :truck="truck"
        @clearEvent="clearEvent"
        @actionCreate="actionCreate"
        @actionEdit="actionEdit"
        @actionDelete="onDelete"
      />
    </vs-popup>
  </div>
</template>

<script>
import { mapActions } from 'vuex'
import { convertToCamelCase, convertToSnackCase } from '@/helpers/convert-keys'
import { createFormData } from '@/helpers/form-data'
import PostDetail from '@/components//admin/post/View.vue'

export default {
  name: 'PostManagePage',
  data() {
    return {
      isShowDialog: false,
      isEdit: false,
      isCreate: false,
      isApproveable: false,
      posts: [],
      approveList: [
        {
          name: 'Chưa duyệt',
          value: 0
        },
        {
          name: 'Đã duyệt',
          value: 1
        }
      ],
      statusList: [
        {
          name: 'Hết hạn',
          value: 1
        },
        {
          name: 'Chưa hết hạn',
          value: 0
        }
      ],
      selected: null,
      post: {
        postItemType: [],
        itemTypeId: []
      },
      approveFilter: 1,
      statusFilter: 0,
      owner: {},
      truck: {}
    }
  },
  components: {
    PostDetail
  },
  computed: {},
  methods: {
    ...mapActions('post', {
      getPosts: 'getPosts',
      getPost: 'getPost',
      createPost: 'createPost',
      updatePost: 'updatePost',
      deletePost: 'deletePost',
      approvePost: 'approvePost'
    }),
    async onEdit(id) {
      const res = await this.getPost(id)
      this.post = convertToCamelCase(res.data.post_information)
      this.post.postItemType = Object.keys(this.post.postItemType)
      this.post.itemTypeId = Object.keys(this.post.postItemType)
      this.owner = convertToCamelCase(res.data.customer_information)
      this.truck = convertToCamelCase(res.data.truck_information)
      this.isEdit = true
      this.isCreate = false
      this.isShowDialog = true
    },
    onDelete() {
      this.$vs.dialog({
        type: 'confirm',
        color: 'danger',
        title: 'Xác nhận xoá ?',
        text: 'Bạn có chắc chắn muốn xoá bài đăng này ?',
        accept: this.actionDelete,
        acceptText: 'Xoá',
        cancelText: 'Thoát'
      })
    },
    async onApprove(id) {
      await this.approvePost(id)
      await this.onSearch()
    },
    onCreate() {
      this.post = {
        itemTypeId: []
      }
      this.isCreate = true
      this.isEdit = false
      this.isShowDialog = true
    },
    clearEvent() {
      this.post = {}
      this.truck = {}
      this.isCreate = false
      this.isEdit = false
      this.isShowDialog = false
      this.isDelete = false
    },
    async actionCreate() {
      await this.createPost(createFormData(convertToSnackCase(this.post)))
      await this.onSearch()
      this.clearEvent()
    },
    async actionEdit() {
      await this.updatePost(createFormData(convertToSnackCase(this.post), true))
      await this.onSearch()
      this.clearEvent()
    },
    async actionDelete() {
      await this.deletePost(this.selected.post_id)
      await this.onSearch()
      this.clearEvent()
    },
    async onSearch() {
      const res = await this.getPosts({ status: this.statusFilter, isApprove: this.approveFilter })
      this.posts = res.data
      this.isApproveable = !this.statusFilter && !this.approveFilter
    }
  },
  async created() {
    await this.onSearch()
  }
}
</script>

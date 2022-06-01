<!-- @format -->

<template>
  <div id="post-manage" class="my-20">
    <div class="header flex">
      <vs-icon class="text-2xl mr-2" icon="arrow_right"></vs-icon>
      <p class="font-bold text-2xl">Quản lý bài đăng của bạn</p>
    </div>
    <p class="font-thin italic text-sm mt-4 text-red-600">*Bạn có thể lọc dữ liệu theo các lựa chọn tương ứng</p>
    <div class="post-content">
      <vs-table
        noDataText="Không có dữ liệu bài đăng"
        v-model="selected"
        class="border-2 border-red-200 mt-1"
        pagination
        max-items="10"
        :data="posts"
      >
        <template slot="header">
          <div class="flex justify-between items-center m-2 mb-8 w-full">
            <div
              @click="$router.push('/post')"
              class="flex items-center p-2 rounded cursor-pointer bg-gray-100 hover:bg-gray-200 border-blue-400 border-2"
            >
              <span class="material-icons text-green-600 mx-2">assignment</span>
              <span class="font-bold">Thêm bài viết</span>
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
            <vs-td :data="data[index].from_city">
              {{ data[index].from_city }}
            </vs-td>
            <vs-td :data="data[index].from_city">
              {{ data[index].to_city }}
            </vs-td>
            <vs-td :data="data[index].post_type">
              {{ !data[index].post_type ? 'Không ghép' : 'Chấp nhận ghép' }}
            </vs-td>
            <vs-td :data="data[index].is_approve">
              {{ data[index].is_approve ? 'Đã duyệt' : 'Chưa duyệt' }}
            </vs-td>
            <vs-td>
              <span class="material-icons mr-2 text-blue-600 hover:text-black" @click="onEdit(prop.post_id)">edit</span>
              <span class="material-icons text-red-400 hover:text-black" @click="onDelete()">delete_forever</span>
            </vs-td>
          </vs-tr>
        </template>
      </vs-table>
    </div>
    <vs-popup title="Chỉnh sửa bài đăng" :active.sync="isShowDialog" button-close-hidden>
      <PostForm :post="post" :owner="owner" :truck="truck" @clearEvent="clearEvent" />
      <div class="action flex justify-end gap-5">
        <vs-button color="success" icon="assignment" @click="actionEdit">Lưu</vs-button>
        <vs-button color="danger" icon="delete" @click="actionDelete">Xoá</vs-button>
        <vs-button color="lightgray" icon="close" @click="clearEvent">Thoát</vs-button>
      </div>
    </vs-popup>
  </div>
</template>

<script>
import { mapActions } from 'vuex'
import { convertToCamelCase, convertToSnackCase } from '@/helpers/convert-keys'
import { createFormData } from '@/helpers/form-data'
import PostForm from '@/components/user/post/View.vue'

export default {
  name: 'PostManagePage',
  data() {
    return {
      isShowDialog: false,
      isEdit: false,
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
          value: 0
        },
        {
          name: 'Chưa hết hạn',
          value: 1
        }
      ],
      selected: null,
      post: {
        itemTypeId: []
      },
      approveFilter: 1,
      statusFilter: 0,
      owner: {},
      truck: {}
    }
  },
  components: {
    PostForm
  },
  methods: {
    ...mapActions('driver', {
      getPosts: 'getPostsByDriver',
      getPost: 'getPostByDriver',
      updatePost: 'updatePostByDriver',
      deletePost: 'deletePostByDriver'
    }),
    async onEdit(id) {
      const res = await this.getPost(id)
      this.post = convertToCamelCase(res.data.post_information)
      this.post.itemTypeId = Object.keys(this.post.postItemType)
      this.owner = convertToCamelCase(res.data.customer_information)
      this.truck = convertToCamelCase(res.data.truck_information)
      this.post.endDate = this.post.endDate.substring(0, 10)
      this.post.truckId = this.truck.truckId
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
    clearEvent() {
      this.truck = {}
      this.owner = {}
      this.post = {}
      this.isCreate = false
      this.isEdit = false
      this.isShowDialog = false
      this.isDelete = false
    },
    async actionEdit() {
      const formData = createFormData(convertToSnackCase(this.post), true)
      formData.delete('image')
      console.log(this.post.postImage)
      for (let i = 0; i < this.post.postImage.length; i++) {
        formData.append('image[]', this.post.postImage[i])
      }
      await this.updatePost(formData)
      await this.onSearch()
      this.clearEvent()
    },
    async actionDelete() {
      await this.deletePost(this.selected.post_id)
      await this.onSearch()
      this.clearEvent()
    },
    async onSearch() {
      const { data } = await this.getPosts({ status: this.statusFilter, isApprove: this.approveFilter })
      this.posts = data
    }
  },
  async created() {
    await this.onSearch()
  }
}
</script>

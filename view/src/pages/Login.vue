<!-- @format -->

<template>
  <div id="login" class="mx-2">
    <vs-row class="h-screen">
      <vs-col vs-w="8" class="flex flex-col justify-center items-center h-full">
        <img src="@/assets/img/admin-logo.png" alt="">
        <span class="text-4xl font-bold">ĐÂY LÀ TRANG ADMIN</span>
      </vs-col>
      <vs-col vs-w="4" class="bg-green-500 h-full rounded-xl">
        <div class="flex flex-col mt-56">
          <div class="font-semibold text-center text-white text-4xl">LOGIN</div>
          <div class="flex flex-col justify-center items-center">
            <vs-input label="Username" class="w-3/4 mt-16" v-model="username" />
            <vs-input type="password" label="Password" class="w-3/4 mt-4" v-model="password" />
          </div>
          <div class="flex justify-evenly items-center mt-8">
              <vs-button @click="handleLogin">LOGIN</vs-button>
                <span class="text-white cursor-pointer hover:text-gray-300">Quên mật khẩu ?</span>
          </div>
        </div>
      </vs-col>
    </vs-row>
  </div>
</template>

<script>
export default {
    data(){
        return {
            username: null,
            password: null,
        }
    }
}
</script>

<style lang="scss"></style>

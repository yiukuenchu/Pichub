<template>
  <el-row class="container">
    <el-col :span="24">
      <div class="nav-logo">
          <a href="/"><img src="../../assets/camera.png" style="padding-left: 8px;"></a>
      </div>
      <div class="nav-title" style="margin-left: 80px">
        <el-col :span="24">
          <el-menu :default-active="activeIndex" class="el-menu-demo" mode="horizontal" @select="handleSelect">
            <el-menu-item index="explore">发现</el-menu-item>
            <el-menu-item index="about">关于</el-menu-item>
            <el-menu-item index="license">版权</el-menu-item>
          </el-menu>
        </el-col>
      </div>
    </el-col>
  </el-row>
</template>

<script>
    export default {
      name: 'home',
      data() {
        return {
          defaultActiveIndex: "/",
        };
      },
      methods: {
        handleSelect(key, keyPath) {
          console.log(key, keyPath);
        }
      },
    }
</script>

<style scoped>

</style>

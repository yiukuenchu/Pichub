/**
* @author yiukuenchu
* @version 创建时间：${date} ${time}
* 类说明
*/

<template>
  <el-container>

    <div class="el-tabs__nav">
      <a><img src="../../assets/camera.png" width="34" height="31" style="margin-left: 10px" ></a>
    </div>

    <div class="el-input" style="margin-left: 20px; width: 700px; " >
      <el-input v-model="input" type="text" size="medium" prefix-icon="el-icon-search" placeholder="搜索获取高质量图片" clearable="true">
      </el-input>
    </div>

    <div>
      <el-menu :default-active="activeIndex" class="el-menu-demo" mode="horizontal" @select="handleSelect">
        <el-menu-item index="explore">发现</el-menu-item>
        <el-menu-item index="about">关于</el-menu-item>
        <el-menu-item index="license">版权</el-menu-item>
        <el-menu-item index="more">
          <el-dropdown trigger="click">
           <i class="el-icon-more"></i>
            <el-dropdown-menu slot="dropdown">
              <el-dropdown-item>社区</el-dropdown-item>
              <el-dropdown-item>商店</el-dropdown-item>
              <el-dropdown-item>帮助</el-dropdown-item>
              <el-dropdown-item>License</el-dropdown-item>
            </el-dropdown-menu>
          </el-dropdown>
        </el-menu-item>
      </el-menu>
    </div>
  </el-container>
</template>


<script>
    export default {
      name: 'home',
      data() {
        return {
          defaultActiveIndex: "/",
        }
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

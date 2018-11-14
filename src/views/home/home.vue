/**
* @author yiukuenchu
* @version 创建时间：${date} ${time}
* 类说明
*/

<template>
  <div>
    <el-row class="el-tabs__nav">
      <el-col :span="24">
        <div class="el-tabs__nav">
            <a><img src="../../assets/camera.png" width="34" height="31" style="margin-left: 10px" ></a>
        </div>
        <div class="el-input" style="margin-left: 20px">
          <el-input v-model="input" type="text" size="medium"  placeholder="搜索获取高质量图片">
          </el-input>
        </div>
        <div class="el-tabs__nav" style="margin-left: 20px" >
          <el-col :span="80">
            <el-menu :default-active="activeIndex" class="el-menu-demo" mode="horizontal" @select="handleSelect">
              <el-menu-item index="explore">发现</el-menu-item>
              <el-menu-item index="about">关于</el-menu-item>
              <el-menu-item index="license">版权</el-menu-item>
            </el-menu>
          </el-col>
        </div>
      </el-col>
    </el-row>
  </div>
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

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="csrf-token" content="@{{ csrf_token }}">
  <title>jennycrawl</title>
</head>
<body>
  <div id="app">
    <div class="container">
        <el-menu :default-active="this.$route.path" router class="el-menu-demo" mode="horizontal" background-color="#545c64" text-color="#fff" active-text-color="#ffd04b">
           <el-submenu index="1">
             <template slot="title">微博</template>
             <el-menu-item index="/weibo/statistics">统计结果</el-menu-item>
             <el-menu-item index="/weibo/account">账号管理</el-menu-item>
           </el-submenu>
           <el-submenu index="wechat">
             <template slot="title">微信</template>
             <el-menu-item index="/wechat/statistics">统计结果</el-menu-item>
             <el-menu-item index="/wechat/account">公众号配置</el-menu-item>
           </el-submenu>
        </el>
    </div>
      <router-view></router-view>
    </div>
  </div>
  <script type="text/javascript" src="/js/app.js"></script>
</body>
</html>

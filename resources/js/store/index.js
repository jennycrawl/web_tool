import Vue from 'vue';
import Vuex from 'vuex';
import WechatAccount from './wechat_account';
import WechatStatistics from './wechat_statistics';
import Weibo from './weibo';
Vue.use(Vuex);
export default new Vuex.Store({
  // 可以设置多个模块
  modules: {
    WechatAccount:WechatAccount,
    WechatStatistics:WechatStatistics,
    Weibo:Weibo,
  }
});


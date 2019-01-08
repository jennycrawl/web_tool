import Vue from 'vue';
import Vuex from 'vuex';
import Wechat from './wechat';
import Weibo from './weibo';
Vue.use(Vuex);
export default new Vuex.Store({
  // 可以设置多个模块
  modules: {
    Wechat:Wechat,
    Weibo:Weibo,
  }
});


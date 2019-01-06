import api from '../api';
export default{
  namespaced: true,
  state: {
    recommend: [], // 推荐
    list: [],  // 列表
    detail: {}  // 详情
  },
  mutations: {
    // 注意，这里可以设置 state 属性，但是不能异步调用，异步操作写到 actions 中
    SETRECOMMEND(state, list) {
      state.recommend = list;
    },
    SETLIST(state, list) {
      state.list = list;
    },
    SETDETAIL(state, detail) {
      state.detail = detail;
    }
  },
  actions: {
    getWechatAccountDetail({commit}, id) {
      // 获取详情，并调用 mutations 设置 detail
      api.getWechatAccountDetail(id).then(function(res) {
        commit('SETDETAIL', res.data);
        document.body.scrollTop = 0;
      });
    },
    getWechatAccountRecommend({commit}) {
      api.getWechatAccountRecommend().then(function(res) {
        commit('SETRECOMMEND', res.data);
      });
    },
    getWechatAccountList({commit}) {
      api.getWechatAccountList().then(function(res) {
        if (res.data['success'] && res.data['msg']) {
          commit('SETLIST', res.data.msg);
        } else {
          commit('SETLIST', []);
        }
      });
    }
  }
}

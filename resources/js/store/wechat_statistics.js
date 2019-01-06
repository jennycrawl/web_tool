import api from '../api';
export default{
  namespaced: true,
  state: {
    list: [],  // 列表
  },
  mutations: {
    // 注意，这里可以设置 state 属性，但是不能异步调用，异步操作写到 actions 中
    SETLIST(state, list) {
      state.list = list;
    },
  },
  actions: {
    getWechatStatisticsList({commit}, params) {
      api.getWechatStatisticsList(params).then(function(res) {
        if (res.data['success'] && res.data['msg']) {
          commit('SETLIST', res.data.msg);
        } else {
          commit('SETLIST', []);
        }
      });
    },
  }
}

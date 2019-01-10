import api from '../api';
export default{
  namespaced: true,
  state: {
    statisticsList: [],  // 列表
    msgList: [],
    accountList: [],
  },
  mutations: {
    // 注意，这里可以设置 state 属性，但是不能异步调用，异步操作写到 actions 中
    setStatisticsList(state, list) {
      state.statisticsList = list;
    },
    setMsgList(state, list) {
      state.msgList = list;
    },
    setAccountList(state, list) {
      state.accountList = list;
    },
  },
  actions: {
    getStatisticsList({commit}, params) {
      api.getWeiboStatisticsList(params).then(function(res) {
        if (res.data['success'] && res.data['msg']) {
          commit('setStatisticsList', res.data.msg);
        } else {
          commit('setStatisticsList', []);
        }
      });
    },
    getMsgList({commit}, params) {
      api.getWeiboMsgList(params).then(function(res) {
        if (res.data['success'] && res.data['msg']) {
          commit('setMsgList', res.data.msg);
        } else {
          commit('setMsgList', []);
        }
      });
    },
    getAccountList({commit}, params) {
      api.getWeiboAccountList(params).then(function(res) {
        if (res.data['success'] && res.data['msg']) {
          commit('setAccountList', res.data.msg);
        } else {
          commit('setAccountList', []);
        }
      });
    },
    insertAccount({commit}, params) {
      return api.insertWeiboAccount(params).then(function(res) {
        return {status:res.status,data:res.data}
      });
    },
    updateAccount({commit}, {id, params}) {
      return api.updateWeiboAccount(id, params).then(function(res) {
        return {status:res.status,data:res.data}
      });
    },
    deleteAccount({commit}, {id, params}) {
      return api.deleteWeiboAccount(id, params).then(function(res) {
        return {status:res.status,data:res.data}
      });
    },
  }
}

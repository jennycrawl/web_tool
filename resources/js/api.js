import axios from 'axios'
export default {
  // 列表接口
  getWechatAccountList: function (params) {
    return axios.get('http://jennycrawl.jerehu.com/api/wechat/account/list', {
      params: params
    })
  },
  insertWechatAccount: function (params) {
    return axios.post('http://jennycrawl.jerehu.com/api/wechat/account', params);
  },
  updateWechatAccount: function (id, params) {
    return axios.put('http://jennycrawl.jerehu.com/api/wechat/account/' + id, params);
  },
  deleteWechatAccount: function (id, params) {
    return axios.delete('http://jennycrawl.jerehu.com/api/wechat/account/' + id, params);
  },
  getWechatStatisticsList: function (params) {
    return axios.get('http://jennycrawl.jerehu.com/api/wechat/statistics', {
      params: params
    })
  },
  getWechatMsgList: function (params) {
    return axios.get('http://jennycrawl.jerehu.com/api/wechat/msg', {
      params: params
    })
  },

  getWeiboAccountList: function (params) {
    return axios.get('http://jennycrawl.jerehu.com/api/weibo/account/list', {
      params: params
    })
  },
  insertWeiboAccount: function (params) {
    return axios.post('http://jennycrawl.jerehu.com/api/weibo/account', params);
  },
  updateWeiboAccount: function (id, params) {
    return axios.put('http://jennycrawl.jerehu.com/api/weibo/account/' + id, params);
  },
  deleteWeiboAccount: function (id, params) {
    return axios.delete('http://jennycrawl.jerehu.com/api/weibo/account/' + id, params);
  },
  getWeiboStatisticsList: function (params) {
    return axios.get('http://jennycrawl.jerehu.com/api/weibo/statistics', {
      params: params
    })
  },
  getWeiboMsgList: function (params) {
    return axios.get('http://jennycrawl.jerehu.com/api/weibo/msg', {
      params: params
    })
  },
}

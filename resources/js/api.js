import axios from 'axios'
export default {
  // 列表接口
  getWechatAccountList: function (params) {
    return axios.get('http://jennycrawl.jerehu.com/api/wechat/account/list', {
      params: params
    })
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

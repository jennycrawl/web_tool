export default[
  { path: '/', redirect: '/weibo/statistics' },
  { path: '/wechat/account', component: require('./page/wechat_account_list.vue') },
  { path: '/wechat/statistics', component: require('./page/wechat_statistics.vue') },
  { path: '/wechat/msg', component: require('./page/wechat_msg.vue') },
  { path: '/weibo/account', component: require('./page/weibo_account_list.vue') },
  { path: '/weibo/statistics', component: require('./page/weibo_statistics.vue') },
  { path: '/weibo/msg', component: require('./page/weibo_msg.vue') },
];

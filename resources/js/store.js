import Vue from 'vue'
import Vuex from 'vuex'
import createPersistedState from 'vuex-persistedstate'

Vue.use(Vuex)

export default new Vuex.Store({
  state: {
    companyId: 0,
    clientClasses: [],
    clientClasses2: [],
    peopleClasses: [],
    subjectClasses: [],
    taskClasses: [],
    productClasses: [],
    invoiceClasses: [],
    scheduleClasses: [],
    processNames: [],
    unitNames: [],
    editItems: [],
    colors: [],
    billPaymentClass: [],
    unitNgroupEditItemsmes: [],
    editItems: [],
    loginTime: 0,
    alarms:[],
  },
  mutations: {
    setProsess(state, payload) {
      state.process = payload.process;
    },
    setSysItems(state, payload) {
      state.companyId = payload.sysItems.companyId;
      state.clientClasses = payload.sysItems.clientClasses;
      state.clientClasses2 = payload.sysItems.clientClasses2;
      state.peopleClasses = payload.sysItems.peopleClasses;
      state.subjectClasses = payload.sysItems.subjectClasses;
      state.taskClasses = payload.sysItems.taskClasses;
      state.productClasses = payload.sysItems.productClasses;
      state.taskColors = payload.sysItems.taskColors;
      state.invoiceClasses = payload.sysItems.invoiceClasses;
      state.scheduleClasses = payload.sysItems.scheduleClasses;
      state.processNames = payload.sysItems.processNames;
      state.unitNames = payload.sysItems.unitNames;
      state.editItems = payload.sysItems.editItems;
      state.colors = payload.sysItems.colors;
      state.billPaymentClass = payload.sysItems.billPaymentClass;
      state.unitNgroupEditItemsmes = payload.sysItems.unitNgroupEditItemsmes;
      state.editItems = payload.sysItems.editItems;
      state.loginTime = payload.sysItems.loginTime;
      state.alarms = payload.sysItems.alarms;
    },
    addAlarm(state, payload) {
      state.alarms.push(payload.value);
    },
    updateAlarm(state, payload) {
      const index = state.alarms.findIndex((alarm) => alarm.id === payload.value.id);
      state.alarms.splice(index  , 1);
      state.alarms.push(payload.value);
    },
    deleteAlarm(state, payload) {
      const index = state.alarms.findIndex((alarm) => alarm.id === payload.value.id);
      state.alarms.splice(index  , 1);
    }
  },
  getters: {
    getCompanyId(state) {
        return state.companyId;
    },
    getClientClasses(state) {
      return state.clientClasses;
    },
    getClientClasses2(state) {
      return state.clientClasses2;
    },
    getPeopleClasses(state) {
      return state.peopleClasses;
    },
    getSubjectClasses(state) {
        return state.subjectClasses;
    },
    getTaskClasses(state) {
        return state.taskClasses;
    },
    getProductClasses(state) {
        return state.productClasses;
    },
    getTaskColors(state) {
        return state.taskColors;
    },
    getInvoiceClasses(state) {
        return state.invoiceClasses;
    },
    getScheduleClasses(state) {
        return state.scheduleClasses;
    },
    getProcessNames(state) {
        return state.processNames;
    },
    getTaxClasses(state) {
      return [
        {name : '内税', no : 1}, 
        {name : '外税', no : 2}, 
        {name : 'その他', no : 3}, 
      ]
    },
    getUnitNames(state) {
      return state.unitNames;
    },
    getEditItems(state) {
      return state.editItems;
    },
    getColors(state) {
      return state.colors;
    },
    getBillPaymentClass(state) {
      return state.billPaymentClass;
    },
    getLoginTime(state) {
      return state.loginTime;
    },
    getAlarms(state) {
      return state.alarms;
    },
  },
  action: {
  },
  // ストレージ保存のためのプラグインを有効化
  plugins: [createPersistedState({
      key: 'current-process',
      storage: localStorage
  })]
})

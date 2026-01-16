import Vue from 'vue';
import Vuex from 'vuex';
import auth from './modules/auth';

Vue.use(Vuex);

export default new Vuex.Store({
  modules: { auth },
  state: {
    filters: { region: null, period: 'Q1' }
  },
  mutations: {
    SET_REGION(state, val) { state.filters.region = val; },
    SET_PERIOD(state, val) { state.filters.period = val; }
  },
  getters: {
    currentFilters: state => state.filters
  }
});

import axios from '../../service/axios';

const state = {
  user: null,
  token: localStorage.getItem('token') || null,
};

const getters = {
  isAuthenticated: state => !!state.token,
  user: state => state.user,
  userRole: state => (state.user ? state.user.role : null),
};

const mutations = {
  SET_USER(state, user) {
    state.user = user;
  },
  SET_TOKEN(state, token) {
    state.token = token;
    localStorage.setItem('token', token);
  },
  LOGOUT(state) {
    state.user = null;
    state.token = null;
    localStorage.removeItem('token');
  },
};

const actions = {
  async login({ commit, dispatch }, credentials) {
    const response = await axios.post('/login', credentials);
    commit('SET_TOKEN', response.data.token);
    await dispatch('fetchUser');

  },

  async fetchUser({ commit }) {
    try {
      const response = await axios.get('/user');
      commit('SET_USER', response.data);
      return response.data;
    } catch (error) {
      console.error('Failed to fetch user:', error);
      commit('LOGOUT'); // Token invalid
    }
  },

  async register({ commit, dispatch }, userData) {
    const response = await axios.post('/register', userData);
    commit('SET_TOKEN', response.data.token);
    await dispatch('fetchUser');
  },
  async setUserRole({ commit }, data) {
    try {
      const response = await axios.post('/user/set/role', data);
      return response.data;
    } catch (error) {
      console.error('Failed to fetch user:', error);
    }
  },
  logout({ commit }) {
    commit('LOGOUT');
  },
};

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions,
};

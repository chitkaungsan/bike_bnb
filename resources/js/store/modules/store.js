// store/modules/store.js
import axios from '../../service/axios';

 const state = () => ({
  store: {
    'user_id': '',
    name: '',
    description: '',
    location: '',
    logo: null,
    cover_photo: null,
    front: null,
    side: null,
    outside: null,
    inside: null,
    loading: false,
    address: '',
    phone: '',
  },
});

const mutations = {
  SET_STORE_FIELD(state, { key, value }) {
    state.store[key] = value;
  },
  SET_LOADING(state, loading) {
    state.loading = loading;  
  },

  RESET_STORE(state) {
    state.store = {
      name: '',
      description: '',
      location: '',
      logo: null,
      cover_photo: null,
      front: null,
      side: null,
      outside: null,
      inside: null,
    };
  },
};

const actions = {
  setField({ commit }, payload) {
    commit('SET_STORE_FIELD', payload);
  },

  async submitStore({ state, commit }) {
     commit("SET_LOADING", true);
    try {
      const formData = new FormData();
      formData.append('user_id', state.store.user_id);
      formData.append('name', state.store.name);
      formData.append('description', state.store.description);
      formData.append('location', state.store.location);
      formData.append('address', state.store.address);
      formData.append('phone', state.store.phone);

      ['logo', 'cover_photo', 'front', 'side', 'outside', 'inside'].forEach((key) => {
        if (state.store[key]) formData.append(key, state.store[key]);
      });
      console.log('formData', formData);
      const response = await axios.post('/store/add', formData, {
        headers: { 'Content-Type': 'multipart/form-data' },
      });

      // Optionally reset store after submit
      commit('RESET_STORE');
      return response.data;
    } catch (error) {
      console.error('Failed to submit store:', error);
      throw error;
    }finally {
      commit("SET_LOADING", false);
    }
  },
  async fetchStoreList({ commit },id) {
    console.log('uder id', id);
    try {
      const response = await axios.get(`/get/stores/${id}`);
      console.log('response', response);
      return response.data;
    } catch (error) {
      console.error('Failed to fetch store list:', error);
      throw error;
    }
  }
};

const getters = {
  store: state => state.store,
  loading: state => state.loading
};
export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions,
};

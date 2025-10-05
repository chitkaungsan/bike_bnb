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
  },
});

const mutations = {
  SET_STORE_FIELD(state, { key, value }) {
    state.store[key] = value;
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
    try {
      const formData = new FormData();
      formData.append('user_id', state.store.user_id);
      formData.append('name', state.store.name);
      formData.append('description', state.store.description);
      formData.append('location', state.store.location);

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
    }
  },
};

const getters = {
  store: state => state.store,
};
export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions,
};

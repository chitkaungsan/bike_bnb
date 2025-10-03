import axios from 'axios';

 const state = {
    user: null,
    token: localStorage.getItem('token') || null,
};

 const getters = {
    isAuthenticated: state => !!state.token,
    user: state => state.user,
};

 const mutations = {
    SET_USER(state, user) {
        state.user = user;
    },
    SET_TOKEN(state, token) {
        state.token = token;
        localStorage.setItem('token', token); // Persist token
    },
    LOGOUT(state) {
        state.user = null;
        state.token = null;
        localStorage.removeItem('token');
    },
};

 const actions = {
    async register({ commit }, credentials) {
        // We add 'await' here to make the component wait for completion
        const response = await axios.post('/api/register', credentials);
        commit('SET_USER', response.data.user);
        commit('SET_TOKEN', response.data.token);
    },
     async login({ commit, dispatch }, credentials) {
        const response = await axios.post('/api/login', credentials);
        commit('SET_TOKEN', response.data.token);
        console.log('credtials', credentials);
        await dispatch('fetchUser');
    },
      async fetchUser({ commit }) {
        try {
            const config = {
                headers: { Authorization: `Bearer ${localStorage.getItem('token')}` }
            };
            const response = await axios.get('/api/user', config);
            commit('SET_USER', response.data);
        } catch (error) {
            console.error("Failed to fetch user:", error);
            // If the token is invalid, you could dispatch a logout action here
        }
    },

    async register({ commit, dispatch }, userData) {
        const response = await axios.post('/api/register', userData);
        commit('SET_TOKEN', response.data.token);
        await dispatch('fetchUser');
    },

    logout({ commit }) {
        commit('logout');
    }

    // Your existing login/logout actions...
};
export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations,
}

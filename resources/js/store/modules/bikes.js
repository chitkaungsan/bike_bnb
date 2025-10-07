import { createStore } from "vuex"
import axios from "../../service/axios"

const state = {
  bike: null,
  loadig: false
}

const mutations = {
  SET_BIKE(state, bike) {
    state.bike = bike
  },
  SET_LOADING(state, loading) {
    state.loading = loading
  },

}

const actions = {
  async addBike({ commit }, formData) {
    commit("SET_LOADING", true);
    try {
      const response = await axios.post("/bike/store", formData, {
        headers: {
          "Content-Type": "multipart/form-data",
        },
      });

      // Commit mutation if needed
      commit("SET_BIKE", response.data);

      return response.data; // optional, can use in component
    } catch (error) {
      console.error("Failed to add bike:", error.response || error);
      throw error; // so component can handle alert
    }finally {
      commit("SET_LOADING", false);
    }
  },
};
const getters = {
  bike: (state) => state.bike,
  loading: (state) => state.loading
}

export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations,
}

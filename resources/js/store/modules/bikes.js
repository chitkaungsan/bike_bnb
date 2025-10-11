import { createStore } from "vuex"
import axios from "../../service/axios"

const state = {
  bike: null,
  loadig: false,
   bikes: {
    data: [],
    current_page: 1,
    last_page: 1,
    per_page: 5,
    total: 0,
  },
}

const mutations = {
  SET_BIKE(state, bike) {
    state.bike = bike
  },
  SET_LOADING(state, loading) {
    state.loading = loading
  },
  SET_BIKES(state, bikes) {
    state.bikes = bikes
  },
   SET_BIKES(state, payload) {
    state.bikes = payload;
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
 async fetchBikeList({ commit }, id) {
  commit("SET_LOADING", true);
  try {
    const response = await axios.get(`get/bikes/${id}`);
    commit("SET_BIKE", response.data);
    return response.data;
  } catch (error) {
    console.error("Failed to fetch bike list:", error);
    throw error; // rethrow if you want to handle it elsewhere
  } finally {
    commit("SET_LOADING", false);
  }
},
async fetchAllBikes({ commit },page) {
  commit("SET_LOADING", true);
  try {
    const response = await axios.get(`get/bike/all?page=${page}`);
    commit("SET_BIKES", response.data);
    return response;
  } catch (error) {
    console.error("Failed to fetch bike list:", error);
    throw error; 
  } finally {
    commit("SET_LOADING", false);
  }
},

    async fetchBikeById({ getters }, id) {
    let bike = getters.getBikeById(id);
    if (bike) return bike;

    try {
      const res = await axios.get(`bikes/${id}`);
      return res.data; // single bike object
    } catch (err) {
      console.error("Failed to fetch bike by id:", err);
      return null;
    }
  },
    async getBike({ dispatch }, id) {
    return await dispatch("fetchBikeById", id);
  },
};
const getters = {
  bike: (state) => state.bike,
  loading: (state) => state.loading,
  bikes: (state) => state.bikes,
   getBikeById: (state) => (id) => {
    if (!state.bikes || !state.bikes.data) return null;
    return state.bikes.data.find((bike) => bike.id === id) || null;
  },
}

export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations,
}

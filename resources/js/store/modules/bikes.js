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
  store_bikes: [],
  bike_details: [],
  categories: [],
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
  SET_BIKE_DETAILS(state, bike_details) {
    state.bike_details = bike_details
  },
  SET_STORE_BIKES(state, store_bikes) {
    state.store_bikes = store_bikes
  },
  SET_CATEGORIES(state, categories) {
    state.categories = categories
  }  

}

const actions = {
  async fetchAllCategories({ commit }) {
    try {
      const response = await axios.get("/get/all/categories");
      commit("SET_CATEGORIES", response.data);
    } catch (error) {
      console.error("Failed to fetch categories:", error);
    } 
  },
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
async fetchAllBikesWithId({ commit },payload) {
  // fetch all bike with store id 
  commit("SET_LOADING", true);
  const {id,page} = payload
  try {
    const response = await axios.get(`get/bike/all/${id}?page=${page}`);
    commit("SET_STORE_BIKES", response.data);
    return response;
  } catch (error) {
    console.error("Failed to fetch bike list:", error);
    throw error; 
  } finally {
    commit("SET_LOADING", false);
  }
},

    async fetchBikeById({ getters, commit }, id) {
    let bike = getters.getBikeById(id);
    if (bike) return bike;

    try {
      const res = await axios.get(`bikes/${id}`);
      commit("SET_BIKE_DETAILS", res.data); 
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
  bike_details: (state) => state.bike_details,
  store_bikes: (state) => state.store_bikes,
  categories: (state) => state.categories
}

export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations,
}

import axios from 'axios'

const state = {
  cities: [],
  loading: false,
  error: null,
}

const getters = {
  allCities: (state) => state.cities,
  isLoading: (state) => state.loading,
  cityError: (state) => state.error,
}

const actions = {
  async fetchCities({ commit }) {
    commit('setLoading', true)
    commit('setError', null)
    try {
      const response = await axios.get('/api/cities')
      commit('setCities', response.data)
    } catch (error) {
      commit('setError', error.response?.data?.message || 'Failed to fetch cities')
    } finally {
      commit('setLoading', false)
    }
  },

  async addCity({ commit }, cityData) {
    try {
      const response = await axios.post('/api/cities', cityData)
      commit('addCity', response.data)
    } catch (error) {
      commit('setError', error.response?.data?.message || 'Failed to add city')
    }
  },

  async updateCity({ commit }, cityData) {
    try {
      const response = await axios.put(`/api/cities/${cityData.id}`, cityData)
      commit('updateCity', response.data)
    } catch (error) {
      commit('setError', error.response?.data?.message || 'Failed to update city')
    }
  },

  async deleteCity({ commit }, id) {
    try {
      await axios.delete(`/api/cities/${id}`)
      commit('removeCity', id)
    } catch (error) {
      commit('setError', error.response?.data?.message || 'Failed to delete city')
    }
  },
}

const mutations = {
  setCities(state, cities) {
    state.cities = cities
  },
  addCity(state, city) {
    state.cities.push(city)
  },
  updateCity(state, updatedCity) {
    const index = state.cities.findIndex((c) => c.id === updatedCity.id)
    if (index !== -1) state.cities.splice(index, 1, updatedCity)
  },
  removeCity(state, id) {
    state.cities = state.cities.filter((c) => c.id !== id)
  },
  setLoading(state, status) {
    state.loading = status
  },
  setError(state, message) {
    state.error = message
  },
}

// 👇 Export module (as you asked: all exports at bottom)
export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations,
}

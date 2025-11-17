import { createStore } from "vuex"
import axios from "axios"

const state = {
  user: null,
  token: null,
}

const mutations = {
  setUser(state, user) {
    state.user = user
  },
  setToken(state, token) {
    state.token = token
  },
}

const actions = {
  async login({ commit }, credentials) {
    const response = await axios.post("/login", credentials)
    commit("setUser", response.data.user)
    commit("setToken", response.data.token)
  },
  async register({ commit }, userData) {
    const response = await axios.post("/register", userData)
    commit("setUser", response.data.user)
    commit("setToken", response.data.token)
  },
  async logout({ commit }) {
    await axios.post("/logout")
    commit("setUser", null)
    commit("setToken", null)
  },
}

const getters = {
  isAuthenticated: (state) => !!state.token,
  user: (state) => state.user,
}

export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations,
}

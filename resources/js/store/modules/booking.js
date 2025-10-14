import axios from "../../service/axios"
const state = {
  bookings: [],
  booking: null,
  loading: false,
  error: null,
};

const getters = {
  allBookings: (state) => state.bookings,
  singleBooking: (state) => state.booking,
  isLoading: (state) => state.loading,
  errorMessage: (state) => state.error,
};

const actions = {
  async calculatePrice({ commit }, { bikeId, startDate, endDate }) {
    commit('setLoading', true);
    try {
      const response = await axios.get('/calculate/price', {
        params: { bikeId, startDate, endDate },
      });
      console.log('Price calculation response:', response.data);
      return response.data; // Assuming the API returns the calculated price
    } catch (error) {
      commit('setError', error.message);
      throw error;
    } finally {
      commit('setLoading', false);
    }
  },
  async fetchBooking({ commit }, bookingId) {
    commit('setLoading', true);
    try {
      const response = await axios.get(`/bookings/${bookingId}`);
      commit('setBooking', response.data);
    } catch (error) {
      commit('setError', error.message);
    } finally {
      commit('setLoading', false);
    }
  },
  async createBooking({ commit }, bookingData) {
    commit('setLoading', true);
    try {
      const response = await axios.post('/bookings', bookingData);
      commit('addBooking', response.data);
    } catch (error) {
      commit('setError', error.message);
    } finally {
      commit('setLoading', false);
    }
  },
  async updateBooking({ commit }, { bookingId, bookingData }) {
    commit('setLoading', true);
    try {
      const response = await axios.put(`/bookings/${bookingId}`, bookingData);
      commit('updateBooking', response.data);
    } catch (error) {
      commit('setError', error.message);
    } finally {
      commit('setLoading', false);
    }
  },
  async deleteBooking({ commit }, bookingId) {
    commit('setLoading', true);
    try {
      await axios.delete(`/bookings/${bookingId}`);
      commit('removeBooking', bookingId);
    } catch (error) {
      commit('setError', error.message);
    } finally {
      commit('setLoading', false);
    }
  },
};

const mutations = {
  setBookings(state, bookings) {
    state.bookings = bookings;
  },
  setBooking(state, booking) {
    state.booking = booking;
  },
  addBooking(state, booking) {
    state.bookings.push(booking);
  },
  updateBooking(state, updatedBooking) {
    const index = state.bookings.findIndex(b => b.id === updatedBooking.id);
    if (index !== -1) {
      state.bookings.splice(index, 1, updatedBooking);
    }
  },
  removeBooking(state, bookingId) {
    state.bookings = state.bookings.filter(b => b.id !== bookingId);
  },
  setLoading(state, isLoading) {
    state.loading = isLoading;
  },
  setError(state, error) {
    state.error = error;
  },
};

export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations,
};
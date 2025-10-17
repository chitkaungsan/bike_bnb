import axios from "../../service/axios";

const state = {
    bookings: [],
    booking: null,
    loading: false,
    error: null,
    my_bookings: [],
};

const getters = {
    allBookings: (state) => state.bookings,
    singleBooking: (state) => state.booking,
    isLoading: (state) => state.loading,
    errorMessage: (state) => state.error,
    myBookings: (state) => state.my_bookings,
};

const actions = {
    async calculatePrice({ commit }, { bikeId, startDate, endDate }) {
        commit("setLoading", true);
        try {
            const response = await axios.get("/calculate/price", {
                params: { bikeId, startDate, endDate },
            });
            return response.data; // Assuming the API returns the calculated price
        } catch (error) {
            commit("setError", error.message);
            throw error;
        } finally {
            commit("setLoading", false);
        }
    },
    async fetchBooking({ commit }, bookingId) {
        commit("setLoading", true);
        try {
            const response = await axios.get(`/bookings/${bookingId}`);
            commit("setBooking", response.data);
        } catch (error) {
            commit("setError", error.message);
        } finally {
            commit("setLoading", false);
        }
    },
    async createBooking({ commit }, bookingData) {
        commit("setLoading", true);
        try {
            const response = await axios.post("/booking/create", bookingData);
            commit("addBooking", response.data);
            return response.data;
        } catch (error) {
            const message = error.response?.data?.error || error.message;
            commit("setError", message);
            throw new Error(message);
            commit("setError", message);
        } finally {
            commit("setLoading", false);
        }
    },
    async fetchBookingById({ commit }, bookingId) {
        commit("setLoading", true);
        try {
            const response = await axios.get(`/bookings/${bookingId}`);
            commit("setBooking", response.data);
            return response; // Return the full response for further handling
        } catch (error) {
            const message = error.response?.data?.error || error.message;
            commit("setError", message);
            throw new Error(message);
        } finally {
            commit("setLoading", false);
        }
    },
    async updateBooking({ commit }, { bookingId, bookingData }) {
        commit("setLoading", true);
        try {
            const response = await axios.put(
                `/bookings/${bookingId}`,
                bookingData
            );
            commit("updateBooking", response.data);
        } catch (error) {
            commit("setError", error.message);
        } finally {
            commit("setLoading", false);
        }
    },
    async deleteBooking({ commit }, bookingId) {
        commit("setLoading", true);
        try {
            await axios.delete(`/bookings/${bookingId}`);
            commit("removeBooking", bookingId);
        } catch (error) {
            commit("setError", error.message);
        } finally {
            commit("setLoading", false);
        }
    },
    async getBookDateWithBikeId({ commit }, bikeId) {
        commit("setLoading", true);
        try {
            const response = await axios.get(`/bookings/bike/${bikeId}`);
            commit("setBookings", response.data);
            return response.data;
        } catch (error) {
            commit("setError", error.message);
            throw error;
        } finally {
            commit("setLoading", false);
        }
    },
    async fetchUserBookings({ commit },user_id) {
        commit("setLoading", true);
        try {
            const response = await axios.get(`/my/bookings/${user_id}`);
            commit("setMyBookings", response.data);
            console.log('user bookings response', response);
            return response.data;
        } catch (error) {
            commit("setError", error.message);
        } finally {
            commit("setLoading", false);
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
        const index = state.bookings.findIndex(
            (b) => b.id === updatedBooking.id
        );
        if (index !== -1) {
            state.bookings.splice(index, 1, updatedBooking);
        }
    },
    removeBooking(state, bookingId) {
        state.bookings = state.bookings.filter((b) => b.id !== bookingId);
    },
    setLoading(state, isLoading) {
        state.loading = isLoading;
    },
    setError(state, error) {
        state.error = error;
    },
    setMyBookings(state, bookings) {
        state.my_bookings = bookings;
    }
};

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations,
};

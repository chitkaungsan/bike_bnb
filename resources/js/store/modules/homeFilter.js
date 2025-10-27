// store/modules/homeFilter.js
import axios from "../../service/axios";

const state = {
    bikes: [],
    loading: false,
    error: null,
    cat_id: null,
    city: null,
    start_date: null,
    end_date: null,
};

const getters = {
    filteredBikes: (state) => state.bikes,
    isLoading: (state) => state.loading,
    filterError: (state) => state.error,
    cat_id: (state) => state.cat_id,
    city: (state) => state.city,
    start_date: (state) => state.start_date,
    end_date: (state) => state.end_date,
};

const actions = {
    async fetchFilterBikes({ commit }, params) {
        commit("setLoading", true);
        commit("setError", null);

        const formattedParams = {
            location_id: params.location_id,
            category_id: params.category_id,
            start_date: params.start_date
                ? new Date(params.start_date).toISOString().split("T")[0]
                : null,
            end_date: params.end_date
                ? new Date(params.end_date).toISOString().split("T")[0]
                : null,
        };

        //  Commit each piece of filter state
        commit("setCatId", params.category_id);
        commit("setSelectedCity", params.location_id);
        commit("setSelectedDate", {
            start_date: formattedParams.start_date,
            end_date: formattedParams.end_date,
        });

        try {
            const response = await axios.get("/filter/bikes", {
                params: formattedParams,
            });

            commit("setFilterBikes", response.data);
            return response;
        } catch (error) {
            commit(
                "setError",
                error.response?.data?.message ||
                    "Failed to fetch filtered bikes"
            );
            console.error("fetchFilterBikes error:", error);
            throw error;
        } finally {
            commit("setLoading", false);
        }
    },

    async searchBikesFilter({ state }, router) {
        console.log(
            "cat, city, dates:",
            state.cat_id,
            state.city,
            state.start_date,
            state.end_date
        );

        const query = {};

        if (state.city) query.location_id = state.city;
        if (state.cat_id) query.category_id = state.cat_id;
        if (state.start_date) query.start_date = state.start_date;
        if (state.end_date) query.end_date = state.end_date;

        router.replace({
            name: "bikes",
            query,
        });
    },

    updateCatId({ commit }, cat_id) {
        commit("setCatId", cat_id);
    },

    setSelectedCity({ commit }, city) {
        commit("setSelectedCity", city);
    },

    updateSelectedDate({ commit }, selectedDate) {
        commit("setSelectedDate", selectedDate);
    },
};

const mutations = {
    setFilterBikes(state, bikes) {
        state.bikes = bikes;
    },
    setLoading(state, status) {
        state.loading = status;
    },
    setError(state, message) {
        state.error = message;
    },
    setCatId(state, cat_id) {
        state.cat_id = cat_id;
    },
    setSelectedCity(state, city) {
        state.city = city;
    },
    //  Expect an object with { start_date, end_date }
    setSelectedDate(state, selectedDate) {
        state.start_date = selectedDate.start_date || null;
        state.end_date = selectedDate.end_date || null;
    },
};

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations,
};

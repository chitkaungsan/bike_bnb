// store/modules/homeFilter.js
import axios from "../../service/axios";

const state = {
    bikes: [],
    loading: false,
    error: null,
    cat_id: null,
};

const getters = {
    filteredBikes: (state) => state.bikes,
    isLoading: (state) => state.loading,
    filterError: (state) => state.error,
    cat_id: (state) => state.cat_id,
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

        try {
            //  call your Laravel API (adjust endpoint if needed)
            const response = await axios.get("/filter/bikes", {
              params:  formattedParams,
            });

            commit("setFilterBikes", response.data);

            // Return data so parent (BikeListPage.vue) can use res.data
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

    async searchBikesFilter({ commit }) {
        commit("setLoading", true);
        commit("setError", null);
        let cat_id = state.cat_id;
        console.log('cat_id', cat_id);
        try {
            const response = await axios.get("/search/bikes/filter",{
                params: {
                    category_id: cat_id,
                },
            });
            commit("setFilterBikes", response.data);
        } catch (error) {
            commit(
                "setError",
                error.response?.data?.message || "Failed to fetch bikes"
            );
        } finally {
            commit("setLoading", false);
        }
    },
    updateCatId({ commit }, cat_id) {
        commit("setCatId", cat_id);
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
};

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations,
};

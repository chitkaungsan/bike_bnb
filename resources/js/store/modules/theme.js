

 const state = {
  theme: 'light', // Default theme
};

const getters = {
  currentTheme: (state) => state.theme,
};

 const mutations = {
  SET_THEME(state, theme) {
    state.theme = theme;
    // Set the theme on the root HTML element for Bootstrap to detect
    document.documentElement.setAttribute('data-bs-theme', theme);
  },
};

 const actions = {
  toggleTheme({ commit, state }) {
    const newTheme = state.theme === 'light' ? 'dark' : 'light';
    localStorage.setItem('theme', newTheme); // Save preference to localStorage
    commit('SET_THEME', newTheme);
  },

  initTheme({ commit }) {
    const cachedTheme = localStorage.getItem('theme');
    if (cachedTheme) {
      // Use the theme from localStorage if it exists
      commit('SET_THEME', cachedTheme);
    } else {
      // Otherwise, check the user's OS preference
      const userPrefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
      commit('SET_THEME', userPrefersDark ? 'dark' : 'light');
    }
  },
};

export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations,
}

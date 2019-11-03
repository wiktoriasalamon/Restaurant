const state = {
  token: localStorage.getItem('user-token') || '',
  status: '',
};

const getters = {
  isAuthenticated: state => !!state.token,
  authStatus: state => state.status,
};

const actions = {
   login({commit}, credentials) {
     return new Promise((resolve, reject) => {
        commit('authRequest');
        axios(route('api.login'), credentials).then((response) => {
          const token = resp.data.token;
          localStorage.setItem('user-token', token);
          axios.defaults.headers.common['Authorization'] = 'Bearer ' + token;
          commit('authSuccess', token);
          resolve(response);
        }).catch((error) => {
          commit('authError');
          localStorage.removeItem('user-token');
          reject(error);
        });
     });
   },
   logout({commit}) {
     return new Promise((resolve, reject) => {
       commit('authLogout');
       localStorage.removeItem('user-token');
       delete axios.defaults.headers.common['Authorization'];
       resolve();
     })
   }
};

const mutations = {
  authRequest(state) {
    state.status = 'loading';
  },
  authSuccess(state, token) {
    state.status = 'success';
    state.token = token
  },
  authError(state) {
    state.status = 'error';
  },
  authLogout(state) {
    state.status = 'logout';
  }
};

export default {
  state, getters, mutations, actions
};
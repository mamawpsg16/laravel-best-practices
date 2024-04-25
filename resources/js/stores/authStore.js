import { defineStore } from 'pinia';

// Declare your store
export const useAuthStore = defineStore('auth', {
  state: () => ({
    user:null,
    isAuthenticated:false
  }),

  getters: {
    userName(state) {
      return state.user ? state.user.name : '';
    }
  },

  actions: {
    async register(name, email, password, passwordConfirmation){
        return await axios.post('/register', {
          name: name,
          email: email,
          password: password,
          password_confirmation : passwordConfirmation
        });
    },

    async login(email, password, remembered){
        return await axios.post('/login', {
          email: email,
          password: password,
          remembered:remembered
        });
    },

    async logout(){
      try {
          const response = await axios.post('/logout');
          localStorage.removeItem('authenticated');
        } catch (error) {
          console.error(error,'ERROR');
        }
    },

    async getUser() {
        await axios.get('/api/user').then(response => {
          const { user } = response.data;
          if(user){
            localStorage.setItem('authenticated',true)
            this.setAuthenticated(true);
            this.setUser(user);
          }
        })
        .catch(error => {
          console.error('Error fetching user:', error);
        });
    },

    setAuthenticated(isAuthenticated) {
      this.isAuthenticated = isAuthenticated
    },

    setUser(user) {
      this.user = user
    },
  },
});

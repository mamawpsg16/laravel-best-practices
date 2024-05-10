import { defineStore } from 'pinia';
import axios from 'axios';
// Declare your store
export const useAuthStore = defineStore('auth', {
  state: () => ({
    user:null,
    isAuthenticated:false
  }),

  getters: {
    userName(state) {
      return state.user ? state.user.name : '';
    },
    isUserAuthenticated(state){
      return state.isAuthenticated;
    },
    isUserVerified(state){
      return state.user?.isVerified;
    },
    isSocialAuthenticated(state){
      return state.user?.provider;
    },
  },

  actions: {
    async resetPassword(email){
      await axios.get("sanctum/csrf-cookie");
      return await axios.post('/forgot-password', {
        email: email,
      });
    },
    async resetPasswordConfirmation(email, password, passwordConfirmation, token){
        await axios.get("sanctum/csrf-cookie");
        return await axios.post('/reset-password/confirmation', {
          email: email,
          password: password,
          password_confirmation : passwordConfirmation,
          token: token,
        });
    },

    async register(name, email, password, passwordConfirmation){
        await axios.get("sanctum/csrf-cookie");
        return await axios.post('/register', {
          name: name,
          email: email,
          password: password,
          password_confirmation : passwordConfirmation
        });
    },

    async login(email, password, remembered){
        await axios.get("sanctum/csrf-cookie");

        return await axios.post('/login', {
          email: email,
          password: password,
          remembered:remembered
        });
    },
    

    async logout(){
      try {
          await axios.post('/api/logout').then((response =>{
            if(response.status == 200) {
              localStorage.removeItem('authenticated');
              this.setAuthenticated(false);
              this.setUser(null);
              window.location.href = '/login'
            }
          }));
        } catch (error) {
          console.error(error,'ERROR');
        }
    },

    async changePassword(password, new_password, new_password_confirmation){
      try {
          const response = await axios.post('api/user/change-password', {
            password: password,
            new_password: new_password,
            new_password_confirmation:new_password_confirmation
          });
          return response;
        } catch (error) {
          return error;
        }
    },

    async getUser() {
        await axios.get('/api/user').then(response => {
          if(response?.data && response?.data?.user ){
            localStorage.setItem('authenticated',true)
            this.setAuthenticated(true);
            this.setUser(response.data.user);
            return;
          }
          localStorage.removeItem('authenticated')
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

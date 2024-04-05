import { defineStore } from 'pinia';

// Declare your store
export const useAuthStore = defineStore('auth', {
  /** DATA */
  state: () => ({
    // Define your state properties here
    isAuthenticated:false
  }),
  actions: {
    setAuthenticated(value) {
      this.isAuthenticated = value
    },
    async logout(){
      try {
          const response = await axios.post('/logout');
          localStorage.removeItem('authenticated');
        } catch (error) {
          console.error(error,'ERROR');
        }
    },
  },
});

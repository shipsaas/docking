export const authStorage = {
  get() {
    return sessionStorage.getItem('DOCKING_CONSOLE_KEY');
  },

  set(key) {
    return sessionStorage.setItem('DOCKING_CONSOLE_KEY', key);
  },

  isLoggedIn() {
    return !!authStorage.get();
  },
};

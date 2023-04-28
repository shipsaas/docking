import { ref } from 'vue';
import { authStorage } from '../utils/authStorage';
import { accessRepository } from '../repositories/access.repository';

export const useAuthentication = () => {
  const isLoggedIn = ref(authStorage.isLoggedIn());

  const onLogin = async (password) => {
    const passwordCheckResult = await accessRepository.access(password);

    if (passwordCheckResult) {
      authStorage.set(password);
      isLoggedIn.value = true;
    }

    return passwordCheckResult;
  };

  const onLogOut = () => {
    authStorage.set(null);
    isLoggedIn.value = false;
  };

  return {
    isLoggedIn,
    onLogin,
    onLogOut,
  };
};

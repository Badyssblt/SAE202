export const useAuth = defineStore(
  "auth",
  () => {
    const user = ref(null);
    const token = ref("");

    const isAuthenticated = computed(() => user.value !== null);

    const authenticate = (newUser) => {
      user.value = newUser;
    };

    const logout = () => {
      user.value = null;
      token.value = null;
    };

    return {
      user,
      authenticate,
      token,
      isAuthenticated,
      logout,
    };
  },
  {
    persist: {
      storage: persistedState.cookiesWithOptions({
        sameSite: "strict",
      }),
    },
  }
);

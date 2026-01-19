export default defineNuxtRouteMiddleware((to, from) => {
    // Only run on client side
    if (import.meta.server) return;

    const authToken = useCookie('auth_token');
    const userCookie = useCookie('user');

    console.log('AuthMiddleware execution path:', to.path);
    console.log('Token present:', !!authToken.value);

    // If no token, redirect to index (login)
    if (!authToken.value && to.path !== '/') {
        console.log('No token, redirecting to login');
        return navigateTo('/');
    }

    // If has token and on index, redirect based on role
    if (authToken.value && to.path === '/') {
        try {
            const user = userCookie.value as any;
            if (user.role === 'admin') {
                return navigateTo('/admin/dashboard');
            } else {
                return navigateTo('/entry');
            }
        } catch (e) {
            // Invalid user data, clear and stay on index
            authToken.value = null;
            userCookie.value = null;
        }
    }

    // Protect admin routes
    if (to.path.startsWith('/admin')) {
        try {
            const user = userCookie.value as any;
            if (user.role !== 'admin') {
                // Salesman trying to access admin area - redirect to entry
                return navigateTo('/entry');
            }
        } catch (e) {
            return navigateTo('/');
        }
    }

    // Protect entry route (salesmen only)
    if (to.path === '/entry') {
        if (!authToken.value) {
            return navigateTo('/');
        }
    }
});

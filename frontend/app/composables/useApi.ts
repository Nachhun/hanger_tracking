export const useApi = () => {
    const config = useRuntimeConfig();
    const baseURL = config.public.apiBase || '/api';

    const getAuthHeaders = () => {
        const token = useCookie('auth_token');
        return token.value ? {
            'Authorization': `Bearer ${token.value}`,
            'Accept': 'application/json',
        } : {
            'Accept': 'application/json',
        };
    };

    const apiCall = async (endpoint: string, options: any = {}) => {
        try {
            return await $fetch(`${baseURL}${endpoint}`, {
                ...options,
                headers: {
                    ...getAuthHeaders(),
                    ...options.headers,
                },
            });
        } catch (error: any) {
            console.error('API Error:', error);

            // Handle 401 unauthorized - redirect to login
            if (error?.status === 401 || error?.statusCode === 401) {
                const authToken = useCookie('auth_token');
                const user = useCookie('user');
                authToken.value = null;
                user.value = null;

                if (import.meta.client) {
                    window.location.href = '/';
                }
            }

            throw error;
        }
    };

    return {
        apiCall,
        baseURL,
    };
};

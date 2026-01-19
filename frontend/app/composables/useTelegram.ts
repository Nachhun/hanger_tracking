import { ref, readonly } from 'vue';

/**
 * Composable for Telegram Mini App integration
 * Provides access to Telegram WebApp API
 */
export const useTelegram = () => {
    const webApp = ref<any>(null);
    const initData = ref<string>('');
    const user = ref<any>(null);

    if (import.meta.client) {
        // Access Telegram WebApp API (only available in browser)
        webApp.value = (window as any).Telegram?.WebApp;

        if (webApp.value) {
            initData.value = webApp.value.initData || '';
            console.log('Telegram initData:', initData.value);
            user.value = webApp.value.initDataUnsafe?.user || null;
        }
    }

    /**
     * Signal that the Mini App is ready to be displayed
     */
    const ready = () => {
        if (webApp.value) {
            webApp.value.ready();
        }
    };

    /**
     * Expand the Mini App to full height
     */
    const expand = () => {
        if (webApp.value) {
            webApp.value.expand();
        }
    };

    /**
     * Close the Mini App
     */
    const close = () => {
        if (webApp.value) {
            webApp.value.close();
        }
    };

    /**
     * Show the main button
     */
    const showMainButton = (text: string, onClick: () => void) => {
        if (webApp.value?.MainButton) {
            webApp.value.MainButton.text = text;
            webApp.value.MainButton.onClick(onClick);
            webApp.value.MainButton.show();
        }
    };

    /**
     * Hide the main button
     */
    const hideMainButton = () => {
        if (webApp.value?.MainButton) {
            webApp.value.MainButton.hide();
        }
    };

    return {
        webApp: readonly(webApp),
        initData: readonly(initData),
        user: readonly(user),
        ready,
        expand,
        close,
        showMainButton,
        hideMainButton,
    };
};

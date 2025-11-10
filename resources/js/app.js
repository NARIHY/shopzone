    import Echo from 'laravel-echo';
    import Pusher from 'pusher-js';

    window.Pusher = Pusher;

    window.Echo = new Echo({
        broadcaster: 'reverb',
        key: import.meta.env.VITE_REVERB_APP_KEY,
        wsHost: import.meta.env.VITE_REVERB_HOST,
        wsPort: import.meta.env.VITE_REVERB_PORT,
        wssPort: import.meta.env.VITE_REVERB_PORT,
        forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? 'https') === 'https',
        enabledTransports: ['ws', 'wss'],
    });
    
(function initNotifications() {
    // Fonction pour jouer un BIP court (800 Hz, 100ms, volume doux)
    const playBeep = () => {
        try {
            const AudioContext = window.AudioContext || window['webkitAudioContext'];
            const ctx = new AudioContext();

            if (ctx.state === 'suspended') {
                ctx.resume().catch(() => console.warn('AudioContext reste suspendu'));
            }

            const oscillator = ctx.createOscillator();
            const gainNode = ctx.createGain();

            oscillator.connect(gainNode);
            gainNode.connect(ctx.destination);

            oscillator.frequency.value = 800;
            oscillator.type = 'sine';
            gainNode.gain.setValueAtTime(0.3, ctx.currentTime);
            gainNode.gain.exponentialRampToValueAtTime(0.01, ctx.currentTime + 0.1);

            oscillator.start(ctx.currentTime);
            oscillator.stop(ctx.currentTime + 0.1);
        } catch (e) {
            console.warn('Échec du bip sonore (AudioContext non disponible)', e);
        }
    };

    if (!window.Echo) {
        console.error("Laravel Echo n'est pas chargé.");
        return; // valid here (inside function)
    }

    window.Echo.channel('notifications')
        .listen('.NotificationSent', (event) => {
            console.log('Notification reçue:', event);

            if (window.livewire) {
                window.livewire.emit('notify', event.type, event.message);
            } else if (typeof Livewire !== 'undefined') {
                Livewire.dispatch('notify', [event.type, event.message]);
            }

            playBeep();
        });

    document.addEventListener('play-beep', playBeep);
})();

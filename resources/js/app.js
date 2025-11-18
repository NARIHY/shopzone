import './echo';
import notificationSound from './sound/notification.mp3';

// Précharge silencieusement le son pour éviter le blocage sur mobile
const preloadNotificationSound = () => {
    const audio = new Audio(notificationSound);
    audio.volume = 0; // silencieux
    audio.play().catch(() => {
        // Normal sur mobile tant qu'il n'y a pas d'interaction utilisateur
    });
};

// Lancer le preload dès le départ
preloadNotificationSound();

(function initNotifications() {
    // Fonction pour jouer un BIP court (800 Hz, 100ms, volume doux)
    const playBeep = () => {
        const AudioContext = window.AudioContext || window.webkitAudioContext;
        const ctx = new AudioContext();

        if (ctx.state === 'suspended') {
            ctx.resume().catch(() => console.warn('AudioContext reste suspendu'));
        }

        const now = ctx.currentTime;

        // Nodes
        const masterGain = ctx.createGain();
        const filter = ctx.createBiquadFilter();
        const oscMain = ctx.createOscillator();
        const oscHarm = ctx.createOscillator();
        const harmGain = ctx.createGain();

        // Chaînage
        oscMain.connect(harmGain);
        oscHarm.connect(harmGain);
        harmGain.connect(filter);
        filter.connect(masterGain);
        masterGain.connect(ctx.destination);

        // Paramètres
        const freq = 800;
        const volume = 0.3;
        const duration = 0.1;

        oscMain.type = 'sine';
        oscMain.frequency.setValueAtTime(freq, now);

        oscHarm.type = 'triangle';
        oscHarm.frequency.setValueAtTime(freq * 2.01, now);
        harmGain.gain.setValueAtTime(0.18 * volume, now);

        filter.type = 'lowpass';
        filter.frequency.setValueAtTime(2000, now);
        filter.Q.setValueAtTime(0.8, now);

        // Enveloppe ADSR simplifiée
        const attack = 0.01;
        const decay = 0.03;
        const sustainLevel = 0.6;
        const release = 0.04;
        const sustainTime = Math.max(0, duration - (attack + decay + release));

        masterGain.gain.setValueAtTime(0.0001, now);
        masterGain.gain.linearRampToValueAtTime(volume, now + attack);
        masterGain.gain.linearRampToValueAtTime(volume * sustainLevel, now + attack + decay);
        masterGain.gain.setValueAtTime(volume * sustainLevel, now + attack + decay + sustainTime);
        masterGain.gain.linearRampToValueAtTime(0.0001, now + attack + decay + sustainTime + release);

        const stopTime = now + duration + 0.05;
        oscMain.start(now);
        oscHarm.start(now);
        oscMain.stop(stopTime);
        oscHarm.stop(stopTime);
    };

    // Fonction principale : joue le MP3, fallback sur beep si besoin
    const playNotificationSound = () => {
        const audio = new Audio(notificationSound);
        audio.volume = 0.4; // ajuste selon tes goûts

        const playPromise = audio.play();

        if (playPromise !== undefined) {
            playPromise
                .then(() => {
                    console.log('Notification MP3 jouée');
                })
                .catch((err) => {
                    console.warn('MP3 bloqué ou erreur → fallback beep synthétique', err);
                    playBeep();
                });
        }
    };

    if (!window.Echo) {
        console.error("Laravel Echo n'est pas chargé.");
        return;
    }

    window.Echo.channel('notifications')
        .listen('.NotificationSent', (event) => {
            console.log('Notification reçue:', event);
            playNotificationSound(); // joue le son préchargé ou fallback beep
        });

    document.addEventListener('play-beep', playBeep);
})();

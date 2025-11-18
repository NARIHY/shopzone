
/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allow your team to quickly build robust real-time web applications.
 */

import './echo';


(function initNotifications() {
    // Fonction pour jouer un BIP court (800 Hz, 100ms, volume doux)
    const playBeep = () => {
    const AudioContext = window.AudioContext || window['webkitAudioContext'];
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
    const freq = 800; // fréquence fondamentale
    const volume = 0.3; // volume
    const duration = 0.1; // durée totale

    oscMain.type = 'sine';
    oscMain.frequency.setValueAtTime(freq, now);

    oscHarm.type = 'triangle';
    oscHarm.frequency.setValueAtTime(freq * 2.01, now);
    harmGain.gain.setValueAtTime(0.18 * volume, now);

    filter.type = 'lowpass';
    filter.frequency.setValueAtTime(2000, now);
    filter.Q.setValueAtTime(0.8, now);

    // Enveloppe ADSR
    const attack = 0.01;
    const decay = 0.03;
    const sustainLevel = 0.6;
    const release = 0.04;
    const sustainTime = Math.max(0, duration - (attack + decay + release));

    masterGain.gain.setValueAtTime(0.0001, now);
    masterGain.gain.linearRampToValueAtTime(volume, now + attack);
    masterGain.gain.linearRampToValueAtTime(volume * sustainLevel, now + attack + decay);
    masterGain.gain.setValueAtTime(volume * sustainLevel, now + attack + decay);
    masterGain.gain.linearRampToValueAtTime(0.0001, now + attack + decay + sustainTime + release);

    // Démarrage / arrêt
    const stopTime = now + attack + decay + sustainTime + release + 0.02;
    oscMain.start(now);
    oscHarm.start(now);
    oscMain.stop(stopTime);
    oscHarm.stop(stopTime);

    // Nettoyage
    setTimeout(() => {
        oscMain.disconnect();
        oscHarm.disconnect();
        harmGain.disconnect();
        filter.disconnect();
        masterGain.disconnect();
    }, (duration + 0.1) * 1000);
};


    if (!window.Echo) {
        console.error("Laravel Echo n'est pas chargé.");
        return; // valid here (inside function)
    }

    window.Echo.channel('notifications')
        .listen('.NotificationSent', (event) => {
            console.log('Notification reçue:', event);

            playBeep();
        });

    document.addEventListener('play-beep', playBeep);
})();

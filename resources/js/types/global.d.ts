import Pusher from 'pusher-js';
import type { route as routeFn } from 'ziggy-js';

declare global {
    const route: typeof routeFn;
    interface Window {
        // Informa que agora existe uma propriedade 'Pusher' no window,
        // e que o seu tipo é o mesmo da classe Pusher que importámos.
        Pusher: typeof Pusher;
    }
}

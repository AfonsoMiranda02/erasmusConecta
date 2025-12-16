import Echo from 'laravel-echo';
import io from 'socket.io-client';

// Make socket.io available globally (for compatibility with CDN version)
window.io = io;

// Initialize LaravelEcho with Socket.IO
// Only initialize if not already initialized (e.g., by CDN script)
if (!window.Echo || !window.Echo.connector) {
    const echoHost = (window.location.protocol === 'https:' ? 'https' : 'http') + '://' + window.location.hostname + ':6001';

window.Echo = new Echo({
        broadcaster: 'socket.io',
        client: io,
        host: echoHost,
        transports: ['websocket', 'polling'],
        auth: {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.content || ''
            }
        }
    });
}

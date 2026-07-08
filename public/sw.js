// Service worker minimal : permet l'installation du site en app (PWA) et un
// repli hors-ligne propre. Ne cache jamais les pages dynamiques (panier,
// favoris, commande) — uniquement les pages déjà visitées + les assets statiques,
// pour ne jamais montrer de données périmées (stock, prix) par erreur.
const CACHE_NAME = 'blac-joyaux-v1';
const OFFLINE_URL = '/offline.html';
const PRECACHE_ASSETS = [
    '/css/app.css',
    '/images/icon-192.png',
    '/images/icon-512.png',
    OFFLINE_URL,
];

self.addEventListener('install', function (event) {
    event.waitUntil(
        caches.open(CACHE_NAME).then(function (cache) {
            return cache.addAll(PRECACHE_ASSETS);
        })
    );
    self.skipWaiting();
});

self.addEventListener('activate', function (event) {
    event.waitUntil(
        caches.keys().then(function (keys) {
            return Promise.all(
                keys.filter(function (key) { return key !== CACHE_NAME; })
                    .map(function (key) { return caches.delete(key); })
            );
        })
    );
    self.clients.claim();
});

self.addEventListener('fetch', function (event) {
    const request = event.request;
    if (request.method !== 'GET') return;

    // Pages (navigation) : toujours le réseau en priorité pour des données fraîches,
    // repli sur la page hors-ligne uniquement si vraiment injoignable.
    if (request.mode === 'navigate') {
        event.respondWith(
            fetch(request).catch(function () {
                return caches.match(OFFLINE_URL);
            })
        );
        return;
    }

    // Assets statiques (styles, images, polices) : cache d'abord pour la vitesse,
    // réseau en repli, mise en cache silencieuse des nouvelles réponses.
    if (['style', 'image', 'font'].indexOf(request.destination) !== -1) {
        event.respondWith(
            caches.match(request).then(function (cached) {
                const network = fetch(request).then(function (response) {
                    if (response && response.ok) {
                        const copy = response.clone();
                        caches.open(CACHE_NAME).then(function (cache) { cache.put(request, copy); });
                    }
                    return response;
                }).catch(function () { return cached; });

                return cached || network;
            })
        );
    }
});

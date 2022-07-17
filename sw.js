const cacheName = 'storage';
const cacheVersion = 'v1';
const urlsToCache = [
	'client.js',
	'client.min.js',
	'elfinder/roundcube.html',
	'elfinder/elopen.html',
	'elfinder/main.js',
	'elfinder/js/elfinder.min.js',
	'elfinder/js/require.min.js',
	'elfinder/js/jquery.min.js',
	'elfinder/js/jquery-ui.min.js',
	'elfinder/js/ace/ace.js',
	'elfinder/js/extras/editors.local.min.js',
	'elfinder/js/i18n/elfinder.de.js',
	'elfinder/js/i18n/elfinder.LANG.js',
	'elfinder/js/i18n/elfinder.fallback.js',
	'elfinder/css/theme.css',
	'elfinder/css/elfinder.min.css',
	'elfinder/css/jquery-ui.css',
	'elfinder/css/images/ui-icons_222222_256x240.png',
	'elfinder/css/images/ui-bg_glass_75_e6e6e6_1x400.png',
	'elfinder/css/images/ui-bg_glass_75_dadada_1x400.png',
	'elfinder/css/images/ui-bg_highlight-soft_75_cccccc_1x100.png',
	'elfinder/css/images/ui-bg_glass_65_ffffff_1x400.png',
	'elfinder/img/toolbar.png',
	'elfinder/img/icons-big.svg',
	'elfinder/img/logo.png',
	'elfinder/img/volume_icon_local.svg',
	'elfinder/img/arrows-normal.png',
	'elfinder/img/arrows-active.png',
	'elfinder/img/resize.png',
];

self.addEventListener('install', function(event) {
	event.waitUntil(
		caches.open(cacheName+cacheVersion)
			.then(function(cache) {
			return cache.addAll(urlsToCache);
		})
	);
	self.skipWaiting();
});

self.addEventListener('activate', event => {
	event.waitUntil(caches.keys().then(keyList => {
		return Promise.all(keyList.map(key => {
			if(key.indexOf(cacheName) !== -1) {
				if(key.indexOf(cacheVersion) === -1) {
					return caches.delete(key);
				}
			}
        }));
    }).then(self.clients.claim()));
});

seajs.config({
    // Enable plugins
    plugins: ['shim'],

    // Configure alias
    alias: {
        'jquery': {
            src: 'lib/jquery-1.9.1.min.js',
            exports: 'jQuery'
        },
        'jquery.wookmark': {
            src: 'lib/jquery.wookmark.js',
            deps: ['jquery']
        },
        'jquery.slider': {
            src: 'lib/jquery.jscarousal.js',
            deps: ['jquery']
        },
        'jquery.imagesloaded': {
            src: 'lib/jquery.imagesloaded.js',
            deps: ['jquery']
        },
        'jquery.tabs': {
            src: 'lib/jquery.tabs.js',
            deps: ['jquery']
        },
        'jquery.md5': {
            src: 'lib/jquery.md5.js',
            deps: ['jquery']
        },

        'SWFUpload': {
            src: 'lib/swfupload.js',
            exports: 'SWFUpload'
        },

        'SWFUpload.hanlder': {
            src: 'app/swfupload.handlers.js'
        }
    }
});


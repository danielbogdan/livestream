requirejs.config({
    paths: {
        'text': '../lib/require/text',
        'durandal':'../lib/durandal/js',
        'plugins' : '../lib/durandal/js/plugins',
        'transitions' : '../lib/durandal/js/transitions',
        'knockout': '../lib/knockout/knockout-3.2.0',
        'komapping': "../lib/knockout/knockout.mapping-latest",
        'kodrag': "../lib/knockout/knockout.dragdrop",
        'kodirty': "../lib/knockout/knockout.dirty-flag",
        'bootstrap': '../lib/bootstrap/js/bootstrap',
        'jquery': '../lib/jquery/jquery-1.9.1',
        'plupload': '../lib/plupload/plupload.full.min',
        'pluploadQueue': '../lib/plupload/jquery.plupload.queue/jquery.plupload.queue.min'
    },
    shim: {
        'bootstrap': {
            deps: ['jquery'],
            exports: 'jQuery'
       },
       'komapping': {
            deps: ['knockout'],
            exports: 'komapping'
        },
       'plupload': {
           deps: ['jquery'],
            exports: "plupload"
        },
        'pluploadQueue': ['plupload']
    },

    // To help prevent JS caching while we're developing.
    urlArgs: ("v=" + (new Date()).getTime())
});

define(['durandal/system', 'durandal/app', 'durandal/viewLocator', 'services/utils'],  function (system, app, viewLocator, utils) {
    //>>excludeStart("build", true);
    system.debug(true);
    //>>excludeEnd("build");

    app.title = 'BLC broadcast manager';

    app.configurePlugins({
        router: true,
        dialog: true
    });

    utils.init().then(function() {
      app.start().then(function() {
          //Replace 'viewmodels' in the moduleId with 'views' to locate the view.
          //Look for partial views in a 'views' folder in the root.
          viewLocator.useConvention();

          //Show the app by setting the root view model for our application with a transition.
          app.setRoot('viewmodels/shell', 'entrance');
      });
    })
});


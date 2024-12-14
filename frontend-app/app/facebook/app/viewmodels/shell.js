define(['plugins/router', 'durandal/app', 'services/utils'], function (router, app, utils) {
  return {
    router: router,
    activate: function () {
      var menu = [];
      if (utils.settings.connector) {
        menu.push({ route: 'connector', title: 'Connector', moduleId: 'viewmodels/connector', nav: true });
      }
      if (utils.settings.broadcaster) {
        menu.push({ route: 'playlist', title: 'Channel & Playlist', moduleId: 'viewmodels/playlist', nav: true });
        menu.push({ route: 'videos', title: 'Video library', moduleId: 'viewmodels/videos', nav: true });
      }
      if (utils.settings.previewer) {
        menu.push({ route: 'preview', title: 'Channel preview', moduleId: 'viewmodels/preview', nav: true });
      }
      if (utils.settings.archive) {
        menu.push({ route: 'archive', title: 'Archive', moduleId: 'viewmodels/archive', nav: true });
      }

      router.map(menu).buildNavigationModel().mapUnknownRoutes( menu[0].moduleId, menu[0].route );

      return router.activate();
    }
  };
});


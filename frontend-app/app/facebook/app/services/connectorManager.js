define(['knockout', 'komapping', 'jquery', 'services/utils', 'kodirty'], function (ko, komapping, $, utils, kodirty)
{
  ko.mapping = komapping;

  var manager = {};

  var internalStatus = ko.observable({ isStreaming: ko.observable(false) });
  var isTransitioningState = ko.observable(false);
  var internalYoutubeStatus = ko.observable({ isStreaming: ko.observable(false) });
  var isYoutubeTransitioningState = ko.observable(false);

  manager.facebookMode = ko.observable(utils.modes.AUTO);
  manager.facebookSettings = ko.observable({});
  manager.facebookSettingsDirtyFlag = new kodirty.DirtyFlag(manager.facebookSettings)();
  manager.youtubeMode = ko.observable(utils.modes.MANUAL);
  manager.youtubeSettings = ko.observable({});
  manager.youtubeSettingsDirtyFlag = new kodirty.DirtyFlag(manager.youtubeSettings)();

  manager.startFacebookConnector = function (url)
  {
    isTransitioningState(true);
    $.ajax({
      type: "POST",
      url: "api/start-connector-facebook",
      data: manager.facebookSettings().settings.mode() == utils.modes.MANUAL ? url.facebookURL() : "",
      cache: false,
      dataType: "json",
      async: true,
      contentType: "application/x-www-form-urlencoded"
    });
  }
  manager.stopFacebookConnector = function ()
  {
    isTransitioningState(true);
    $.ajax({
      type: "PUT",
      url: "api/stop-connector-facebook",
      cache: false,
      async: true
    });
  }
  manager.startYoutubeConnector = function ()
  {
    isYoutubeTransitioningState(true);
    $.ajax({
      type: "PUT",
      url: "api/start-connector-youtube",
      cache: false,
      async: true
    });
  }
  manager.stopYoutubeConnector = function ()
  {
    isYoutubeTransitioningState(true);
    $.ajax({
      type: "PUT",
      url: "api/stop-connector-youtube",
      cache: false,
      async: true
    });
  }

  manager.facebookLogin = function(userCredentials) {
    return $.ajax({
      type: "POST",
      url: "api/facebook-login",
      data: JSON.stringify(userCredentials),
      cache: false,
      dataType: "json",
      async: true,
      contentType: "application/x-www-form-urlencoded"
    });
  }
  manager.facebookSaveSettings = function() {
    $.ajax({
      type: "POST",
      url: "api/facebook-settings",
      data: ko.toJSON(manager.facebookSettings),
      cache: false,
      async: true,
      contentType: "application/x-www-form-urlencoded"
    }).done(function() {
      manager.facebookSettingsDirtyFlag.reset();
    });
  }
  manager.youtubeSaveSettings = function() {
    $.ajax({
      type: "POST",
      url: "api/youtube-settings",
      data: ko.toJSON(manager.youtubeSettings),
      cache: false,
      async: true,
      contentType: "application/x-www-form-urlencoded"
    }).done(function() {
      manager.youtubeSettingsDirtyFlag.reset();
    });
  }
  manager.setFacebookPage = function(page) {
    // TODO
    var temp = ko.observable({});
    ko.mapping.fromJS(page, {}, temp);
    manager.facebookSettings().page.access_token(temp().access_token());
    manager.facebookSettings().page.category(temp().category());
    manager.facebookSettings().page.id(temp().id());
    manager.facebookSettings().page.name(temp().name());
    //manager.facebookSettings().page.tasks(temp().tasks());
  }

  manager.isStartAvailable = ko.computed(function ()
  {
    return !isTransitioningState() && !internalStatus().isStreaming();
  });
  manager.isStopAvailable = ko.computed(function ()
  {
    return !isTransitioningState() && internalStatus().isStreaming();
  });
  manager.status = ko.computed(function ()
  {
    if (isTransitioningState()) return utils.states.WAITING;
    return internalStatus().isStreaming() ? utils.states.RUNNING : utils.states.STOPPED;
  });
  manager.isYoutubeStartAvailable = ko.computed(function ()
  {
    return !isYoutubeTransitioningState() && !internalYoutubeStatus().isStreaming();
  });
  manager.isYoutubeStopAvailable = ko.computed(function ()
  {
    return !isYoutubeTransitioningState() && internalYoutubeStatus().isStreaming();
  });
  manager.youtubeStatus = ko.computed(function ()
  {
    if (isYoutubeTransitioningState()) return utils.states.WAITING;
    return internalYoutubeStatus().isStreaming() ? utils.states.RUNNING : utils.states.STOPPED;
  });

  function refreshStatus()
  {
    $.ajax({
      url: "api/connector-status-facebook",
      cache: false,
      async: true
    }).done(function (data)
    {
      var currentStatus = internalStatus().isStreaming();
      ko.mapping.fromJS(data, {}, internalStatus);
      if (isTransitioningState() && currentStatus == internalStatus().isStreaming()) {
        internalStatus().isStreaming(undefined);
      } else {
        isTransitioningState(false);
      }
    });
  }

  function getConnectorSettings()
  {
    $.ajax({
      url: "api/facebook-settings",
      cache: false,
      async: false
    }).done(function (data)
    {
      ko.mapping.fromJS(data, {}, manager.facebookSettings);
      manager.facebookSettingsDirtyFlag.reset();
    });
  }

  function refreshYoutubeStatus()
  {
    $.ajax({
      url: "api/connector-status-youtube",
      cache: false,
      async: true
    }).done(function (data)
    {
      var currentStatus = internalYoutubeStatus().isStreaming();
      ko.mapping.fromJS(data, {}, internalYoutubeStatus);
      if (isYoutubeTransitioningState() && currentStatus == internalYoutubeStatus().isStreaming()) {
        internalYoutubeStatus().isStreaming(undefined);
      } else {
        isYoutubeTransitioningState(false);
      }
    });
  }

  function getYoutubeConnectorSettings()
  {
    $.ajax({
      url: "api/youtube-settings",
      cache: false,
      async: false
    }).done(function (data)
    {
      ko.mapping.fromJS(data, {}, manager.youtubeSettings);
      manager.youtubeSettingsDirtyFlag.reset();
    });
  }

  setInterval(refreshStatus, 8000);
  refreshStatus();
  getConnectorSettings();
  setInterval(refreshYoutubeStatus, 8000);
  refreshYoutubeStatus();
  getYoutubeConnectorSettings();

  return manager;
});

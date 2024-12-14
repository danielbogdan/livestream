define(['durandal/app', 'services/connectorManager', 'services/utils', 'knockout', 'jquery', './optionSelectorModal'], function (app, connectorManager, utils, ko, $, OptionSelectorModal)
{
  var vm = {};

  vm.facebookURL = ko.observable("");
  vm.isStartAvailable = connectorManager.isStartAvailable;
  vm.isStopAvailable = connectorManager.isStopAvailable;
  vm.startFacebookConnector = connectorManager.startFacebookConnector;
  vm.stopFacebookConnector = connectorManager.stopFacebookConnector;
  vm.facebookSettings = connectorManager.facebookSettings;
  vm.facebookSaveSettings = connectorManager.facebookSaveSettings;
  vm.facebookSettingsDirty = connectorManager.facebookSettingsDirtyFlag.isDirty;
  vm.isYoutubeStartAvailable = connectorManager.isYoutubeStartAvailable;
  vm.isYoutubeStopAvailable = connectorManager.isYoutubeStopAvailable;
  vm.startYoutubeConnector = connectorManager.startYoutubeConnector;
  vm.stopYoutubeConnector = connectorManager.stopYoutubeConnector;
  vm.youtubeSettings = connectorManager.youtubeSettings;
  vm.youtubeSaveSettings = connectorManager.youtubeSaveSettings;
  vm.youtubeSettingsDirty = connectorManager.youtubeSettingsDirtyFlag.isDirty;
  vm.modes = utils.modes;
  vm.youtubeEnabled = utils.settings.youtube_connector;

  vm.status = ko.computed(function ()
  {
    switch (connectorManager.status())
    {
      case utils.states.RUNNING: return { class: 'label-success', text: "ONLINE" };
      case utils.states.STOPPED: return { class: 'label-danger', text: "OFFLINE" };
      case utils.states.WAITING: return { class: 'label-warning', text: "..." };
    }
  });
  vm.youtubeStatus = ko.computed(function ()
  {
    switch (connectorManager.youtubeStatus())
    {
      case utils.states.RUNNING: return { class: 'label-success', text: "ONLINE" };
      case utils.states.STOPPED: return { class: 'label-danger', text: "OFFLINE" };
      case utils.states.WAITING: return { class: 'label-warning', text: "..." };
    }
  });
  vm.facebookStatus = ko.computed(function ()
  {
    return vm.facebookSettings().page && vm.facebookSettings().page.id() ?
      { linked: true, class: 'label-success', text: 'Linked to <a href="https://facebook.com/' + vm.facebookSettings().page.id() + '" target="_blank">' + vm.facebookSettings().page.name() + '</a>' } :
      { linked: false, class: 'label-danger', text: "Not linked" };
  });
  vm.isAutoPushAvailable = ko.computed(function ()
  {
    return utils.channel == utils.settings.source_channel;
  });

  vm.unlinkFacebookPage = function() {
    connectorManager.setFacebookPage({ id: 0, access_token: '', category: '', name: '', tasks: [] });
  }

  vm.attached = function() {
    window.fbAsyncInit = function() {
      FB.init({
        appId      : '1201887399870758',
        xfbml      : true,
        version    : 'v4.0'
      });

      FB.Event.subscribe('auth.authResponseChange', function(info) {
        if (info.status === 'connected') {
          var accessToken = info.authResponse.accessToken;
          var userID = info.authResponse.userID;
          connectorManager.facebookLogin(info.authResponse).then(function (pageData) {
            var pagesList = pageData.data;

            switch (pagesList.length) {
              case 0: app.showMessage("Your facebook account doesn't manage any pages. Please logout from your facebook account using the same browser and try again."); break;
              case 1: connectorManager.setFacebookPage(pagesList[0]); break;
              default:
                OptionSelectorModal.show("Select the facebook page you want to stream to:", pagesList).then(function(response) {
                  connectorManager.setFacebookPage(response);
                });
                break;
            }
          });
        } else {
        }
      });
    };

    (function(d, s, id){
      var js, fjs = d.getElementsByTagName(s)[0];
      if (d.getElementById(id)) {return;}
      js = d.createElement(s); js.id = id;
      js.src = "//connect.facebook.net/en_US/sdk.js";
      fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));
  };

  return vm;
});


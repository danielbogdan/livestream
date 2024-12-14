define(function ()
{
    var vm = {};

    vm.bytesToSize = function (bytes)
    {
        var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
        if (bytes == 0) return '0 Byte';
        var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
        return Math.round(bytes * 100 / Math.pow(1024, i), 2) / 100 + ' ' + sizes[i];
    };

    vm.bytesToEstimatedSeconds = function (bytes)
    {
        return bytes / 252926.1192718292;
    }

    vm.secondsToEstimatedTime = function (seconds)
    {
        var minutes = Math.round(seconds / 60);
        var hours = Math.floor(minutes / 60);
        minutes %= 60;

        return (hours ? hours + "h " : "") + minutes + "m";
    }

    vm.bytesToEstimatedTime = function (bytes)
    {
        return vm.secondsToEstimatedTime(vm.bytesToEstimatedSeconds(bytes));
    }

    vm.states =
    {
        STOPPED: 0,
        RUNNING: 1,
        WAITING: 2
    };

    vm.modes =
    {
      AUTO: 0,
      MANUAL: 1
    };

    vm.settings = {};
    vm.channel = "";

    vm.init = function() {
      return $.ajax({
        type: "GET",
        url: "api/settings",
        cache: false,
        async: false
      }).done(function (data)
      {
        vm.settings = data;
        vm.channel = vm.settings.channel;
      });
    }

    return vm;
});

define(['plugins/dialog', 'knockout'], function (dialog, ko) {

    var OptionSelectorModal = function(title, list) {
      this.title = title;
      this.list = ko.observableArray(list);
      this.selectedItem = ko.observable(list[0]);
    };

    OptionSelectorModal.prototype.ok = function() {
      debugger;
      dialog.close(this, this.selectedItem());
    };
    OptionSelectorModal.prototype.cancel = function() {
      dialog.close(this);
    };

    OptionSelectorModal.show = function(title, list){
      return dialog.show(new OptionSelectorModal(title, list));
    };

    return OptionSelectorModal;
});


/*jshint immed:false, browser:true, jquery:true */
/*global Hogan */

/* Social Icons Interface */

(function($) {

var doc = $(document);
var form, entriesContainer, buttonBox, entrySelect, select;
var availableItems = [];
var currentItems = [];
var newItemTemplate;

doc.ready(function() {
  setInitialData();
  prepareNewItemTemplate();
  initializeUserInterface();
});

function setInitialData() {
  form = $('#social-form');
  entriesContainer = form.find('.entries');
  buttonBox = form.find('.button-box');
  select = $('#social-manager-select');
  
  form.find('.item-entry').each(function(i, elem) {
    currentItems.push($(this).data('id'));
  });
  
  select.find('option').each(function(i, elem) {
    availableItems.push($(this).attr('value'));
  });
  
}

function prepareNewItemTemplate() {
  var container = $('#item-entry-template');
  var tpl = $.trim(container.html());
  newItemTemplate = Hogan.compile(tpl);
  container.remove();
}

function removeItem(id, arr) {
  var newArr = [];
  for (var i=0,len=arr.length; i < len; i++) {
    if (arr[i] == id) continue;
    else newArr.push(arr[i]);
  }
  return newArr;
}

function initializeUserInterface() {
  // Remove button
  doc.delegate('#social-manager .item-entry .remove', 'click', function(e) {
    var self = $(this);
    var parent = self.parents('.item-entry');
    var id = parent.data('id');
    currentItems = removeItem(id, currentItems);
    parent.remove();
    e.preventDefault();
  });
  
  // Add button
  $('#social-manager-add').click(function(e) {
    var chosen = select.find(':selected').attr('value');
    if (currentItems.indexOf(chosen) === -1) {
      var html = newItemTemplate.render({id: chosen});
      currentItems.push(chosen);
      entriesContainer.append(html);
      buttonBox.show();
    } else {
      entriesContainer.find('.item-entry.' + chosen + ' i.icon').stop().animate({marginLeft: 5}, 100, function() {
        $(this).stop().animate({marginLeft: 0}, 50);
      });
    }
    e.preventDefault();
  });
  
  // Sortable
  entriesContainer.sortable({
    axis: 'y',
    cancel: ':input,button,a.button',
    cursor: 'auto',
    delay: 100,
    distance: 1,
    items: '> .item-entry'
  });
}

})(jQuery);
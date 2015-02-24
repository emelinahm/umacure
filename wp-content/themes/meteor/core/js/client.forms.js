
/* client.forms.js */

(function($, Core) {

Core.once('meteor-forms-init', function(forms) {
  initMeteorForms(forms);
  initFormValidation(forms);
});

function initMeteorForms(forms) {
  if (forms.length) {
    var buttonTemplate = Hogan.compile('<a class="meteor-button {{{size}}} hoverable">{{#icon}}<i class="{{{icon}}}"></i> &nbsp; {{/icon}}{{text}}</a>');
    forms.find('textarea.autosize').autosize();
    forms.find('input:hidden[name=__action__]').each(function(i, elem) {
      var input = $(elem);
      input.parents('form').attr('action', $.base64.decode(input.val()));
      input.remove();
    });
    forms.find('input:submit').each(function(i, elem) {
      var submit = $(elem).hide();
      var button = $(buttonTemplate.render({
        size: submit.data('size') || 'large',
        icon: submit.data('icon'),
        text: submit.attr('value')
      })).insertAfter(submit).click(function(e) {
        submit.trigger('click');
        return e.preventDefault();
      });
      var id = submit.attr('id');
      if (id) button.attr('id', id + '-button');
    });
    forms.each(function(i, elem) {
      var form = $(elem);
      form.find('input:checkbox[data-enables]').each(function(_, elem) {
        var cb = $(elem);
        var targets = (cb.data('enables') || '').split(',');
        for (var input,len=targets.length,i=0; i < len; i++) {
          input = form.find('input:text[name="'+ targets[i] +'"]');
          targets[i] = (input.length) ? input : null;
        }
        var toggle = function() {
          var checked = cb.is(':checked');
          for (var input,len=targets.length,i=0; i < len; i++) {
            input = targets[i];
            if (input) {
              if (checked) {
                input.removeAttr('disabled');
              } else {
                input.attr('disabled', 'disabled');
              }
            }
          }
        }
        cb.change(toggle);
        toggle();
      });
    });
  }
}

function initFormValidation(forms) {

  var errRegex = /^ERR: /;

  forms.each(function(i, elem) {
    
    var form = $(elem);
  
    form.find('.required, [data-validation]').each(function(i, elem) {
      var input = $(elem);
      input.addClass(input.data('validation')).removeAttr('data-validation');
      input.after('<span class="invalid"><i class="icon-minus-sign"></i></span>')
    });
  
    var validateOptions = {
      errorPlacement: function() {},
      highlight: function(elem) { 
        $(elem).addClass('invalid'); 
      },
      unhighlight: function(elem) {
        $(elem).removeClass('invalid');
      },
      submitHandler: function() {
        if (form.hasClass('noajax')) {
          form.submit();
        } else {
          if (form.data('sent-message')) {
            alert(form.find('.meteor-notification').text());
          } else {
            form.ajaxSubmit({
              success: function(res) {
                form.find('.meteor-notification').remove();
                if (errRegex.test(res)) {
                  res = res.replace(errRegex, '');
                  form.find('input:submit').before('<div class="meteor-notification icon error"><i class="icon-minus-sign"></i>' + res + '</div>');
                } else {
                  form.find('input:submit').before('<div class="meteor-notification icon success"><i class="icon-ok"></i>' + res + '</div>');
                  form.data('sent-message', true);
                }
              }
            })
          }
        }
      }
    }
  
    form.validate(validateOptions);
    
  });

}

})(jQuery, window.Core);
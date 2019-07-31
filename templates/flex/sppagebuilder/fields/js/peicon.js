
jQuery(function($) {

  // Init dropdown
  $(document).on('click', '.pixeden-icon-input', function(event) {
    event.preventDefault();
    $(this).closest('.pixeden-icon-chooser').toggleClass('open');

    if($(this).closest('.pixeden-icon-chooser').hasClass('open')) {
      $(this).closest('.pixeden-icon-chooser').find('input[type="text"]').focus();
    }
  });


  // Select Icon
  $(document).on('click', '.pe-list-icon', function(event) {
    event.preventDefault();
    var $this = $(this);
    var parent = $this.closest('.pixeden-icon-chooser')
    var pe_icons = $(this).closest('ul').find('>li');
    
    pe_icons.removeClass('active');
    $this.addClass('active');
    
    parent.find('.pixeden-icon-input>span').html('<i class="pe '+ $this.data('pixeden_icon') +'"></i> ' + $this.data('pixeden_icon_name'));
	parent.find('.addon-input-pe').val($this.data('pixeden_icon_name'));
	parent.addClass('has-pe-icon').removeClass('open');
  });


  // Search Icon
  $(document).on('keyup', '.pixeden-dropdown input[type="text"]', function(){
    var value = $(this).val();
    var exp = new RegExp('.*?' + value + '.*?', 'i');

    $(this).next('.pixeden-icons').children().each(function() {
      var isMatch = exp.test($('span', this).text());
      $(this).toggle(isMatch);
    });
  });

  // Remove Icon
  $(document).on('click', '.remove-pe-icon', function(event) {
    event.stopPropagation();
    event.preventDefault();
    var $this = $(this);
    var parent = $this.closest('.pixeden-icon-chooser');

    parent.removeClass('has-pe-icon');
    parent.find('.pixeden-icon-input>span').html('--' + Joomla.JText._('COM_SPPAGEBUILDER_ADDON_ICON_SELECT') + '--');
    parent.find('.pixeden-icons>li').removeClass('active');
    parent.find('.addon-input-pe').val('');
  });
  
});
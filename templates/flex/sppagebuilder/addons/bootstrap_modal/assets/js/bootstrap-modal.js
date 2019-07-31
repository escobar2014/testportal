/* ========================================================================
 * Bootstrap: modal.js v3.2.0
 * http://getbootstrap.com/javascript/#modals
 * ========================================================================
 * Copyright 2011-2014 Twitter, Inc.
 * Licensed under MIT (https://github.com/twbs/bootstrap/blob/master/LICENSE)
 * ======================================================================== */


+function ($) {
  'use strict';
  

  // MODAL CLASS DEFINITION
  // ======================

  var SPPBModal = function (element, options) {
    this.options        = options
    this.$body          = $(document.body)
    this.$element       = $(element)
    this.$backdrop      =
    this.isShown        = null
    this.scrollbarWidth = 0

    if (this.options.remote) {
      this.$element
        .find('.sppb-modal-content')
        .load(this.options.remote, $.proxy(function () {
          this.$element.trigger('loaded.sppb.modal')
        }, this))
    }
  }

  SPPBModal.VERSION  = '3.2.0'

  SPPBModal.DEFAULTS = {
    backdrop: true,
    keyboard: true,
    show: true
  }

  SPPBModal.prototype.toggle = function (_relatedTarget) {
    return this.isShown ? this.hide() : this.show(_relatedTarget)
  }

  SPPBModal.prototype.show = function (_relatedTarget) {
    var that = this
    var e    = $.Event('show.sppb.modal', { relatedTarget: _relatedTarget })

    this.$element.trigger(e)

    if (this.isShown || e.isDefaultPrevented()) return

    this.isShown = true

    this.checkScrollbar()
    this.$body.addClass('sppb-modal-open')

    this.setScrollbar()
    this.escape()

    this.$element.on('click.dismiss.sppb.modal', '[data-dismiss="sppb-modal"]', $.proxy(this.hide, this))

    this.backdrop(function () {
      var transition = $.support.transition && that.$element.hasClass('sppb-fade')

      if (!that.$element.parent().length) {
        that.$element.appendTo(that.$body) // don't move modals dom position
      }

      that.$element
        .show()
        .scrollTop(0)

      if (transition) {
        that.$element[0].offsetWidth // force reflow
      }

      that.$element
        .addClass('in')
        .attr('aria-hidden', false)

      that.enforceFocus()

      var e = $.Event('shown.sppb.modal', { relatedTarget: _relatedTarget })

      transition ?
        that.$element.find('.sppb-modal-dialog') // wait for modal to slide in
          .one('bsTransitionEnd', function () {
            that.$element.trigger('focus').trigger(e)
          })
          .emulateTransitionEnd(300) :
        that.$element.trigger('focus').trigger(e)
    })
  }

  SPPBModal.prototype.hide = function (e) {
    if (e) e.preventDefault()

    e = $.Event('hide.sppb.modal')

    this.$element.trigger(e)

    if (!this.isShown || e.isDefaultPrevented()) return

    this.isShown = false

    this.$body.removeClass('sppb-modal-open')

    this.resetScrollbar()
    this.escape()

    $(document).off('focusin.sppb.modal')

    this.$element
      .removeClass('in')
      .attr('aria-hidden', true)
      .off('click.dismiss.sppb.modal')

    $.support.transition && this.$element.hasClass('sppb-fade') ?
      this.$element
        .one('bsTransitionEnd', $.proxy(this.hideModal, this))
        .emulateTransitionEnd(300) :
      this.hideModal()
  }

  SPPBModal.prototype.enforceFocus = function () {
    $(document)
      .off('focusin.sppb.modal') // guard against infinite focus loop
      .on('focusin.sppb.modal', $.proxy(function (e) {
        if (this.$element[0] !== e.target && !this.$element.has(e.target).length) {
          this.$element.trigger('focus')
        }
      }, this))
  }

  SPPBModal.prototype.escape = function () {
    if (this.isShown && this.options.keyboard) {
      this.$element.on('keyup.dismiss.sppb.modal', $.proxy(function (e) {
        e.which == 27 && this.hide()
      }, this))
    } else if (!this.isShown) {
      this.$element.off('keyup.dismiss.sppb.modal')
    }
  }

  SPPBModal.prototype.hideModal = function () {
    var that = this
    this.$element.hide()
    this.backdrop(function () {
      that.$element.trigger('hidden.sppb.modal')
    })
  }

  SPPBModal.prototype.removeBackdrop = function () {
    this.$backdrop && this.$backdrop.remove()
    this.$backdrop = null
  }

  SPPBModal.prototype.backdrop = function (callback) {
    var that = this
    var animate = this.$element.hasClass('sppb-fade') ? 'sppb-fade' : ''

    if (this.isShown && this.options.backdrop) {
      var doAnimate = $.support.transition && animate

      this.$backdrop = $('<div class="sppb-modal-backdrop ' + animate + '" />')
        .appendTo(this.$body)

      this.$element.on('click.dismiss.sppb.modal', $.proxy(function (e) {
        if (e.target !== e.currentTarget) return
        this.options.backdrop == 'static'
          ? this.$element[0].focus.call(this.$element[0])
          : this.hide.call(this)
      }, this))

      if (doAnimate) this.$backdrop[0].offsetWidth // force reflow

      this.$backdrop.addClass('in')

      if (!callback) return

      doAnimate ?
        this.$backdrop
          .one('bsTransitionEnd', callback)
          .emulateTransitionEnd(150) :
        callback()

    } else if (!this.isShown && this.$backdrop) {
      this.$backdrop.removeClass('in')

      var callbackRemove = function () {
        that.removeBackdrop()
        callback && callback()
      }
      $.support.transition && this.$element.hasClass('sppb-fade') ?
        this.$backdrop
          .one('bsTransitionEnd', callbackRemove)
          .emulateTransitionEnd(150) :
        callbackRemove()

    } else if (callback) {
      callback()
    }
  }

  SPPBModal.prototype.checkScrollbar = function () {
    if (document.body.clientWidth >= window.innerWidth) return
    this.scrollbarWidth = this.scrollbarWidth || this.measureScrollbar()
  }

  SPPBModal.prototype.setScrollbar = function () {
    var bodyPad = parseInt((this.$body.css('padding-right') || 0), 10)
    if (this.scrollbarWidth) this.$body.css('padding-right', bodyPad + this.scrollbarWidth)
  }

  SPPBModal.prototype.resetScrollbar = function () {
    this.$body.css('padding-right', '')
  }

  SPPBModal.prototype.measureScrollbar = function () { // thx walsh
    var scrollDiv = document.createElement('div')
    scrollDiv.className = 'sppb-modal-scrollbar-measure'
    this.$body.append(scrollDiv)
    var scrollbarWidth = scrollDiv.offsetWidth - scrollDiv.clientWidth
    this.$body[0].removeChild(scrollDiv)
    return scrollbarWidth
  }


  // MODAL PLUGIN DEFINITION
  // =======================

  function Plugin(option, _relatedTarget) {
    return this.each(function () {
      var $this   = $(this)
      var data    = $this.data('sppb.modal')
      var options = $.extend({}, SPPBModal.DEFAULTS, $this.data(), typeof option == 'object' && option)

      if (!data) $this.data('sppb.modal', (data = new SPPBModal(this, options)))
      if (typeof option == 'string') data[option](_relatedTarget)
      else if (options.show) data.show(_relatedTarget)
    })
  }

  var old = $.fn.sppbmodal

  $.fn.sppbmodal             = Plugin
  $.fn.sppbmodal.Constructor = SPPBModal


  // MODAL NO CONFLICT
  // =================

  $.fn.sppbmodal.noConflict = function () {
    $.fn.sppbmodal = old
    return this
  }


  // MODAL DATA-API
  // ==============

  $(document).on('click.sppb.modal.data-api', '[data-toggle="sppb-modal"]', function (e) {
    var $this   = $(this)
    var href    = $this.attr('href')
    var $target = $($this.attr('data-target') || (href && href.replace(/.*(?=#[^\s]+$)/, ''))) // strip for ie7
    var option  = $target.data('sppb.modal') ? 'toggle' : $.extend({ remote: !/#/.test(href) && href }, $target.data(), $this.data())

    if ($this.is('a')) e.preventDefault()

    $target.one('show.sppb.modal', function (showEvent) {
      if (showEvent.isDefaultPrevented()) return // only register focus restorer if modal will actually get shown
      $target.one('hidden.sppb.modal', function () {
        $this.is(':visible') && $this.trigger('focus')
      })
    })
    Plugin.call($target, option, this)
  })

  //Add Dynamic IDs
  $(document).ready(function(){
    $('.sppb-modal-selector').each(function (index){
      var id = 'sppb-modal' + (index+1);
      var modal = $(this).parent().next('.sppb-modal').appendTo('body');
      $(this).attr('data-target', '#' + id);
      $(modal).attr('id', id).attr('aria-labelledby', id + 'Label');
      $(modal).find('.sppb-modal-title').attr('id', id + 'Label');
    });
  });

}(jQuery);

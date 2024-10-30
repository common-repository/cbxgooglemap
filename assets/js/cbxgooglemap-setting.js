(function( $ ) {
    'use strict';

    $( document ).ready(function($) {

        //Initiate Color Picker
        $('.wp-color-picker-field').wpColorPicker();      
       
		$('.selecttwo-select').select2({
			placeholder: cbxgooglemap_setting.please_select,
			allowClear: false
		});

        // Switches option sections
        var activetab = '';
        if (typeof(localStorage) !== 'undefined') {
            activetab = localStorage.getItem('cbxgooglemapactivetab');
        }

        //if url has section id as hash then set it as active or override the current local storage value
        if(window.location.hash){
            if($(window.location.hash).hasClass('cbxgooglemap_group')){
                activetab = window.location.hash;
                if (typeof(localStorage) !== 'undefined' ) {
                    localStorage.setItem('cbxgooglemapactivetab', activetab);
                }
            }
        }        


        if (activetab !== '' && $(activetab).length && $(activetab).hasClass('cbxgooglemap_group')) {
            $('.cbxgooglemap_group').hide();
            $(activetab).fadeIn();
        }

        if (activetab !== '' && $(activetab + '-tab').length) {
            $('.nav-tab-wrapper a.nav-tab').removeClass('nav-tab-active');
            $(activetab + '-tab').addClass('nav-tab-active');
        }


        


        $('.nav-tab-wrapper a').on('click', function (e) {
            e.preventDefault();

            var $this = $(this);

            $('.nav-tab-wrapper a.nav-tab').removeClass('nav-tab-active');
            $this.addClass('nav-tab-active').blur();

            var clicked_group = $(this).attr('href');

            if (typeof (localStorage) !== 'undefined') {
                localStorage.setItem('cbxgooglemapactivetab', $(this).attr('href'));
            }
            $('.cbxgooglemap_group').hide();
            $(clicked_group).fadeIn();
        });

        $('.wpsa-browse').on('click', function (event) {
            event.preventDefault();

            var self = $(this);

            // Create the media frame.
            var file_frame = wp.media.frames.file_frame = wp.media({
                title: self.data('uploader_title'),
                button: {
                    text: self.data('uploader_button_text')
                },
                multiple: false
            });

            file_frame.on('select', function () {
                // var attachment = file_frame.state().get('selection').first().toJSON();
                //
                // self.prev('.wpsa-url').val(attachment.url);

                var attachment = file_frame.state().get('selection').first().toJSON();

                var picker_wrapper = self.closest('.cbxgooglemapmeta_input_file_wrap');

                picker_wrapper.find('.wpsa-url').val(attachment.url);
                picker_wrapper.find('.cbxgooglemapmeta_marker_preview').css({
                    'background-image' : 'url("'+attachment.url+'")'
                }).removeClass('cbxgooglemapmeta_marker_hide');
                picker_wrapper.find('.cbxgooglemapmeta_trash').removeClass('cbxgooglemapmeta_trash_hide');
                picker_wrapper.find('.cbxgooglemapmeta_filepicker_btn').addClass('cbxgooglemapmeta_filepicked').removeClass('cbxgooglemapmeta_left_space');
            });

            // Finally, open the modal
            file_frame.open();
        });

        // for icon delete functionality
        $(document).on('click', '.cbxgooglemapmeta_input_file_wrap .dashicons', function () {
            var picker_wrapper = $(this).closest('.cbxgooglemapmeta_input_file_wrap');
            picker_wrapper.find('.wpsa-url').val('');
            picker_wrapper.find('.cbxgooglemapmeta_marker_preview').addClass('cbxgooglemapmeta_marker_hide');
            picker_wrapper.find('.cbxgooglemapmeta_filepicker_btn').removeClass('cbxgooglemapmeta_filepicked').addClass('cbxgooglemapmeta_left_space');
            $(this).addClass('cbxgooglemapmeta_trash_hide');
        });

        //make the subheading single row
        $('.setting_subheading').each(function (index, element) {
            var $element = $(element);
            var $element_parent = $element.parent('td');
            $element_parent.attr('colspan', 2);
            $element_parent.prev('th').remove();
        });

        //make the subheading single row
        $('.setting_heading').each(function (index, element) {
            var $element = $(element);
            var $element_parent = $element.parent('td');
            $element_parent.attr('colspan', 2);
            $element_parent.prev('th').remove();
        });

        $('.cbxgooglemap_group').each(function (index, element) {
            var $element = $(element);
            var $form_table = $element.find('.form-table');
            $form_table.prev('h2').remove();
        });

        //one click save setting for the current tab
        $('#save_settings').on('click', function (e) {
            e.preventDefault();

            var $current_tab = $('.nav-tab.nav-tab-active');
            var $tab_id      = $current_tab.data('tabid');
            $('#' + $tab_id).find('.submit_cbxgooglemap').trigger('click');
        });

	    //copy shortcode
	    $('.shortcode_demo_btn').on('click', function (event) {
		    event.preventDefault();

		    var $this = $(this);
		    var $target = $this.data('target-cp');
		    var $copy_area = $($target);

		    $copy_area.focus();
		    $copy_area.select();

		    try {
			    var successful = document.execCommand('copy');
			    if(successful){
				    $this.text(cbxgooglemap_setting.copy_success);
				    $this.addClass('copy_success');
			    }
			    else{
				    $this.text(cbxgooglemap_setting.copy_fail);
				    $this.addClass('copy_fail');
			    }
		    } catch (err) {
			    $this.text(cbxgooglemap_setting.copy_fail);
			    $this.addClass('copy_fail');
		    }

	    });//end copy shortcode

    });
})( jQuery );

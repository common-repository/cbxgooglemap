(function ($) {
    'use strict';

    $(document).ready(function ($) {

        //select all text on click of shortcode text
        $('.cbxgooglemapshortcodecopytrigger').on('click', function (event) {
            var text = $(this).data('clipboard-text');
            var successText = $(this).data('success');
            // var $this = $(this);
            var $input = $('<input class="cbxgooglemapshortcode-text" type="text">');
            $input.prop('value', text);
            $input.insertAfter('body');
            // $input.focus();
            $input.select();
            try {
                document.execCommand('copy');
                $('.cbxgooglemapshortcode-text').remove();
                $(this).after('<span id="copied-text">'+successText+'</span>');
                // $('#copied-text').hide('slow');
                $('#copied-text').fadeOut(1000, function(){ $(this).remove();});
            } catch (err) {

            }
        });

    });
})(jQuery);

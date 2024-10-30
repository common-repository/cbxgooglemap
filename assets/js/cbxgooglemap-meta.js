(function ($) {
    'use strict';

    $(document).ready(function ($) {

        var $cbxmap = null;

        //Initiate Color Picker
        $('.cbxgooglemapmeta_colorpicker').wpColorPicker();

        $('.selecttwo-select').select2({
            placeholder: cbxgooglemap_meta.please_select,
            allowClear: false
        });


        $(document).on('click', '.cbxgooglemapmeta_filepicker_btn', function (event) {
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
                var attachment = file_frame.state().get('selection').first().toJSON();

                var picker_wrapper = self.closest('.cbxgooglemapmeta_input_file_wrap');

                picker_wrapper.find('.cbxgooglemapmeta_filepicker').val(attachment.url);
                picker_wrapper.find('.cbxgooglemapmeta_marker').css({
                    'background-image': 'url("' + attachment.url + '")'
                }).removeClass('cbxgooglemapmeta_marker_hide');
                picker_wrapper.find('.cbxgooglemapmeta_trash').removeClass('cbxgooglemapmeta_trash_hide');
                picker_wrapper.find('.cbxgooglemapmeta_filepicker_btn').addClass('cbxgooglemapmeta_filepicked').removeClass('cbxgooglemapmeta_left_space');
            });

            // Finally, open the modal
            file_frame.open();
        });

        // for icon delete functionality
        $(document).on('click', '.cbxgooglemapmeta_input_file_wrap .cbxgooglemapmeta_trash', function () {
            var picker_wrapper = $(this).closest('.cbxgooglemapmeta_input_file_wrap');
            picker_wrapper.find('.cbxgooglemapmeta_input_file').val('');
            picker_wrapper.find('.cbxgooglemapmeta_marker').addClass('cbxgooglemapmeta_marker_hide');
            picker_wrapper.find('.cbxgooglemapmeta_filepicker_btn').removeClass('cbxgooglemapmeta_filepicked').addClass('cbxgooglemapmeta_left_space');
            $(this).addClass('cbxgooglemapmeta_trash_hide');
        });

        // Switches option sections
        $('.metabox-content-cbxgooglemap').hide();
        var activetab = '';
        if (typeof (localStorage) !== 'undefined') {
            activetab = localStorage.getItem('cbxgooglemapmetaactivetab');

        }
        if (activetab !== '' && $(activetab).length) {
            $(activetab).fadeIn();
        } else {
            $('.metabox-content-cbxgooglemap:first').fadeIn();
        }

        $('.metabox-content-cbxgooglemap .collapsed').each(function () {
            $(this).find('input:checked').parent().parent().parent().nextAll().each(
                function () {
                    if ($(this).hasClass('last')) {
                        $(this).removeClass('hidden');
                        return false;
                    }
                    $(this).filter('.hidden').removeClass('hidden');
                });
        });

        if (activetab !== '' && $(activetab + '-tab').length) {
            $(activetab + '-tab').addClass('nav-tab-active');


            if (activetab + '-tab' === '#metabox-contentmaplocation-tab') {
                cbxgooglemapmeta_render();
            }
        } else {
            $('.nav-tab-wrapper a:first').addClass('nav-tab-active');
        }

        $('.nav-tab-wrapper a').on('click', function (evt) {
            evt.preventDefault();

            $('.nav-tab-wrapper a').removeClass('nav-tab-active');
            $(this).addClass('nav-tab-active').blur();
            var clicked_group = $(this).attr('href');
            if (typeof (localStorage) !== 'undefined') {
                localStorage.setItem('cbxgooglemapmetaactivetab', $(this).attr('href'));
            }
            $('.metabox-content-cbxgooglemap').hide();
            $(clicked_group).fadeIn();


            if ($(this).attr('id') == 'metabox-contentmaplocation-tab' && typeof $cbxmap !== null) {
                cbxgooglemapmeta_render();
            }
        });

        var cbxmap = '';


        function cbxgooglemapmeta_render() {
            //backend map integration
            $('.cbxgooglemapmeta_input_location').each(function (index, element) {
                var $element = $(element);
                var $parent = $element.closest('.metabox-holder-cbxgooglemap');


                var $zoom = $parent.find('.cbxgooglemapmeta_input_zoom');
                var $zoom_val = Number($zoom.val());
                var $show_info = Number($("input[name='_cbxgooglemapshowinfo']:checked").val());
                var $info_open = Number($("input[name='_cbxgooglemapinfow_open']:checked").val());
                var $scroll_wheel = Number($("input[name='_cbxgooglemapscrollwheel']:checked").val());


                var $heading = $parent.find('.cbxgooglemapmeta_input_title').val();
                var $address = $parent.find('.cbxgooglemapmeta_input_address').val();
                var $website = $parent.find('.cbxgooglemapmeta_input_website').val();


                var $lat = $('#metabox-contentmaplocation .cbxgooglemapmeta_input_lat');
                var $lng = $('#metabox-contentmaplocation .cbxgooglemapmeta_input_lng');
                var $current_lat = Number($lat.val());
                var $current_lng = Number($lng.val());
                var $icon_url = $('#metabox-contentmaplocation').find('.cbxgooglemapmeta_filepicker').val();


                if (!$icon_url) {
                    $icon_url = cbxgooglemap_meta.icon_url_default;
                }

                var $meta_map = $parent.find('.map_canvas');
                var $map_source = Number($meta_map.data('mapsource'));
                var $map_type = $("select[name='_cbxgooglemapmaptype']").val();
                var $api_key = $meta_map.data('apikey');

                if ($map_source === 1) {
                    //google map
                    if ($api_key !== '') {
                        // The location of Primary marker
                        var $latlng = {lat: $current_lat, lng: $current_lng};

                        // The map, centered at Primary marker
                        $cbxmap = new google.maps.Map($meta_map[0], {
                            zoom: $zoom_val,
                            center: $latlng,
                            mapTypeId: $map_type,
                            disableDefaultUI: true
                        });

                        if($icon_url == ''){
                            /*var $map_icon = {
                                url: $icon_url,
                                scaledSize: new google.maps.Size(50, 50)
                            };*/

                            $marker = new google.maps.Marker({
                                position: $latlng,
                                map: $cbxmap,
                                title: $heading,
                                draggable: true,
                                //icon: $map_icon,
                            });
                        }
                        else{
                            var $map_icon = {
                                url: $icon_url,
                                scaledSize: new google.maps.Size(50, 50)
                            };

                            $marker = new google.maps.Marker({
                                position: $latlng,
                                map: $cbxmap,
                                title: $heading,
                                draggable: true,
                                icon: $map_icon,
                            });
                        }




                        if ($show_info) {
                            var $info_content = '';
                            var $heading_html = '';
                            var $address_html = '';

                            if ($heading !== '') {
                                if ($website) {
                                    $heading_html = '<h3 class="jqcbxgoglemap_info_heading"><a href="' + $website + '" target="_blank">' + $heading + '</a></h3>';
                                } else {
                                    $heading_html = '<h3 class="jqcbxgoglemap_info_heading">' + $heading + '</h3>';
                                }
                            }

                            if ($address !== '') {
                                $address_html = '<div class="jqcbxgoglemap_info_body">' + $address + '</div>';
                            }


                            if ($heading_html !== '' || $address_html !== '') {
                                $info_content = '<div class="jqcbxgoglemap_info">' + $heading_html + ' ' + $address_html + '</div>';
                            }

                            // info popup
                            var infowindow = new google.maps.InfoWindow({
                                content: $info_content
                            });

                            $marker.addListener('click', function () {
                                infowindow.open({
                                    anchor: $marker,
                                    $cbxmap
                                });
                            });

                            if ($info_open) {
                                infowindow.open({
                                    anchor: $marker,
                                    $cbxmap
                                });
                            }
                        }

                        $marker.addListener('dragend', function (e) {
                            $lat.val(e.latLng.lat());
                            $lng.val(e.latLng.lng());
                        });

                    }//end if apy key exits

                } else {
                    //open street map

                    //at first destroy the map
                    if ($cbxmap && $cbxmap.remove) {
                        $cbxmap.off();
                        $cbxmap.remove();
                    }


                    $cbxmap = L.map($meta_map[0]).setView([$current_lat, $current_lng], $zoom_val);

                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                    }).addTo($cbxmap);

                    var $marker;

                    if ($icon_url !== '') {
                        var $map_icon = L.icon({
                            iconUrl: $icon_url,
                            iconSize: [50, 50],
                            popupAnchor: [15, 0]
                        });

                        $marker = L.marker([$current_lat, $current_lng], {
                            draggable: true,
                            icon: $map_icon
                        }).addTo($cbxmap);
                    } else {
                        $marker = L.marker([$current_lat, $current_lng], {
                            draggable: true
                        }).addTo($cbxmap);
                    }


                    if ($show_info === 1) {
                        var $heading_html = '';
                        var $address_html = '';

                        if ($heading !== '') {
                            if ($website) {
                                $heading_html = '<h3 class="jqcbxgoglemap_info_heading"><a href="' + $website + '" target="_blank">' + $heading + '</a></h3>';
                            } else {
                                $heading_html = '<h3 class="jqcbxgoglemap_info_heading">' + $heading + '</h3>';
                            }
                        }


                        if ($address !== '') {
                            $address_html = '<div class="jqcbxgoglemap_info_body">' + $address + '</div>';
                        }

                        if ($heading_html !== '' || $address_html !== '') {
                            if ($info_open) {
                                $marker.bindPopup('<div class="jqcbxgoglemap_info">' + $heading_html + '' + $address_html + '</div>').openPopup();
                            } else {
                                $marker.bindPopup('<div class="jqcbxgoglemap_info">' + $heading_html + '' + $address_html + '</div>').on('click', function (event) {
                                    //event.target.openPopup;
                                });
                            }
                        }
                    }

                    if ($scroll_wheel === 1) {
                        $cbxmap.scrollWheelZoom.disable();
                    }

                    $marker.on('dragend', function (e) {
                        $lat.val($marker.getLatLng().lat);
                        $lng.val($marker.getLatLng().lng);
                    });

                    $cbxmap.on('zoomend', function (e) {
                        $zoom.val($cbxmap.getZoom());
                    });

                    /*var geocoder = L.Control.geocoder({
                        placeholder: cbxgooglemap_meta.search_address,
                        defaultMarkGeocode: false
                    })
                        .on('markgeocode', function(e) {
                            var bbox = e.geocode.bbox;
                            //console.log(e.geocode);
                            //console.log(bbox.getCenter().lat);
                            //console.log(bbox.getCenter().lng);

                            $lat.val(bbox.getCenter().lat);
                            $lng.val(bbox.getCenter().lng);

                            $cbxmap.setView(bbox.getCenter(), $zoom_val);
                            $marker.setLatLng(bbox.getCenter());

                            // var poly = L.polygon([
                            // 	bbox.getSouthEast(),
                            // 	bbox.getNorthEast(),
                            // 	bbox.getNorthWest(),
                            // 	bbox.getSouthWest()
                            // ]).addTo(map);

                            //map.fitBounds(poly.getBounds());
                        }).addTo($cbxmap);*/


                }//end if openstreetmap

                CBXGOOGLEMAPEvents_do_action('cbxgooglemap_render_meta', $parent.find('.map_canvas'), $cbxmap, $map_source);


            });//end google map
        }


        //select shortcode text and copy to clipboard
        $(document).on('click', '.cbxgooglemapshortcodecopytrigger', function (e) {

            var text = $(this).data('clipboard-text');
            var successText = $(this).data('success');
            var $input = $('<input class="cbxgooglemapshortcode-text" type="text">');
            $input.prop('value', text);
            $input.insertAfter('body');
            $input.select();

            try {
                document.execCommand('copy');
                $('.cbxgooglemapshortcode-text').remove();
                $(this).after('<span id="copied-text">' + successText + '</span>');
                $('#copied-text').fadeOut(1000, function () {
                    $(this).remove();
                });
            } catch (err) {

            }
        });

    });
})(jQuery);
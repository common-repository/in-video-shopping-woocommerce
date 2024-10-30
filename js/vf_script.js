
var $ = jQuery;
var wp_color_picker;
var wp_color_picker_play_btn;	

$(document).ready(function() {
    //script for image upload

    //tooltip 
    //$('.hastip').tooltip();

    $('.vjs-controls').css('visibility','visible');

    $( "#vf_customize_accordion" ).accordion({
        heightStyle: "content"
    });

    $('.close').click(function(e){
        $('#popup1').hide();

    });

    /*$( "#splash_cancel" ).click(function() {
    $('#vf_splash ').val('');
    });
    $( "#image_cancel" ).click(function() {
    $('#vf_logo ').val('');
    });*/


    wp_color_picker = $('.vf_color_picker').wpColorPicker({
        hide: false,
        change: function(event, ui){vf_color_change( ui.color.toCSS())}
    });

    wp_color_picker_play_btn = $('.vf_color_picker_play_btn').wpColorPicker({
        hide: false,
        change: function(event, ui){vf_color_change_play_btn( ui.color.toCSS())}
    });



    vf_customize_change_events();
    vf_on_theme_select();
    vf_on_product_select();

    vf_upload_image();
    vf_upload_video();
    vf_clear_text_image();
    vf_customize_submit();
    vf_video_player_settings();
});

function vf_clear_text_image(){


    $('.vf_btn_cancel').click(function(e) {
        //alert('clear');
        var img_id = $(this).data('img');
        var inputbox_id = $(this).data('input');
        //console.log(img_id);
        //console.log(inputbox_id);

        $('#'+img_id).removeAttr('src');
        $('#'+inputbox_id).val('');


    });


}



function vf_upload_video(){

    $('.upload-video-new').click(function(e) {

        var video_id = $(this).data('id');
        var exts = ['mp4'];
        e.preventDefault();

        var video = wp.media({ 
            title: 'Upload Video',
            library : { type : 'video'},
            // mutiple: true if you want to upload multiple files at once
            multiple: false
        }).open()
        .on('select', function(e){
            // This will return the selected image /video  zfrom the Media Uploader, the result is an object
            var uploaded_video = video.state().get('selection').first();
            // We convert uploaded_video to a JSON object to make accessing it easier
            // Output to the console uploaded_video
            //console.log(uploaded_video);
            var video_url = uploaded_video.toJSON().url;
            var video_file_type = uploaded_video.toJSON().filename;
            var get_ext = video_file_type.split('.').pop();
            //console.log(get_ext);

            //console.log(get_ext);
            if($.inArray(get_ext,exts)>-1){
                console.log(video_id);
                $('.'+video_id).val(video_url);
                $('.vf_choice_external').val('');
            }else
            {
                //alert('not allow');
                $('.'+video_id).val('Please select the video type mp4*').css('color','red');

            }

            // Let's assign the url value to the input field

        });
       // console.log(video);
    });



}





function vf_upload_image(){

    $('.upload-btn').click(function(e) {

        var img_id = $(this).data('id');
        //console.log(img_id);
        //alert("here");
        e.preventDefault();
        var image = wp.media({ 
            title: 'Upload Image',
            library : { type : 'image'},
            // mutiple: true if you want to upload multiple files at once
            multiple: false
        }).open()
        .on('select', function(e){
            // This will return the selected image zfrom the Media Uploader, the result is an object
            var uploaded_image = image.state().get('selection').first();
            // We convert uploaded_image to a JSON object to make accessing it easier
            // Output to the console uploaded_image
            console.log(uploaded_image);
            var image_url = uploaded_image.toJSON().url;
            // Let's assign the url value to the input field
            $('#'+img_id).val(image_url);
        });
        console.log(image);
    });



}




function vf_color_change (color){

    $('.vjs-big-play-button').css('background-color', color);

    $('.vjs-controls').css('background-color', color);

    $('<style> .vjs-default-skin .vjs-control::before{color:'+color+'!important;}</style>').appendTo('head');

    $('.vjs-play-progress, .vjs-volume-level').css('background-color', color);
}



function vf_color_change_play_btn(color){
    //console.log(color);
   
   $('<style>.vjs-big-play-button::before{color:'+color+'!important;}</style>').appendTo('head');
   
   //$('.vjs-play-progress, .vjs-volume-level').css('background-color', color);
   


    //$('.vjs-big-play-button').css('background-color', color);
}

function vf_customize_change_events(){

    // did by ritesh 
    /*$('.vf_catch_change').change(function() {

    var vjs_class = $(this).data('vjs-class');

    if($(this).is(':checked')) {

    $(vjs_class).hide();
    }else{
    $(vjs_class).show();
    }

    });*/


    //sagar 13march	
    $('.onoffswitch-checkbox').change(function() {

        var vjs_class = $(this).data('vjs-class');

        //console.log(vjs_class);
        var clickedID = this.id;
        // console.log( clickedID);
        $('#'+clickedID).val( $(this).is(':checked') ? '1' : '0' );


        if($(this).is(':checked')) {
            console.log(vjs_class);

            $(vjs_class).show();

            $("#off").removeClass('active');
            $("#on").addClass('active');

        }else{
            $(vjs_class).hide();

            $("#off").addClass('active');
            $("#on").removeClass('active');

        }

    });
}

function vf_customize_submit(){

    $( "#frm_player_customization" ).submit(function( event ) {
        event.preventDefault();
        var form_data = {};

        $.each($(this).serializeArray(), function(i, field) {
            form_data[field.name] = field.value;
        });
        //console.log(form_data);
        var data = {
            'action': 'customize_player_ajax',
            'form_data': form_data      // We pass php values differently!
        };

        // We can also pass the url value separately from ajaxurl for front end AJAX implementations
        jQuery.post(vf_vars.ajax_url, data, function(response) {
            //alert('Got this from the server: ' + response);
        });

        jQuery('#popup1').show();


    });
}

function vf_on_product_select(){
    //$('.vf_select_prod').change(function() {
    $('body').on( 'change', '.vf_select_prod', function() {
        // alert("gjjh");

        var table = $(this).closest('table');
        console.log(table);
        //var table_id = $(this).data('table-id');
        var image = $(this).find('option:selected').data('image');
        var title = $(this).find('option:selected').data('title');
        var desc = $(this).find('option:selected').data('desc');

        console.log(image);
        //  console.log($('#'+table_id + ' .vf_product_main_container').find('.vf_image img'));

        table.find('.vf_product_main_container .vf_image').html('<img src="'+ image +'">');
        //table.find('.vf_product_main_container .vf_image').html(""image);
        table.find('.vf_product_main_container .vf_title').html(title);
        table.find('.vf_product_main_container .vf_description').html(desc);

        table.find('.vf_product_main_container').show();
        /*  
        var res = select_id.split("_"); 
        $('img').attr('src', res[1]);
        console.log(res[1]);*/



    });
}









function vf_on_theme_select(){
    $( "#vf_user_themes" ).change(function( event ) {


        var theme_id = $(this).val();
        console.log(theme_id);


        if(0 != theme_id){
            $( "input:hidden[name=vf_action]" ).val('edit');


            var data = {
                'action': 'get_theme_by_id',
                'theme_id': theme_id,
                'vf_user_id' : 1
            };
            // We can also pass the url value separately from ajaxurl for front end AJAX implementations
            jQuery.post(vf_vars.ajax_url, data, function(response) {

                //alert("eehehh");

                var vf_theme = jQuery.parseJSON(response);
                //console.log(wp_color_picker.defaultColour('#077a76'));
                console.log(vf_theme);

                vf_color_change(vf_theme.theme_color);
                vf_color_change_play_btn(vf_theme.play_button_color);

                /*$('.vf_color_picker').wpColorPicker( {
                change: _.throttle(function() {
                $(this).trigger( 'change' );
                }, 3000)
                });
                */
                 $('.vf_color_picker').wpColorPicker('color', vf_theme.theme_color);
                $('.vf_color_picker_play_btn').wpColorPicker('color', vf_theme.play_button_color);

                //wp_color_picker('color', '#'+vf_theme.theme_color);

                //wp_color_picker.change(null, vf_theme.theme_color);
                //wp_color_picker_play_btn.change(null, vf_theme.button_color);

                //$('.wp-color-result').css('background-color', vf_theme.theme_color);

                $( "input:text[name=vf_theme_name]" ).val(vf_theme.theme_name);
                $( "input:hidden[name=vf_video_theme_id]" ).val(vf_theme.id);
                $( "input:hidden[name=vf_theme_color]" ).val(vf_theme.theme_color);
                $( "input:hidden[name=button_color]" ).val(vf_theme.button_color);
                $( "input:hidden[name=vf_theme_color]" ).attr('data-default-color',vf_theme.theme_color);
                $( "input:hidden[name=vf_theme_play_button_color]" ).attr('data-default-color',vf_theme.play_button_color);

                /*var val1 = ( 1 ==  vf_theme.theme_control_meta.vf_tcm_all) ? 1 : 0;
                console.log(val1)
                $( "input:checkbox[name=vf_tcm_all]" ).val(value);*/

                //var value = ( 1 ==  vf_theme.theme_control_meta.vf_tcm_all) ? 1 : 0;
                // console.log( clickedID);
                //$('#myonoffswitch').val(value);

                //alert('Got this from the server: ' + response);

                // $("input:checkbox[name=vf_tcm_all]").val();
                var value = ( 1 ==  vf_theme.theme_control_meta.vf_tcm_all) ? 1 : 0;
                //console.log(value);
                $("input:checkbox[name=vf_tcm_all]").prop('checked', value);
                $("input:checkbox[name=vf_tcm_all]").val(vf_theme.theme_control_meta.vf_tcm_all);
                $( "input:checkbox[name=vf_tcm_all]" ).trigger( 'change');

                var value = ( 1 ==  vf_theme.theme_control_meta.vf_tcm_big_play_button) ? true : false;
                $("input:checkbox[name=vf_tcm_big_play_button]").prop('checked', value);
                $("input:checkbox[name=vf_tcm_big_play_button]").val(vf_theme.theme_control_meta.vf_tcm_big_play_button);
                $( "input:checkbox[name=vf_tcm_big_play_button]" ).trigger( 'change');



                var value = ( 1 ==  vf_theme.theme_control_meta.vf_tcm_control_bar) ? true : false;
                $("input:checkbox[name=vf_tcm_control_bar]").prop('checked', value);
                $("input:checkbox[name=vf_tcm_control_bar]").val(vf_theme.theme_control_meta.vf_tcm_control_bar);
                $( "input:checkbox[name=vf_tcm_control_bar]" ).trigger( 'change');


                var value = ( 1 ==  vf_theme.theme_control_meta.vf_tcm_fullscreen_button) ? true : false;
                $("input:checkbox[name=vf_tcm_fullscreen_button]").prop('checked', value);
                $("input:checkbox[name=vf_tcm_fullscreen_button]").val(vf_theme.theme_control_meta.vf_tcm_fullscreen_button);
                $( "input:checkbox[name=vf_tcm_fullscreen_button]" ).trigger( 'change');


                var value = ( 1 ==  vf_theme.theme_control_meta.vf_tcm_small_play_button) ? true : false;
                $("input:checkbox[name=vf_tcm_small_play_button]").prop('checked', value);
                $("input:checkbox[name=vf_tcm_small_play_button]").val(vf_theme.theme_control_meta.vf_tcm_small_play_button);
                $( "input:checkbox[name=vf_tcm_small_play_button]" ).trigger( 'change');


                var value = ( 1 ==  vf_theme.theme_control_meta.vf_tcm_timers) ? true : false;
                $("input:checkbox[name=vf_tcm_timers]").prop('checked', value);
                $("input:checkbox[name=vf_tcm_timers]").val(vf_theme.theme_control_meta.vf_tcm_timers);
                $( "input:checkbox[name=vf_tcm_timers]" ).trigger( 'change');


                var value = ( 1 ==  vf_theme.theme_control_meta.vf_tcm_volume_bar) ? true : false;
                $("input:checkbox[name=vf_tcm_volume_bar]").prop('checked', value);
                $("input:checkbox[name=vf_tcm_volume_bar]").val(vf_theme.theme_control_meta.vf_tcm_volume_bar);
                $( "input:checkbox[name=vf_tcm_volume_bar]" ).trigger( 'change');



            });
        }else{
            $( "input:text[name=vf_video_theme_id]" ).val('');
            $( "input:text[name=vf_action]" ).val('add');
        }


    });
}

function vf_video_player_settings(){
    $('.vjs-control-bar').show();

    
    //document.styleSheets[0].cssRules[0].style.backgroundColor = 'red';
}

  
<?php

if (  ! defined(  'ABSPATH'  )  ) {
    exit; // Exit if accessed directly
}

global $dimensions;


?>
<script>
    "use strict";
    var $ = jQuery;
    var cloned_data = '';
    $(  function (   )
    {

        cloned_data =  $( '#vf_add_products' ).find( '.vf_new_cloner' ).html(  );
        $('.vf_new_cloner').remove();

        //console.log( cloned_data );

        $( '.vf_add_more' ).on( 'click', add_more );
        $( document ).on( 'click','.vf_remove', vf_remove );

        hosting_choice();



    } );

    function hosting_choice(){


        $('.vf_choice').on('input',function(e){
            //alert($(this).val());
            $('.vf_video_id').val($(this).val());
        });

        $('.choices a').click(function(){
            var selected = $(this).data('type');
            var url_type_id = $(this).data('url-type-id');


            var youtube_text = '<?php _e('Paste your Youtube link here');?>';
            var vimeo_text = '<?php _e('Paste your Vimeo link here');?>';
            var external_text = '<?php _e('Paste your external link here');?>';



            switch(url_type_id){
                case 1 :

                    $('.vf_choice_external').attr('placeholder', external_text);
                    break;

                case 2 :

                    $('.vf_choice_external').attr('placeholder', youtube_text);
                    break;

                case 3 :

                    $('.vf_choice_external').attr('placeholder', vimeo_text);
                    break;
            }

            $('.vf_url_type_id').val(url_type_id);
            console.log(selected);
            $('.choice').hide();
            $('.'+selected).show();
        });
    }

    function add_more(  ){


        $( '.vf_cloneable_data' ).append( cloned_data );

        // $('.vf_required').prop('required',true);

        //console.log( cloned_data );

    }



    function vf_remove(  ){

        $(  this  ).parent(  ).parent(  ).parent(  ).parent().next().remove(  );
        $(  this  ).parent(  ).parent(  ).parent(  ).remove(  );
        $( this ).remove(  );
    }

</script>
<?php
$url = ( !empty( $this->video_details['url'] ) )?$this->video_details['url']:'';

$server_name = $_SERVER['SERVER_NAME'];

if (strpos($url,$server_name) !== false) {
    $show = 'selfhosted';
}else{
    $show = 'external';
}
//echo $show;
//print_r($_SERVER);
?>

<form method="POST" action="" >
    <input type="hidden" name="vf_video_id" value="" >
    <input type="hidden" name="vf_url_type_id" value="<?php echo $url = ( !empty( $this->video_details['url_type_id'] ))?$this->video_details['url_type_id']:''; ?>" class="vf_url_type_id" >
    <input style="display: none;" type="text" name="vf_video_link"  class="vf_video_id"  value="<?php echo $url = ( !empty( $this->video_details['url'] ))?$this->video_details['url']:''; ?>"  />
    <table class="video-info">

        <tr>
            <td class="first-column"><span class="heading-title"><?php _e( 'Add video','in-video-shopping-woocommerce' );?></span></td>
            <td colspan="3">


            </td>
        </tr>

        <tr>
            <td class="first-column">
                <div><a class="css-tooltip-top color-green" title=""> <span> <p><?php _e( 'Tell us if your video is hosted by Youtube, Vimeo or another site. If your video file is not hosted anywhere, you can upload an mp4 file to your site, although this is not recommended.','in-video-shopping-woocommerce' );?></p> </span>? </a></div>
                <label><?php _e( 'Video source','in-video-shopping-woocommerce' );?> </label>
            </td>
            <td colspan="3">

                <div class="vf_videosource">

                    <div class="choices">
                        <a class="vf-btn" href="javascript:;" data-type="external" data-url-type-id="2">Youtube </a>
                        <a class="vf-btn" href="javascript:;" data-type="external" data-url-type-id="3">Vimeo</a>
                        <a class="vf-btn" href="javascript:;" data-type="external" data-url-type-id="1">External link </a>
                        <a class="vf-btn" href="javascript:;" data-type="selfhosted" data-url-type-id="1">Upload video </a>
                    </div>
                </div>
            </td>
        </tr>


        <tr class="choice selfhosted" style="display: <?php echo $display = ('selfhosted' == $show)? 'table-row':'none';?>">
            <td class="first-column">
                <div><a class="css-tooltip-top color-green" title=""> <span><!--<img src="<?php echo _VF_PLUGIN_PATH_;?>/assets/images/icon-tooltip.png"/>--> <p><?php _e( 'Place a video link or upload an mp4 file here.','in-video-shopping-woocommerce' );?></p> </span>? </a></div>
                <label><?php _e( 'Self hosted *','in-video-shopping-woocommerce' );?> </label></td>
            <td colspan="3"> <a class="upload-video-new" target="_blank" data-id="vf_video_id"  href="<?php //echo get_admin_url(  ).'/upload.php';?>"><?php _e( 'Upload video','in-video-shopping-woocommerce' );?></a>

                <input id="vf_video_id" type="text" name="vf_choice_selfhosted"   value="<?php echo $url = ( !empty( $this->video_details['url'] ) && 'table-row' == $display )?$this->video_details['url']:''; ?>" class="widefat vf_video_id vf_choice" placeholder=""  /></td>
        </tr>

        <tr class="choice external" style="display: <?php echo $display = ('external' == $show)? 'table-row':'none';?>">
            <td class="first-column">
                <div><a class="css-tooltip-top color-green" title=""> <span><!--<img src="<?php echo _VF_PLUGIN_PATH_;?>/assets/images/icon-tooltip.png"/>--> <p><?php _e( 'Place a video link or upload an mp4 file here.','in-video-shopping-woocommerce' );?></p> </span>? </a></div>
                <label><?php _e( 'Video file *','in-video-shopping-woocommerce' );?> </label></td>
            <td colspan="3">
                <input type="text" name="vf_choice_external"   value="<?php echo $url = ( !empty( $this->video_details['url'] ) && 'table-row' == $display)?$this->video_details['url']:''; ?>" class="widefat vf_choice vf_choice_external"  placeholder="Paste your Youtube link here"  /></td>
        </tr>

        <tr class="add-video">
            <td class="first-column mr-space">
                <div>
                    <a class="css-tooltip-top color-green" title="">
                        <span>
                            <p><?php _e( 'A splash image is shown before your video starts. Upload one according to your video\'s dimensions. Oh, and don\'t forget to make it... splashy.', 'in-video-shopping-woocommerce'  );?></p>
                        </span>?
                    </a>
                </div>

                <label><?php _e( 'Splash image','in-video-shopping-woocommerce' );?></label>

                <!--    <p>Used before the video starts</p>--></td>
            <td class="mr-space"><div class="vf_image_upload splsh-ipload"> <a class="Upload-splash-image upload-btn"  data-id="vf_splash" target="_blank" href="#"><?php _e( 'Upload splash image','in-video-shopping-woocommerce' );?></a>
                    <input placeholder="Splash image URL" type="text" name="vf_splash_image" value="<?php echo $splash = ( !empty( $this->video_details['splash_image'] ) )?$this->video_details['splash_image']:''; ?>" class="widefat " id="vf_splash" />
                    <div class="splash-image">
                        <?php if( $splash ){?>
                            <div class="img-box">
                                <img src="<?php echo $splash;?>" id="splash-src">
                            </div>
                            <span class="vf_btn_cancel" data-img="splash-src" data-input="vf_splash" id="splash_cancel">x</span>  <?php }?></div>
                </div></td>
        </tr>

        <tr>
            <td class="first-column"><span class="heading-title"><?php _e( 'Player settings','in-video-shopping-woocommerce' );?></span></td>
            <td colspan="3">&nbsp;</td>
        </tr>
        <tr>
            <td class="first-column">
                <div>
                    <div class="css-tooltip-top color-green" title="">
                        <span>
                            <p><?php _e( 'Set the size of your player according to a proper aspect ratio. Read more about it <a class="tooltip-link" target="_blank" href="http://www.video-force.com/optimal-video-dimensions" >here</a>','in-video-shopping-woocommerce' );?></p>
                        </span>?
                    </div>
                </div>
                <label><?php _e( 'Player dimensions *','in-video-shopping-woocommerce' );?></label>
            </td>
            <td><div class="dimension-boxes"> <span class="width"><?php _e( 'Width','in-video-shopping-woocommerce' );?></span>
                    <?php
                    if( !empty( $this->video_details ) )
                        $dimensions = unserialize( $this->video_details['iframe_dimensions'] );?>
                    <input type="text" name="vf_iframe_dimensions[width]" value="<?php echo $width = ( isset( $dimensions['width'] ) )? $dimensions['width']:'';?>" placeholder="" required >
                    <b>px</b> </div>
                <div class="dimension-boxes"> <span class="width">Height</span>
                    <input type="text" name="vf_iframe_dimensions[height]" value="<?php echo $height = ( isset( $dimensions['height'] ) )? $dimensions['height']:'';?>" placeholder=""  required>
                    <b>px</b> </div></td>
        </tr>


        <tr class="grey_line">
            <td class="first-column mr-space">
                <div>
                    <div class="css-tooltip-top color-green" title="">
                        <span>
                            <p><?php _e( 'Choose a theme that matches with your branding. Haven\'t created a theme yet? No problemo. You can get it done <a class="tooltip-link" target="_blank" href="./edit.php?post_type=video&page=player-customization" >here</a>','in-video-shopping-woocommerce' );?></p>
                        </span>?
                    </div>
                </div>
                <label><?php _e( 'Player Theme','in-video-shopping-woocommerce' );?></label>
            </td>
            <td class="mr-space">

                <select class="dropdown" id="vf_video_theme_id" name="vf_video_theme_id">
                    <option value="0"><?php _e( 'Player theme','in-video-shopping-woocommerce' );?></option>
                    <?php

                    foreach( $all_themes as $each_theme ){?>
                        <option value="<?php echo $each_theme['id']?>" <?php echo ( $each_theme['id'] == $this->video_details['video_theme_id'] )?'selected':''; ?> ><?php echo $each_theme['theme_name']?></option>
                    <?php }?>
                </select></td>
        </tr>

        <tr>
            <td class="first-column"><span class="heading-title"><?php _e( 'Cart settings','in-video-shopping-woocommerce' );?></span></td>
            <td colspan="3">&nbsp;</td>
        </tr>

        <tr>
            <td class="first-column">
                <a class="css-tooltip-top color-green" title="">
                    <span>
                        <p><?php _e( 'Choose where the cart icon should appear in your video.' );?></p>
                    </span>?
                </a>
                <label><?php _e( 'Cart icon position','in-video-shopping-woocommerce' );?></label>
            </td>
            <td ><?php
                if( !empty( $this->video_details ) )
                    $cart = unserialize( $this->video_details['cart'] );?>
                <select name="vf_cart[position]"  required>
                    <?php if( !empty( $this->cart_display_positions ) )
                        foreach( $this->cart_display_positions as $key => $each_position ){
                            $selected = ( $cart['position'] == $key )?'selected':'';
                            ?>
                            <option <?php echo $selected; ?> value="<?php echo $key;?>"> <?php echo $each_position;?></option>
                        <?php }?>
                </select></td>
        </tr>
        <tr>
            <td class="first-column">
                <div>
                    <a class="css-tooltip-top color-green" title="">
                        <span>
                            <p><?php _e( 'Looking to be a bit different? Here you can upload your own cart icon.','in-video-shopping-woocommerce' );?></p>
                        </span>?
                    </a>
                </div>
                <label><?php _e( 'Overwrite cart icon','in-video-shopping-woocommerce' );?></label></td>
            <td><div class="vf_image_upload splsh-ipload Upload-logomain"> <a class="Upload-logo upload-btn" href="#" data-id="vf_logo"><?php _e( 'Upload cart icon','in-video-shopping-woocommerce' );?></a>
                    <input type="text" name="vf_cart[image_url]" value="<?php echo $image = ( isset( $cart['image_url'] ) )? $cart['image_url']:'';?>"  placeholder="Cart icon URL"  id="vf_logo" class="widefat1 ">
                    <div class="splash-image">
                        <?php if( $image ){?>
                            <img src="<?php echo $image;?>" id="logo-src">

                            <span class="vf_btn_cancel" id="image_cancel" data-img="logo-src" data-input="vf_logo">x</span>  <?php }?></div>
                </div></td>
        </tr>
    </table>
    <input type="hidden" name="vf_shop_id" value="1">
    <input type="hidden" name="vf_video_type_id" value="1">
</form>

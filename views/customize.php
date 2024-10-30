<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
?>
<div class="wrap">
<h2><?php _e( 'Player settings','in-video-shopping-woocommerce' );?>    </h2>

<div id="vf_themes">

    <p><?php _e( 'Select a template you would like to edit','in-video-shopping-woocommerce' );?></p>
    <select class="dropdown" id="vf_user_themes" name="vf_user_themes">
        <option value="0"><?php _e( 'Select a template','in-video-shopping-woocommerce' );?></option>
        <?php foreach( $all_themes as $each_theme ){?>
            <option value="<?php echo $each_theme['id']?>"><?php echo $each_theme['theme_name']?></option>
        <?php }?>
    </select>


</div>

<div class="vf_form">
    <form id="frm_player_customization" action="" method="post">

        <div id="vf_customize_accordion" class="vf_left_sidebar">

            <h3><?php _e( 'Add Template','in-video-shopping-woocommerce' );?></h3>
            <div>
                <table>

                    <tr>
                        <td><label><?php _e( 'Template name *','in-video-shopping-woocommerce' );?></label>

                            <input type="text" name="vf_theme_name" value="" required>
                        </td>
                        <!--<td colspan="3"><input type="text" name="vf_theme_name" value="" ></td>-->
                    </tr>
                </table>
            </div>


            <h3><?php _e( 'Template color','in-video-shopping-woocommerce' );?></h3>
            <div>
                <table>

                    <!--<tr>
                    <td><label>Theme name</label></td>
                    <td colspan="3"><input type="text" name="vf_theme_name" value="" ></td>
                    </tr>
                    -->
                    <tr>
                        <td>  <label><?php _e( 'Template color','in-video-shopping-woocommerce' );?></label>
                            <input class="vf_color_picker" name="vf_theme_color" type="text"  value="#17b286" data-default-color="#17b286" />
                        </td>
                    </tr>
                    <tr>
                        <td>  <label><?php _e( 'Control icon colors','in-video-shopping-woocommerce' );?></label>
                            <input class="vf_color_picker_play_btn" name="play_button_color" type="text"  value="#17b286" data-default-color="#17b286" /></td>

                    </tr>
                </table>
            </div>
            <h3> <?php _e( 'Video controls','in-video-shopping-woocommerce' );?></h3>

            <div>
                <table>
                    <tr>
                        <td> <label><?php _e( 'Controls taskbar','in-video-shopping-woocommerce' );?></label>

                            <div class="onoffswitch">
                                <input class="onoffswitch-checkbox"  data-vjs-class=".vjs-control-bar" type="checkbox"   name="vf_tcm_all" id="myonoffswitch"  value="1" checked>
                                <label class="onoffswitch-label" for="myonoffswitch">
                                    <span class="off" id="off"><?php _e( 'Off','in-video-shopping-woocommerce' );?></span>
                                    <span class="onoffswitch-inner"></span>
                                    <span class="onoffswitch-switch"></span>
                                    <span class="on" id="on"><?php _e( 'On','in-video-shopping-woocommerce' );?></span>
                                </label>
                            </div>

                        </td>

                    </tr>

                    <!-- <tr>
                    <td>  <input class="vf_catch_change" data-vjs-class=".vjs-controls" type="checkbox" name="vf_tcm_all" value="1 "></td>
                    <td> <label>All controls</label></td>
                    </tr>-->

                    <tr>
                        <!-- <td>  <input class="vf_catch_change" data-vjs-class=".vjs-big-play-button" type="checkbox" name="vf_tcm_big_play_button" value="1"></td>-->

                        <td> <label><?php _e( 'Big play button','in-video-shopping-woocommerce' );?> </label>

                            <div class="onoffswitch">
                                <input class="onoffswitch-checkbox"  data-vjs-class=".vjs-big-play-button" type="checkbox" name="vf_tcm_big_play_button" id="myonoffswitch1" value="1" checked>
                                <label class="onoffswitch-label" for="myonoffswitch1">
                                    <span class="off" ><?php _e( 'Off','in-video-shopping-woocommerce' );?></span>
                                    <span class="onoffswitch-inner"></span>
                                    <span class="onoffswitch-switch"></span>
                                    <span class="on" ><?php _e( 'On','in-video-shopping-woocommerce' );?></span>
                                </label>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <!--<td >  <input class="vf_catch_change" data-vjs-class=".vjs-play-control" type="checkbox" name="vf_tcm_small_play_button" value="1"></td>-->
                        <td> <label><?php _e( 'Small play button','in-video-shopping-woocommerce' );?></label>

                            <div class="onoffswitch">
                                <input class="onoffswitch-checkbox"  data-vjs-class=".vjs-play-control" type="checkbox" name="vf_tcm_small_play_button" id="myonoffswitch2" value="1" checked>
                                <label class="onoffswitch-label" for="myonoffswitch2">
                                    <span class="off" ><?php _e( 'Off','in-video-shopping-woocommerce' );?></span>
                                    <span class="onoffswitch-inner"></span>
                                    <span class="onoffswitch-switch"></span>
                                    <span class="on" ><?php _e( 'On','in-video-shopping-woocommerce' );?></span>
                                </label>
                            </div>
                        </td>
                    </tr>


                    <tr>
                        <!--<td >  <input class="vf_catch_change" data-vjs-class=".vjs-progress-control"  type="checkbox" name="vf_tcm_control_bar" value="1"></td>-->
                        <td> <label><?php _e( 'Progress bar','in-video-shopping-woocommerce' );?></label>

                            <div class="onoffswitch">
                                <input class="onoffswitch-checkbox"  data-vjs-class=".vjs-progress-control" type="checkbox" name="vf_tcm_control_bar" id="myonoffswitch3" value="1" checked>
                                <label class="onoffswitch-label" for="myonoffswitch3">
                                    <span class="off" ><?php _e( 'Off','in-video-shopping-woocommerce' );?></span>
                                    <span class="onoffswitch-inner"></span>
                                    <span class="onoffswitch-switch"></span>
                                    <span class="on " ><?php _e( 'On','in-video-shopping-woocommerce' );?></span>
                                </label>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <!--<td >  <input class="vf_catch_change"  data-vjs-class=".vjs-volume-control, .vjs-mute-control" type="checkbox" name="'vf_tcm_volume_bar" value="1"></td>-->
                        <td> <label><?php _e( 'Volume control','in-video-shopping-woocommerce' );?></label>

                            <div class="onoffswitch">
                                <input class="onoffswitch-checkbox"  data-vjs-class=".vjs-mute-control, .vjs-volume-control" type="checkbox" name="vf_tcm_volume_bar" id="myonoffswitch4" value="1" checked>
                                <label class="onoffswitch-label" for="myonoffswitch4">
                                    <span class="off" ><?php _e( 'Off','in-video-shopping-woocommerce' );?></span>
                                    <span class="onoffswitch-inner"></span>
                                    <span class="onoffswitch-switch"></span>
                                    <span class="on " ><?php _e( 'On','in-video-shopping-woocommerce' );?></span>
                                </label>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <!--<td >  <input class="vf_catch_change" data-vjs-class=".vjs-fullscreen-control"  type="checkbox" name="vf_tcm_fullscreen_button" value="1"></td>-->
                        <td> <label><?php _e( 'Full screen control','in-video-shopping-woocommerce' );?></label>

                            <div class="onoffswitch">
                                <input class="onoffswitch-checkbox"  data-vjs-class=".vjs-fullscreen-control " type="checkbox" name="vf_tcm_fullscreen_button" id="myonoffswitch5" value="1" checked>
                                <label class="onoffswitch-label" for="myonoffswitch5">
                                    <span class="off" ><?php _e( 'Off','in-video-shopping-woocommerce' );?></span>
                                    <span class="onoffswitch-inner"></span>
                                    <span class="onoffswitch-switch"></span>
                                    <span class="on " ><?php _e( 'On','in-video-shopping-woocommerce' );?></span>
                                </label>
                            </div>
                        </td>

                    </tr>
                    <tr>
                        <!--<td >  <input class="vf_catch_change" data-vjs-class=".vjs-time-controls"  type="checkbox" name="vf_tcm_timers" value="1"></td>-->

                        <td> <label><?php _e( 'Timers','in-video-shopping-woocommerce' );?></label>

                            <div class="onoffswitch">
                                <input class="onoffswitch-checkbox"  data-vjs-class=".vjs-time-controls " type="checkbox" name="vf_tcm_timers" id="myonoffswitch6" value="1" checked>
                                <label class="onoffswitch-label" for="myonoffswitch6">
                                    <span class="off" ><?php _e( 'Off','in-video-shopping-woocommerce' );?></span>
                                    <span class="onoffswitch-inner"></span>
                                    <span class="onoffswitch-switch"></span>
                                    <span class="on " ><?php _e( 'On','in-video-shopping-woocommerce' );?></span>
                                </label>
                            </div>
                        </td>
                    </tr>

                </table>
            </div>
        </div>








        <input type="hidden" name="vf_action" value="add">
        <input type="hidden" name="vf_video_theme_id" value="">

        <input type="submit" name="vf_submit" value="Save theme" class="button-primary">
    </form>

</div>
<div id="popup1" style=" display: none; width:500px; margin-left:400px; background-color:white; border:1px solid blue; padding:20px; position: absolute;z-index: 1;" >
    <div>
        <a class="close" href="#" style="padding-right: 10px; padding-top: 5px; float: right; color: red;"><strong>X</strong></a>
    </div>
    <div>
        <!--<h2 style="color: black;">VF</h2>-->
        <span >Data saved successcully</span>
        <!--<span><a class="yes clicked_yes" href="javascript:;" style="padding-right: 5px; padding-top: 5px;">YES</a></span>
        <span class="no clicked_no" >NO</span> -->
    </div>

</div>
<style>
    .vjs-fade-in,.vjs-fade-out {
        visibility: visible !important;
        opacity: 1 !important;
        transition-duration: 0s!important;
    }
</style>


<div class="vf_player_container">
    <video id="example_video_1"  class="video-js vjs-default-skin" controls preload="none" width="640" height="264"
           poster="http://video-js.zencoder.com/oceans-clip.png"
           data-setup="{}">
        <source src="http://video-js.zencoder.com/oceans-clip.mp4" type='video/mp4' />
        <source src="http://video-js.zencoder.com/oceans-clip.webm" type='video/webm' />
        <source src="http://video-js.zencoder.com/oceans-clip.ogv" type='video/ogg' />
    </video>

</div>
</div>
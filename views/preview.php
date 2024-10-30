<?php
if($vf_video_id){
    if( 'draft' == $vf_status ){
        echo"<div class='vf_video_banner'><a href='#'>Your video is not published yet</a></div>";
    }
    $iframe = "<iframe src='//player.video-force.com/v1.0/?id=$vf_video_id' allowtransparency='true' frameborder='0' scrolling='no' allowfullscreen mozallowfullscreen webkitallowfullscreen oallowfullscreen msallowfullscreen width='$dimensions[width]' height='$dimensions[height]'> </iframe>";
    ?>



    <?php echo $iframe; ?>
   




    <table class="video-info" >
        <tr>
            <td class="first-column"><span class="heading-title"><?php _e( 'Iframe','in-video-shopping-woocommerce' );?></span></td>
            <td colspan="3">


            </td>
        </tr>
        <tr>
            <td class="first-column">
                <div>
                    <a class="css-tooltip-top color-green" title="">
                        <span>
                            <p><?php _e( 'Copy the iframe code and paste it wherever you wish to show the video' );?></p>
                        </span>?
                    </a>
                </div>
                <label><?php _e( 'Copy Iframe','in-video-shopping-woocommerce' );?> </label>
            </td>
            <td colspan="3">

                <textarea rows=8 cols=80 id="vf_copy_src"><?php echo $iframe; ?></textarea>
                <div ><a class="vf_video_copy" href="#" id="copy">copy</a></div>
            </td>
        </tr>
    </table>





<?php }?>
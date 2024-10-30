<?php
 if  ( ! defined ( 'ABSPATH' ) ) { 
    exit; // Exit if accessed directly
} 
?>
<div class="wrap">
    <h2>Translations</h2>
    <form action="http://<?php echo $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"] ?>" method="POST" id="" name="vf_trans_form">	
        <table >
            <tr>
                <td><?php _e ( 'Add to cart','in-video-shopping-woocommerce' );?></td>
                <td>   <input class="vf_trans" name="trans[add_to_cart]" type="text"  value="<?php echo  ( !empty ( $lang_meta['add_to_cart'] ) )?$lang_meta['add_to_cart']:'';?>" placeholder="Add to cart"  /></td>
            </tr>
            <tr>
                <td><?php _e ( 'Select box default value','in-video-shopping-woocommerce' );?></td>
                <td>   <input class="vf_trans" name="trans[select]" type="text"  value="<?php echo  ( !empty ( $lang_meta['select'] ) )?$lang_meta['select']:'';?>" placeholder="Select"  /></td>
            </tr>
            <tr>
                <td><?php _e ( 'Checkout button ','in-video-shopping-woocommerce' );?></td>
                <td>   <input class="vf_trans" name="trans[checkout]" type="text"  value="<?php echo  ( !empty ( $lang_meta['checkout'] ) )?$lang_meta['checkout']:'';?>" placeholder="Checkout"  /></td>
            </tr>
            <tr>
                <td><?php _e ( 'Cart overlay title','in-video-shopping-woocommerce' );?></td>
                <td>   <input class="vf_trans" name="trans[cart_title]" type="text"  value="<?php echo  ( !empty ( $lang_meta['cart_title'] ) )?$lang_meta['cart_title']:'';?>" placeholder="Cart"  /></td>
            </tr>
            <tr>
                <td><?php _e ( 'Empty cart title','in-video-shopping-woocommerce' );?></td>
                <td>   <input class="vf_trans" name="trans[empty_cart_title]" type="text"  value="<?php echo  ( !empty ( $lang_meta['empty_cart_title'] ) )?$lang_meta['empty_cart_title']:'';?>" placeholder="Your cart is empty"  /></td>
            </tr>
            <tr>
                <td><?php _e ( 'Empty cart message','in-video-shopping-woocommerce' );?></td>
                <td>   <input class="vf_trans" name="trans[empty_cart_message]" type="text"  value="<?php echo  ( !empty ( $lang_meta['empty_cart_message'] ) )?$lang_meta['empty_cart_message']:'';?>"  placeholder="Your cart is empty"  /></td>
            </tr>
            <tr>
                <td><?php _e ( 'Unpublished video','in-video-shopping-woocommerce' );?></td>
                <td>   <input class="vf_trans" name="trans[unpublished_msg]" type="text"  value="<?php echo  ( !empty ( $lang_meta['unpublished_msg'] ) )?$lang_meta['unpublished_msg']:'';?>" placeholder="Video is currently unavailable"  /></td>
            </tr>

            <tr>
                <td>
                    <input type="hidden" name="do_translate" value="true">

                    <?php if ( isset ( $translations['id'] ) ){?>
                        <input type="hidden" name="vf_lang_id" value="<?php echo $translations['id']?>">
                        <?php }?>

                    <input type="submit" name="vf_submit" value="Save Translation" class="button-primary">
                </td>

            </tr>
        </table>
    </form>
</div>
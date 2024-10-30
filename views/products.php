<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
?>
<div><a href="javascript:;" class="button-small button vf_add_more"><?php _e( 'Add product','in-video-shopping-woocommerce' );?></a></div>
<div class="vf_new_cloner" style="display:none;">
    <table class="vf_cloneable" >
        <tr>
            <td valign="top" class="first-column">
                <div>
                    <a class="css-tooltip-top color-green" title="">
                        <span>
                            <p><?php _e( 'Select the WooCommerce product you wish to add to the video','in-video-shopping-woocommerce' );?></p>
                        </span>?
                    </a>
                </div>
                <label class="vf_prod_label"><?php _e( 'Woocommerce product','in-video-shopping-woocommerce' );?> </label>
            </td>
            <td colspan="3">
                <select name="vf_wc_product_id[]" class="vf_select_prod"  required>
                    <option selected disabled> <?php _e( 'Select product','in-video-shopping-woocommerce' );?></option>
                    <?php
                    // print_r( $products->products );
                    if( !empty( $products ) )
                        foreach( $products->products as $each_product ){
                            //$selected = ( $each_vf_product_new['vf_wc_product_id'] == $each_product->id )?'selected':'';
                            $desc = strip_tags( $each_product->short_description );
                            $desc = substr( $desc, 0, 300 );
                            ?>
                            <option value="<?php echo $each_product->id;?>"  data-title="<?php echo $each_product->title; ?>" data-desc="<?php echo $desc;?>" data-image="<?php echo $each_product->featured_src; ?>"><?php echo $each_product->title;?></option>
                        <?php }?>
                </select>


                <a href="javascript:;" class="button-small button vf_remove"><?php _e( 'Remove product','in-video-shopping-woocommerce' );?></a>

            </td>
        </tr>
        <tr>
            <td class="first-column">
                <div>
                    <a class="css-tooltip-top color-green" title="">
                        <span>
                            <p><?php _e( 'Choose a start and end time for your product to be visible inside the video','in-video-shopping-woocommerce' );?></p>
                        </span>?
                    </a>
                </div>
                <label ><?php _e( 'Show','in-video-shopping-woocommerce' );?> </label>

            </td>
            <td colspan="3">
                <div class="dimension-boxes fromtime-box">
                    <span class="width"><?php _e( 'Start *','in-video-shopping-woocommerce' );?></span><input type="text" name="vf_from_time[]" value="" class="widefat " required><b>Sec</b></div>


                <div class="dimension-boxes">
                    <span class="width"><?php _e( 'End *','in-video-shopping-woocommerce' );?></span><input type="text" name="vf_to_time[]" value="" class="widefat " required ><b>Sec</b></div></td>
        </tr>
      
        <tr>
            <td class="first-column ">
                <div>
                    <a class="css-tooltip-top color-green" title="">
                        <span>
                            <p><?php _e( 'Choose where the product should appear in your video.','in-video-shopping-woocommerce' );?></p>
                        </span>?
                    </a>
                </div>
                <label ><?php _e( 'product image Position ','in-video-shopping-woocommerce' );?> </label>

            </td>
            <td colspan="3">
                <select name="vf_position[]"  required>

                    <?php

                    if( !empty( $this->display_positions ) )
                        foreach( $this->display_positions as $key => $each_position ){
                            // $selected = ( $each_vf_product_new['vf_position'] == $key )?'selected':'';
                            ?>
                            <option value="<?php echo $key;?>">

                                <?php echo $each_position;?></option>
                        <?php }?>

                </select>
            </td>
        </tr>
    </table>
    <hr class="border-devider">
</div>
<div class="vf_cloneable_data">

    <?php

    if( !empty( $product_details ) ){

        foreach( $product_details as $each_vf_product ){
            //print_r( $each_vf_product );
            ?>


            <table class="vf_cloneable" >
                <tr>
                    <td valign="top" class="first-column">
                        <div>
                            <a class="css-tooltip-top color-green" title="">
                        <span>
                            <p><?php _e( 'Choose where the product should appear in your video.','in-video-shopping-woocommerce' );?></p>
                        </span>?
                            </a>
                        </div>
                        <label class="vf_prod_label"><?php _e( 'Woocommerce product','in-video-shopping-woocommerce' );?> </label>
                    </td>
                    <td colspan="3">
                        <select name="vf_wc_product_id[]" class="vf_select_prod"  required>
                            <option disabled> <?php _e( 'Select product','in-video-shopping-woocommerce' );?></option>
                            <?php
                            // print_r( $products->products );
                            if( !empty( $products ) )
                                foreach( $products->products as $each_product ){
                                    $selected = ( $each_vf_product['vf_wc_product_id'] == $each_product->id )?'selected':'';
                                    $desc = strip_tags( $each_product->short_description );
                                    $desc = substr( $desc, 0, 300 );
                                    ?>
                                    <option <?php echo $selected;?> value="<?php echo $each_product->id;?>"  data-title="<?php echo $each_product->title; ?>" data-desc="<?php echo $desc;?>" data-image="<?php echo $each_product->featured_src; ?>"><?php echo $each_product->title;?></option>
                                <?php }?>
                        </select>


                        <a href="javascript:;" class="button-small button vf_remove"><?php _e( 'Remove product','in-video-shopping-woocommerce' );?></a>
                        <?php if( $each_vf_product['vf_wc_product_id'] ){
                            if( !empty( $products ) )
                                foreach( $products->products as $each_product ){
                                    if( $each_vf_product['vf_wc_product_id'] == $each_product->id ){
                                        ?>
                                        <div class="vf_product_main_container">
                                            <div class="vf_product">
                                                <div class="vf_col1">
                                                    <div class="vf_image">
                                                        <img src="<?php echo $each_product->featured_src; ?>" alt="<?php echo $each_product->title?>" title="<?php echo $each_product->title?>">
                                                    </div>
                                                </div>
                                                <div class="vf_col2">
                                                    <div class="vf_title"><?php echo $each_product->title?></div>
                                                    <div class="vf_description"><?php
                                                        $desc = strip_tags( $each_product->short_description );
                                                        echo substr( $desc, 0, 300 );?>...</div>

                                                </div>
                                            </div>
                                        </div>
                                    <?php }
                                }
                        }?>
                    </td>
                </tr>
                <tr>
                    <td  class="first-column">
                    <div>
                        <a class="css-tooltip-top color-green" title="">
                        <span>
                            <p><?php _e( 'Choose a start and end time for your product to be visible inside the video','in-video-shopping-woocommerce' );?></p>
                        </span>?
                        </a>
                    </div>
                    <label ><?php _e( 'Show','in-video-shopping-woocommerce' );?> </label>

                    </td>
                    <td colspan="3">
                        <div class="dimension-boxes fromtime-box">
                            <span class="width"><?php _e( 'Start *','in-video-shopping-woocommerce' );?></span><input type="text" name="vf_from_time[]" value="<?php echo $each_vf_product['vf_from_time']; ?>" class="widefat " required><b>Sec</b></div>


                        <div class="dimension-boxes">
                            <span class="width"><?php _e( 'End *','in-video-shopping-woocommerce' );?></span><input type="text" name="vf_to_time[]" value="<?php echo $each_vf_product['vf_to_time']; ?>" class="widefat " required ><b>Sec</b></div></td>
                </tr>
              
                <tr>
                    <td class="first-column ">
                        <div>
                            <a class="css-tooltip-top color-green" title="">
                        <span>
                            <p><?php _e( 'Choose where the product should appear in your video.','in-video-shopping-woocommerce' );?></p>
                        </span>?
                            </a>
                        </div>
                        <label ><?php _e( 'product image Position ','in-video-shopping-woocommerce' );?> </label>

                    </td>
                    <td colspan="3">
                        <select name="vf_position[]"  required>

                            <?php

                            if( !empty( $this->display_positions ) )
                                foreach( $this->display_positions as $key => $each_position ){
                                    $selected = ( $each_vf_product['vf_position'] == $key )?'selected':'';
                                    ?>
                                    <option <?php echo $selected; ?> value="<?php echo $key;?>">

                                        <?php echo $each_position;?></option>
                                <?php }?>

                        </select>
                    </td>
                </tr>
            </table>
            <hr class="border-devider">
        <?php

        }

    }else{?>

        <table class="vf_cloneable">
            <tr>
                <td  class="first-column ">
                    <div>
                        <a class="css-tooltip-top color-green" title="">
                        <span>
                            <p><?php _e( 'Choose where the product should appear in your video.','in-video-shopping-woocommerce' );?></p>
                        </span>?
                        </a>
                    </div>
                    <label class><?php _e( 'Woocommerce product','in-video-shopping-woocommerce' );?> </label>
                </td>
                <td colspan="3">
                    <select name="vf_wc_product_id[]" class="vf_select_prod"  required>
                        <option value="0" selected disabled> <?php _e( 'Select product','in-video-shopping-woocommerce' );?></option>
                        <?php
                        if( !empty( $products ) )
                            foreach( $products->products as $each_product ){
                                $selected = ( $each_vf_product['vf_wc_product_id'] == $each_product->id )?'selected':'';
                                $desc = strip_tags( $each_product->short_description );
                                $desc = substr( $desc, 0, 300 );
                                ?>
                                <option <?php echo $selected;?> value="<?php echo $each_product->id;?>"  data-title="<?php echo $each_product->title; ?>" data-desc="<?php echo $desc;?>" data-image="<?php echo $each_product->featured_src; ?>"><?php echo $each_product->title;?></option>
                            <?php }?>
                    </select>
                    <a href="javascript:;" class="button-small button vf_remove"><?php _e( 'Remove product','in-video-shopping-woocommerce' );?></a>
                    <div class="vf_product_main_container" style="display: none;">
                        <div class="vf_product">
                            <div class="vf_col1">
                                <div class="vf_image"></div>
                            </div>
                            <div class="vf_col2">
                                <div class="vf_title"></div>
                                <div class="vf_description"></div>

                            </div>
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td  class="first-column">
                    <div>
                        <a class="css-tooltip-top color-green" title="">
                        <span>
                            <p><?php _e( 'Choose a start and end time for your product to be visible inside the video','in-video-shopping-woocommerce' );?></p>
                        </span>?
                        </a>
                    </div>
                    <label ><?php _e( 'Show','in-video-shopping-woocommerce' );?> </label>

                </td>
                <td colspan="3">
                    <div class="dimension-boxes fromtime-box">
                        <span class="width"><?php _e( 'Start *','in-video-shopping-woocommerce' );?></span><input type="text" name="vf_from_time[]" value="" class="widefat " required ><b>Sec</b> </div>

                    <div class="dimension-boxes">
                        <span class="width"><?php _e( 'End *','in-video-shopping-woocommerce' );?></span><input type="text" name="vf_to_time[]"  value=""  class="widefat " required /><b>Sec</b></div></td>
            </tr>

            <tr>
                <td class="first-column ">
                    <div>
                        <a class="css-tooltip-top color-green" title="">
                        <span>
                            <p><?php _e( 'Choose where the product should appear in your video.','in-video-shopping-woocommerce' );?></p>
                        </span>?
                        </a>
                    </div>
                    <label ><?php _e( 'product image Position ','in-video-shopping-woocommerce' );?> </label>

                </td>
                <td colspan="3">
                    <select name="vf_position[]"  required>

                        <?php if( !empty( $this->display_positions ) )
                            foreach( $this->display_positions as $key => $each_position ){
                                $selected = ( $each_vf_product['vf_position'] == $key )?'selected':'';
                                ?>
                                <option  required <?php echo $selected; ?> value="<?php echo $key;?>"><?php echo $each_position;?></option>
                            <?php }?>

                    </select>
                </td>
            </tr >

        </table>
        <hr class="border-devider">


    <?php }?>
</div>

<?php //print_r($this->video_details);?>
<div>
    <div><span><?php echo $this->video_details['view_count']?></span>
        <?php  _e( 'Views', 'in-video-shopping-woocommerce' )?>
    </div>
    <div>
        <span><?php echo $this->video_details['click_count']?></span>
        <?php  _e( 'Clicks', 'in-video-shopping-woocommerce' )?>
    </div>
    <hr>
    <div>

        <?php  _e( 'Remaining clicks', 'in-video-shopping-woocommerce' )?> - <span><?php echo ($this->video_details['access_limit'] + $this->video_details['package_limit'] + $this->video_details['free_limit']) - ($this->video_details['access_cnt'] + $this->video_details['package_cnt'] + $this->video_details['free_cnt']); ?></span>

        <?php if(0 == $this->video_details['access_limit']){?>
            <p>
                <a href="//www.video-force.com/plugin-pricing" target="_blank" class="vf-btn"><?php  _e( 'Buy a Subscription', 'in-video-shopping-woocommerce' )?></a>
            </p>
        <?php }elseif($this->video_details['access_limit'] > 0 && 0 == $this->video_details['package_limit']){?>
            <p>
                <a href="//www.video-force.com/plugin-clicks" target="_blank" class="vf-btn"><?php  _e( 'Buy more Clicks', 'in-video-shopping-woocommerce' )?></a>
            </p>
        <?php }?>
    </div>
</div>
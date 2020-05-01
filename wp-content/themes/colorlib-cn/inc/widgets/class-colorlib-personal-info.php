<?php
/**
 * 热门新闻小工具
 *
 */
class Colorlib_personal_info extends WP_Widget {

    public function __construct()
    {

        $widget_ops = array(
            'classname'   => 'colorlib_personal_info',
            'description' => '个人信息展示',
        );

        parent::__construct('colorlib_personal_info', '个人信息展示', $widget_ops );
    }

    public function widget($args, $instance)
    {
        $email =  isset( $instance['email'] ) ? $instance['email'] : '' ;
        $profession         = isset( $instance['profession'] ) ? $instance['profession'] : '';
        $skill         = isset( $instance['skill'] ) ? $instance['skill'] : '';
        $weixin         = isset( $instance['weixin'] ) ? $instance['weixin'] : '';

        echo $args['before_widget'];
        ?>
        <div class="person_info">
            <div class="avatar_1"><?= get_avatar($email,60) ?></div>
            <span><?php  $user = get_user_by('email',$email) ;
                echo $user->display_name;?></span>
        </div>
        <div class="person_info_1">
            <ul>
                <li><?= $profession; ?></li>
                <li><?= $skill; ?></li>
                <li> 微信: <?= $weixin; ?></li>
            </ul>
        </div>


        <?php

        echo $args['after_widget'];

    }


    public function form($instance)
    {
        if ( ! isset( $instance['email'] ) ) {
            $instance['email'] = '';
        }

        if ( ! isset( $instance['profession'] ) ) {
            $instance['profession'] = '';
        }
        if ( ! isset( $instance['skill'] ) ) {
            $instance['skill'] = '';
        }

        if ( ! isset( $instance['weixin'] ) ) {
            $instance['weixin'] = '';
        }

        ?>


        <p><label  for="<?php echo $this->get_field_id( 'email' ); ?>"><?php echo  esc_html('邮箱'); ?></label>

            <input  type="text" value="<?php echo esc_attr( $instance['email'] ); ?>"
                    name="<?php echo $this->get_field_name( 'email' ); ?>"
                    id="<?php $this->get_field_id( 'email' ); ?>"
                    class="widefat" />
        </p>

        <p><label  for="<?php echo $this->get_field_id( 'weixin' ); ?>"><?php echo  esc_html('微信号'); ?></label>

            <input  type="text" value="<?php echo esc_attr( $instance['weixin'] ); ?>"
                    name="<?php echo $this->get_field_name( 'weixin' ); ?>"
                    id="<?php $this->get_field_id( 'weixin' ); ?>"
                    class="widefat" />
        </p>

        <p><label  for="<?php echo $this->get_field_id( 'profession' ); ?>"><?php echo  esc_html('职业'); ?></label>

            <input  type="text" value="<?php echo esc_attr( $instance['profession'] ); ?>"
                    name="<?php echo $this->get_field_name( 'profession' ); ?>"
                    id="<?php $this->get_field_id( 'profession' ); ?>"
                    class="widefat" />
        </p>

        <p><label  for="<?php echo $this->get_field_id( 'skill' ); ?>"><?php  echo  esc_html('技能');  ?></label>

            <textarea
                    name="<?php echo $this->get_field_name( 'skill' ); ?>"
                    id="<?php $this->get_field_id( 'skill' ); ?>"
                    class="widefat" ><?php echo esc_attr( $instance['skill'] ); ?></textarea>
        <p>



        <?php
    }


}
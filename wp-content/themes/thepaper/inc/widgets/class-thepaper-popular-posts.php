<?php
/**
 * 热门新闻小工具
 *
 */
class Thepaper_popular_posts extends WP_Widget {

    public function __construct()
    {

        $widget_ops = array(
            'classname'   => 'thepaper-popular-posts',
            'description' =>  esc_html__('view new article','thepaper') ,
        );

        parent::__construct('thepaper-popular-posts', esc_html__('popular articles','thepaper') , $widget_ops );
    }

    public function widget($args, $instance)
    {
        $title         = isset( $instance['title'] ) ? $instance['title'] : esc_html__('popular articles','thepaper');
        $limit         = isset( $instance['limit'] ) ? $instance['limit'] : 10;

        echo $args['before_widget'];
        echo $args['before_title'];
        echo $title;
        echo $args['after_title'];
        ?>

        <ul class="postdate_1" id="">


            <?php

            $one = new WP_Query(

               array(
                   'posts_per_page'=>$limit,
                   'order' =>'DESC',
                    'orderby'=>'meta_value_num',
                    'meta_key' => 'views',
                    'meta_value' => 0,
                    'meta_compare' => '>'
                )

            );

            $index = 1;
            while ($one->have_posts()):
                $one->the_post();

                ?>
            <li>
                <span><?php echo $index  ?></span>
                <a href="<?php get_permalink(); ?>"><?php  the_title(); ?></a>
            </li>

            <?php
                $index ++;
                endwhile;

            wp_reset_query();

            ?>

        </ul>


        <?php

        echo $args['after_widget'];

    }


    public function form($instance)
    {
        if ( ! isset( $instance['title'] ) ) {
            $instance['title'] = esc_html__('popular articles','thepaper');
        }
        if ( ! isset( $instance['limit'] ) ) {
            $instance['limit'] = 5;
        }

        ?>

        <p><label  for="<?php echo $this->get_field_id( 'title' ); ?>"><?php echo  esc_html_('Headings','thepaper'); ?></label>

            <input  type="text" value="<?php echo esc_attr( $instance['title'] ); ?>"
                    name="<?php echo $this->get_field_name( 'title' ); ?>"
                    id="<?php $this->get_field_id( 'title' ); ?>"
                    class="widefat" />
        </p>

        <p><label  for="<?php echo $this->get_field_id( 'limit' ); ?>"><?php  echo  esc_html('article numbers','thepaper');  ?></label>

            <input  type="text" value="<?php echo esc_attr( $instance['limit'] ); ?>"
                    name="<?php echo $this->get_field_name( 'limit' ); ?>"
                    id="<?php $this->get_field_id( 'limit' ); ?>"
                    class="widefat" />
        <p>



        <?php
    }


}
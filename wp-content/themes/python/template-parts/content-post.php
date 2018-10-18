
<div style="margin-bottom: 20px">
    <v-hover>
        <v-card
            slot-scope="{ hover }"
            :class="`elevation-${hover ? 5 : 2}`"
        >
            <div class="item">
                 <div class="item-1">
                     <div class="item-1-image">
                        <?php
                        the_post_thumbnail('post-thumbnail',array('class' => 'item-post-thumbnail'));
                        ?>
                     </div>
                     <div class="item-2-title">
                         <h2><a href="<?= get_permalink(); ?>" target="_blank"><?php the_title(); ?></a></h2>
                         <p class="con">
                             <?=  get_the_excerpt();?>
                         </p>
                     </div>
                 </div>

                <div class="intro">
                    <p class="">阅读&nbsp;<span class="read_num"><?php get_post_views(get_the_ID()) ?></span></p>
                    <p class="">评论&nbsp;<span class="comment_num"><?= get_comments_number(get_the_ID())?></span></p>
                    <p class="">收藏&nbsp;<span class="collect_num">0</span></p>
                    <p class="">发布于&nbsp;<span class="collect_num"><?= timeago( get_gmt_from_date(get_the_time('Y-m-d G:i:s')) ); ?></span></p>
                    <p style="display:none;" class="admire_num_p">赞赏&nbsp;<span
                            class="admire_num"></span></p>
                    <div class="tags">
                        <? the_tags(null,'',null); ?>
                    </div>
                </div>

            </div>
        </v-card>
    </v-hover>
</div>

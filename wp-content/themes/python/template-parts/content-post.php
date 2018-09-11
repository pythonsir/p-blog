
<div style="margin-bottom: 20px">
    <v-hover>
        <v-card
            slot-scope="{ hover }"
            :class="`elevation-${hover ? 12 : 2}`"
        >
            <div class="item">

                <h2><a href="<?= get_permalink(); ?>" target="_blank"><?php the_title(); ?></a></h2>
                <p class="con">
                    <?=  get_the_excerpt();?>
                </p>
                <div class="intro">
<!--                    <a class="jing" href="http://blog.51cto.com/artcommend" target="_blank">-->
<!--                     -->
<!--                    </a>-->


                    <p class="">阅读&nbsp;<span class="read_num">1750</span></p>

                    <p class="">评论&nbsp;<span class="comment_num">0</span></p>
                    <p class="">收藏&nbsp;<span class="collect_num">0</span></p>
                    <p class="">发布于&nbsp;<span class="collect_num">4天前</span></p>
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

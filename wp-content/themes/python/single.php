<?php
get_header();
?>

<v-container v-cloak>

    <v-layout row justify-center>
        <v-flex md8>

            <?php
                while (have_posts()):
            the_post();


                ?>

            <div class="artical-Left-blog">
                <div class="status">
                    <a class="tab_name original">原创</a>
                </div>
                <h1 class="artical-title"><? the_title() ?></h1>
                <div class="artical-title-list">
                    <div class="is-vip-bg-6 fl">
                        <a  class="a-img" target="_blank"><img class="is-vip-img is-vip-img-4" data-uid="13709825" src="<?=  get_template_directory_uri().'/images/user.png' ?>"></a>
                    </div>
                    <a href="http://blog.51cto.com/13719825" class="name fl" target="_blank">pythonsir</a>
                    <a class="comment comment-num fr"><font class="comment_number">3</font>人评论</a>
                    <span class="fr"></span>
                    <a href="javascript:;" class="read fr">568人阅读</a>
                    <a href="javascript:;" class="time fr">2018-09-11 12:01:07</a>
                    <div class="clear"></div>
                </div>
                <div class="artical-content-bak main-content editor-side-new">
                    <div class="article-content">
                        <?php the_content();?>
                    </div>
                </div>
                <div class="artical-copyright mt26">©著作权归作者所有：来自颜色库博主 Pythonsir 的原创作品，如需转载，请注明出处，否则将追究法律责任</div>
            </div>

                    <div class="for-tag mt26">
                        <? the_tags('','',null); ?>
                        <div class="clear"></div>
                    </div>

                    <?php


                    if ( comments_open() || get_comments_number() ) {
                        comments_template();
                    }

                endwhile;
            ?>

            <v-fab-transition>
                <v-btn
                    transition="scale-transition"
                    dark
                    fab
                    right
                    fixed
                    bottom
                    color="red"
                    @click="$vuetify.goTo('#app', options)"
                    v-show="visiable"
                >
                    <v-icon>keyboard_arrow_up</v-icon>
                </v-btn>
            </v-fab-transition>

        </v-flex>

    </v-layout>


</v-container>




<?php
get_sidebar();
get_footer();
?>

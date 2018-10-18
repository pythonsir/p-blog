<?php
get_header();
?>
<script>
    var page1 = <?php
        global $paged;
        echo $paged?>;
    var pageNum = <?php
        global $wp_query;
        echo $wp_query->max_num_pages;
        ?>;

</script>
<div id="app">
    <v-app id="inspire">
        <?php get_template_part("template-parts/toolbars") ?>
        <v-content>
            <v-container grid-list-md text-xs-center v-cloak>
                <v-layout row wrap>
                    <v-flex md9>

                        <?php if (have_posts()):

                            while (have_posts()):
                                the_post();

                                get_template_part('template-parts/content', get_post_type());

                            endwhile;



                        endif;




                        ?>



                        <!--                        <v-pagination-->
                        <!--                            v-model="page"-->
                        <!--                            :length="pageNum"-->
                        <!--                            v-on:next="gotoNext"-->
                        <!--                            v-on:previous="gotoPre"-->
                        <!--                            v-on:input="gopage"-->
                        <!--                        ></v-pagination>-->

                    </v-flex>
                    <v-flex md3>
                        <v-layout column>
                            <v-flex>
                                <v-card>
                                    <div class="introduce">
                                        <span></span>
                                        <h2>站长简介</h2><span></span>
                                    </div>
                                    <v-avatar
                                        :size="80"
                                        color="grey lighten-4"
                                    >
                                        <v-img src="<?= get_template_directory_uri() . '/images/user.png' ?>"></v-img>
                                    </v-avatar>
                                    <v-card-title>
                                        <div class="useinfo">
                                            <h3 class="headline mb-0">Pythonsir</h3>
                                            <div>老夫敲代码就是一把梭,拿起键盘就是干!</div>
                                        </div>
                                    </v-card-title>

                                    <v-expansion-panel class="skill" v-cloak>
                                        <v-expansion-panel-content
                                            v-for="(item,i) in skills"
                                            :key="i">
                                            <div slot="header">{{ item.title }}</div>
                                            <v-card>
                                                <v-card-text class="grey lighten-3">

                                                    <a v-if="item.url != undefined" :href="item.url"
                                                       target="_blank">{{item.info}}</a>
                                                    <a v-else href="javascript:void();">{{item.info}}</a>

                                                </v-card-text>
                                            </v-card>
                                        </v-expansion-panel-content>
                                    </v-expansion-panel>

                                </v-card>
                            </v-flex>
                        </v-layout>
                    </v-flex>
                </v-layout>
            </v-container>
        </v-content>
    </v-app>
</div>
<?php
get_sidebar();
get_footer();
?>

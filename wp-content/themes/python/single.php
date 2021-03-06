<?php
get_header();

set_post_views ();

?>
<script>
    var post_id = <?php the_ID();?>

    var is_user_logged_in = <?= is_user_logged_in()? 'true':'false'; ?>

</script>
<div id="app">
    <v-app id="inspire">
        <?php get_template_part("template-parts/toolbars") ?>
        <v-content>
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
                                        <a class="a-img" target="_blank">
                                            <img class="is-vip-img is-vip-img-4"
                                                                              data-uid="13709825"
                                                                              src="<?= get_template_directory_uri() . '/images/user.png' ?>"></a>
                                    </div>
                                    <a href="" class="name fl"
                                       target="_blank">pythonsir</a>
                                    <a class="comment comment-num fr" style="margin-left: 4px;"><font class="comment_number">
                                            <?php
                                           echo get_comments_number() ?></font>人评论</a>
                                    <span class="fr"></span>
                                    <a href="javascript:;" class="read fr"><?php get_post_views(get_the_ID())?>人阅读</a>
                                    <a href="javascript:;" class="time fr">2018-09-11 12:01:07</a>
                                    <div class="clear"></div>
                                </div>
                                <div class="artical-content-bak main-content editor-side-new">
                                    <div class="article-content">
                                        <?php the_content(); ?>
                                    </div>
                                </div>
                                <div id="content" class="artical-copyright mt26">©著作权归作者所有：来自colorlib.cn Pythonsir
                                    的原创作品，如需转载，请注明出处，否则将追究法律责任
                                </div>
                            </div>

                            <div class="for-tag mt26">
                                <?php the_tags('', '', null); ?>
                                <div class="clear"></div>
                            </div>

<!--                          分页start  -->

    <?php the_post_navigation( array('next_text' =>'<span class="post-title">%title</span><v-btn color="primary" icon small dark><v-icon>chevron_right</v-icon></v-btn>','prev_text' =>'<v-btn color="primary" icon small dark><v-icon>chevron_left</v-icon></v-btn><span class="post-title">%title</span>'
                            ) ); ?>


<!--                            分页end-->


                            <div id="comments" class="comments-area normal-comment-list">

                                <div class="comment-loading" v-show="!commentFlag">
                                    <v-progress-circular
                                        :size="50"
                                        color="primary"
                                        indeterminate
                                    ></v-progress-circular>
                                </div>


                                <div v-show="commentFlag">
                                    <div class="submitpl" >
                                        <v-avatar color="grey lighten-4" >

                                            <?php
                                                if(!is_user_logged_in()){

                                                ?>

                                <img src="<?= get_template_directory_uri() . '/images/default_avatar.png' ?>" alt="avatar">

                                            <?php
                                                }else{
                                                    $current_user = wp_get_current_user();

                                                  echo  $imageurl = get_avatar($current_user->user_email);


                                                }

                                            ?>


                                        </v-avatar>


                                            <?php  if(!is_user_logged_in()) {

                                                ?>
                                        <div class="submit-container">
                                                <div class="login">
                                                    <v-btn color="primary" @click="gotoLogin">登录</v-btn><span> 后发表评论</span>
                                                </div>
                                        </div>
                                            <?php
                                            }else{

                                                ?>

                                                <div  class="submit-container-content">
                                                    <span v-show="reply.flag">正在回复 <a>{{reply.reply_user}}</a></span>
                                                    <div class="submit-container-content-1">
                                                        <textarea ref="textarea" v-model="reply.content"></textarea>
                                                        <div>
                                                            <v-btn ref="tj" @click="newComm" color="primary" :disabled="!btnflag" :large="true"><v-icon>reply</v-icon>提交</v-btn>
                                                            <v-btn outline color="indigo" :large="true" @click="clearComm"><v-icon  >clear</v-icon>清除</v-btn>

                                                        </div>
                                                    </div>

                                                </div>

                                            <?php

                                            }

                                            ?>



                                    </div>
                                    <div>

                                        <div class="top-title"><span>{{ total }}条评论</span></div>


                                    </div>
                                    <ul class="comment-list">
                                        <li  v-for="(item, index) in comments.lists" :key="index" class="comment ">
                                            <div  class="comment-body">
                                                <div class="comment-author vcard">
                                                    <div class="comment-image-warper" v-html="item.avatar"></div>
                                                    <div class="comment-info">
                                                        <div class="comment-info-1"><a class="fn" >{{item.comment_author}}</a></div>
                                                        <div class="comment-meta commentmetadata">
                                                            <a> {{item.comment_index}}楼 · {{item.comment_date}} </a>&nbsp;&nbsp;
                                                        </div>
                                                    </div>
                                                </div>
                                                <p v-html="item.comment_content"></p>
                                                <div class="reply" @click="replyComment_p0($vuetify,item)">

                                                    <v-icon :small="true">chat_bubble_outline</v-icon>
                                                    <a>  回复</a></div>
                                                <div class="fdseiw" @click="viewComm(index)" v-show="!item.children_flag" v-if="item.children.length > 0"><h4 >查看所有 {{item.children.length}} 条回复 </h4><v-icon>keyboard_arrow_up</v-icon></div>
                                                <div class="fdseiw" @click="hiddenComm(index)" v-show="item.children_flag" v-if="item.children.length > 0"><h4>隐藏回复</h4><v-icon>keyboard_arrow_down</v-icon></div>
                                                <div v-show="item.children_flag" v-if="item.children.length > 0" class="sub-comment-list">
                                                    <div  v-for="(item_1,index) in item.children" class="sub-comment">
                                                        <div class="sub-comment-1">
                                                            <div  class="sub-comment-1-2" >
                                                                    <a class="" href="javascript:;" target="_blank">{{item_1.comment_author}}：</a>
                                                                    <a href="javascript:;" target="_blank"> @{{item_1.p_comment_author}}</a>
                                                                    <span>{{item_1.comment_content}}</span>
                                                            </div>
                                                        </div>
                                                        <div class="sub-tool-group">
                                                            <span>{{item_1.comment_date}}</span>
                                                            <a @click="replyComments($vuetify,item_1,item)" class="javascript:;"><v-icon :small="true">chat_bubble_outline</v-icon>
                                                                <span>回复</span></a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>

                                    </ul>


                                    <div v-show="total > 0" class="text-xs-center" v-cloak>
                                        <v-pagination
                                            v-model="currpage"
                                            :length="pagenum"
                                            :total-visible="7"
                                            circle
                                        ></v-pagination>
                                    </div>
                                    <div v-show="total == 0">
                                        <div style="text-align: center;">暂无评论!</div>
                                    </div>


                                </div>
                            </div>


                            <?php



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
                                color="primary"
                                @click="$vuetify.goTo('#app', options)"
                                v-show="visiable"
                            >
                                <v-icon>keyboard_arrow_up</v-icon>
                            </v-btn>
                        </v-fab-transition>

                        <v-snackbar
                            v-model="snackbar"

                            :bottom="true"
                        >
                            {{message}}

                        </v-snackbar>


                    </v-flex>

                </v-layout>


            </v-container>
        </v-content>
    </v-app>
</div>
<?php
get_footer();
?>

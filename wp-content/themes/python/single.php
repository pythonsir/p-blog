<?php
get_header();
?>
<script>
    var post_id = <? the_ID();?>
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
                                        <a class="a-img" target="_blank"><img class="is-vip-img is-vip-img-4"
                                                                              data-uid="13709825"
                                                                              src="<?= get_template_directory_uri() . '/images/user.png' ?>"></a>
                                    </div>
                                    <a href="http://blog.51cto.com/13719825" class="name fl"
                                       target="_blank">pythonsir</a>
                                    <a class="comment comment-num fr"><font class="comment_number">
                                            <?php
                                            get_comment_count(the_ID()) ?></font>人评论</a>
                                    <span class="fr"></span>
                                    <a href="javascript:;" class="read fr">568人阅读</a>
                                    <a href="javascript:;" class="time fr">2018-09-11 12:01:07</a>
                                    <div class="clear"></div>
                                </div>
                                <div class="artical-content-bak main-content editor-side-new">
                                    <div class="article-content">
                                        <?php the_content(); ?>
                                    </div>
                                </div>
                                <div class="artical-copyright mt26">©著作权归作者所有：来自颜色库博主 Pythonsir
                                    的原创作品，如需转载，请注明出处，否则将追究法律责任
                                </div>
                            </div>

                            <div class="for-tag mt26">
                                <? the_tags('', '', null); ?>
                                <div class="clear"></div>
                            </div>


                            <div id="comments" class="comments-area normal-comment-list">
                                <div>
                                    <div class="submitpl">
                                        <v-avatar color="grey lighten-4" >

                                            <?php
                                                if(!is_user_logged_in()){

                                                ?>
                                                    <img src="<?= get_template_directory_uri() . '/images/avatar_default.png' ?>" alt="avatar">

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
                                                    <v-btn color="info" @click="gotoLogin">登录</v-btn><span> 后发表评论</span>
                                                </div>
                                        </div>
                                            <?php
                                            }else{

                                                ?>

                                                <textarea class="submit-container">

                                                </textarea>
                                                <div>
                                                    <v-btn color="success">提交</v-btn>
                                                    <v-btn color="success">取消</v-btn>
                                                </div>


                                            <?php

                                            }

                                            ?>



                                    </div>
                                    <div>
                                        <div class="top-title"><span><?php
                                                get_comment_count(the_ID()) ?>条评论</span></div>
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
                                                <p>{{item.comment_content}}</p>
                                                <div class="reply"><v-icon :small="true">chat_bubble_outline</v-icon><a >  回复</a></div>
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
                                                            <a class="javascript:;"><v-icon :small="true">chat_bubble_outline</v-icon>
                                                                <span>回复</span></a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>

                                    </ul>
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
        </v-content>
    </v-app>
</div>
<?php
get_footer();
?>

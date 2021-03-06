<?php

if ( 'POST' != $_SERVER['REQUEST_METHOD'] ) {
    $protocol = $_SERVER['SERVER_PROTOCOL'];
    if ( ! in_array( $protocol, array( 'HTTP/1.1', 'HTTP/2', 'HTTP/2.0' ) ) ) {
        $protocol = 'HTTP/1.0';
    }

    header('Allow: POST');
    header("$protocol 405 Method Not Allowed");
    header('Content-Type: text/plain');
    exit;
}

/** Sets up the WordPress Environment. */
require( dirname(__FILE__) . '/wp-load.php' );

nocache_headers();

$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : 'login';

/**
 * 根据post id 获取文章评论列表
 * @param $post_id
 */
function commentslist($post_id){
    global $wpdb;

    if (!(isset($_REQUEST['page']) && isset($_REQUEST['pageSize']))){

        echo json_encode(array('ret' => 500,'message'=>"系统参数错误!"));
        exit(500);
    }


    $total = $wpdb->get_row($wpdb ->prepare(" 
    select COUNT(t.comment_ID) as total  FROM
 $wpdb->comments t 
   WHERE t.comment_post_ID = %d
 ",$post_id) );

    $where = $wpdb->prepare(" LEFT JOIN $wpdb->comments p ON t.comment_parent = p.comment_ID
WHERE t.comment_post_ID = %d  and t.comment_parent = 0
ORDER BY t.comment_ID DESC ",$post_id);

    $page = ($_REQUEST['page'] - 1) * (int)$_REQUEST['pageSize'];

    $pageSize = $_REQUEST['pageSize'];



    $count = $wpdb->get_row(" 
    select COUNT(t.comment_ID) as total  FROM
 $wpdb->comments t 
 {$where}
 ");

    $pagenums = ceil($count->total / $_REQUEST['pageSize']);



    $_comments = $wpdb->get_results( " 
 SELECT
	t.comment_ID,t.comment_author,t.comment_date,t.comment_parent,t.comment_content,t.user_id,p.comment_author as p_comment_author
FROM
 $wpdb->comments t {$where} limit {$page},{$pageSize}");

    $result = array();


    foreach ($_comments as $comment){
        $children = getLevel($comment->comment_ID );
        $result[] = array(
            "comment_index" => get_comment_meta($comment->comment_ID,'comment_index',true),
            "comment_ID" => $comment->comment_ID,
            "comment_author" => $comment->comment_author,
            "comment_date"=> $comment->comment_date,
            "comment_content" => $comment->comment_content,
            "user_id" => $comment->user_id,
            "p_comment_author" => $comment->p_comment_author,
            "avatar" => get_avatar( $comment->user_id, 38),
            "children" => $children,
            "children_flag" => false
        );

    }


    $ret = array('page' => (int)$_REQUEST['page'],'total'=>$total->total,'pagenum'=>$pagenums,'lists'=>$result);


    echo json_encode($ret);

}

/**
 * 获取二级的子评论列表
 * @param $parentId 评论父id
 * @param $post_id  文章id
 */
function getLevel($parentId){

    global $wpdb;

    $_comments = $wpdb->get_results( $wpdb->prepare( "

SELECT
	t.comment_ID,t.comment_author,t.comment_date,t.comment_karma,t.comment_content,t.user_id,p.comment_author as p_comment_author
FROM
	$wpdb->comments t
LEFT JOIN $wpdb->comments p ON t.comment_parent = p.comment_ID
WHERE    t.comment_karma = %d and t.comment_parent > 0
ORDER BY t.comment_ID  

", $parentId));

    $result = array();

    foreach ($_comments as $comment){
        $result[] = array(
            "comment_ID" => $comment->comment_ID,
            "comment_author" => $comment->comment_author,
            "comment_date"=> $comment->comment_date,
            "comment_content" => $comment->comment_content,
            "user_id" => $comment->user_id,
            "p_comment_author" => $comment->p_comment_author,
            "avatar" => get_avatar( $comment->user_id, 38)
        );

    }

    return $result;
}

/**
 * 添加评论
 */
function addComment(){

    if(!isset($_REQUEST['comment_post_ID'])){
        $result = array('ret' => 500,'message'=>'系统错误,请稍后重试!');
        echo json_decode($result);
        exit;
    }

    $number = get_comments_number($_REQUEST['comment_post_ID']);

    $number = (int)$number + 1;

    $comment = wp_handle_comment_submission( wp_unslash( $_POST ) );

    if ( is_wp_error( $comment ) ){

        $result = array('ret' => 500,'message'=>$comment->get_error_message());
        echo json_encode($result);
    }else{

        add_comment_meta($comment->comment_ID,'comment_index',$number,true);

        $result = array('ret' => 200,'message'=>'评论成功!');

        echo json_encode($result);
    }


}

switch ($action){

    case 'getComments':

        $post_id = isset($_REQUEST['id']) ? $_REQUEST['id'] : null;

        if($post_id){
            commentslist($post_id);
        }

        break;
    case 'addComment':
         addComment();
        break;
    default:
        break;


}
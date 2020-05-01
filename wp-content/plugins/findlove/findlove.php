<?php
/*
  Plugin Name: 脱单在太原 插件
  Plugin URI:
  Description: 脱单在太原
  Version: 1.0
  Author: pythonsir
  Author URI: https://colorlib.cn/
 */

define('FINDLOVE_URL', plugins_url('', __FILE__));
define('FINDLOVE_DIR', plugin_dir_path(__FILE__));

date_default_timezone_set("PRC");


require 'vendor/autoload.php';

use EasyWeChat\Factory;

use Qiniu\Auth;

global $findlove;

$findlove = new FindLove();

class FindLove {

    public  $find_love_db_version = '1.1';

    private  $config = [
        'app_id' => 'wxb2e2951abeeeccbf',
        'secret' => 'bc0434b348c7097f9086baa2ed9cf776',
        // 下面为可选项
        // 指定 API 调用返回结果的类型：array(default)/collection/object/raw/自定义类名
        'response_type' => 'array',

    ];

    // 七牛cdn配置
    private $qiniu = [
        "accessKey" => "bOsorwTwkg0yFmeG633bGvjQH_llc85X9Xv1nips",
        "secretKey" => "Y5Pw-aNlIl4iimM9Z4NIfT2YupbKP40VjFn1680_"
    ];

    public $app;

    public $qiniu_auth;

    public $upToken;

    function  __construct(){

        $this->db_table_install();

        $this->app = Factory::miniProgram($this->config);

        add_action('admin_init',array($this,'add_find_love_role'));

        add_action('admin_init',array($this,'ajax_action_list'));

        $this->qiniu_auth = new Auth($this->qiniu['accessKey'],$this->qiniu['secretKey']);

        $this->upToken = $this->qiniu_auth->uploadToken("colorlib");

    }

    public function add_find_love_role(){

        add_role('findlove','findlove管理员',array('read'=>true,'findlove'=>true));

    }

    public function ajax_action_list(){

        add_action( 'wp_ajax_nopriv_get_session_3rd', array($this,'get_session_3rd') );

        add_action( 'wp_ajax_nopriv_get_up_token', array($this,'get_up_token') );

        add_action( 'wp_ajax_nopriv_get_first_by_session3rd', array($this,'get_first_by_session3rd') );

        add_action('wp_ajax_nopriv_save_first_user_info',array($this,'save_first_user_info'));

        add_action('wp_ajax_nopriv_save_second_user_info',array($this,'save_second_user_info'));

        add_action('wp_ajax_nopriv_get_second_user_info',array($this,'get_second_user_info'));

        add_action('wp_ajax_nopriv_save_three_user_info',array($this,'save_three_user_info'));

        add_action('wp_ajax_nopriv_get_banner',array($this,'get_banner'));

        add_action('wp_ajax_nopriv_get_curr_user_info',array($this,'get_curr_user_info'));

        add_action('wp_ajax_nopriv_get_user_list',array($this,'get_user_list'));

        add_action('wp_ajax_nopriv_get_db_user_detail',array($this,'get_db_user_detail'));
    }

    /**
     * 获取上传文件token
     */
    public function get_up_token(){
        echo json_encode(["ret" => 200,"uptoken" => $this->upToken]);
        wp_die();
    }

    private function add_cos_header(){

        header("Access-Control-Allow-Origin:*");
        header("Access-Control-Allow-Headers:content-type");
        header("Access-Control-Request-Method:GET,POST");
    }

    /**
     * 获取当前用户的个人信息
     */
    public function get_first_by_session3rd(){
        global $wpdb;

        try{

            $session_3rd = isset($_POST['session3rd'])?$_POST['session3rd']:'';

            if($session_3rd == ''){
                throw  new Exception("缺少参数,非法请求!");
            }

            $sql = $wpdb->prepare("SELECT
	u.*
FROM
	fd_user u
JOIN fd_cache c ON u.openid = c.c_value
WHERE
	c.id = %s ",array($session_3rd));

            $db_result = $wpdb->get_row($sql);

            if(is_null($db_result)){

                echo json_encode(["ret" =>201,"data" =>""]);
            }else{
                echo json_encode(["ret" => 200, "data"=> array(
                    "nickName"=>$db_result->nickName,
                    "avatar" => $db_result->avatar,
                    "sex" => $db_result->sex,
                    "birthday"=>$db_result->birthday,
                    "height"=>$db_result->height,
                    "education"=>$db_result->education,
                    "marital"=>$db_result->marital,
                    "career"=>$db_result->career,
                    "profession"=>$db_result->profession,
                    "havecar"=>$db_result->havecar,
                    "havehouse"=>$db_result->havehouse,
                    "zodiac"=>$db_result->zodiac,
                    "constellation"=>$db_result->constellation,
                    "livingArea"=>$db_result->livingArea,
                    "unitNature"=>$db_result->unitNature,
                    "province"=>$db_result->province,
                    "province_code"=>$db_result->province_code,
                    "city"=>$db_result->city,
                    "city_code"=>$db_result->city_code,
                    "annual_salary"=>$db_result->annual_salary,
                    "wx_avatar"=>$db_result->wx_avatar,
                    "wx_nickname"=>$db_result->wx_nickname
                ) ]);
            }

        }catch (Exception $e){

            echo json_encode(["ret" => 500,"message"=>$e->getMessage()]);
        }
        wp_die();

    }



    /**
     * 获取小程序session_key
     */
    public function get_session_3rd(){

        global $wpdb;

        $code = isset($_POST['code'])?$_POST['code']:'';

        if($code != ''){

            $res = $this->app->auth->session($code);

            if(isset($res['session_key']) && isset($res['openid'])){

                $cache_id = $this->get_random_str();

                $_flag = $wpdb->insert("fd_cache",array("id"=>$cache_id,"c_key"=>$res['session_key'],"c_value"=>$res['openid']));


                if(!$_flag){

                    echo json_encode(array('ret'=>202,'session3rd'=>''));

                }else{

                    echo json_encode(array('ret'=>200,'session3rd'=>$cache_id));
                }

            }else{

                echo json_encode(array('ret'=>201,'message'=>"api登录失败,请联系管理员!"));

            }

        }else{

            echo json_encode(array('ret'=>500,'message'=>"系统错误,请稍后再试!"));
        }
        wp_die();
    }

    //随机字符串
    function get_random_str( $length = 16 )
    {
        $str = substr(md5(time()), 0, $length);
        return $str;

    }


    /**
     * 保存用户数据 第一步
     */
    function save_first_user_info(){

        global $wpdb;

        try{

            $nickName = isset($_POST['nickName'])?$_POST['nickName']:'';

            if($nickName == ''){
                throw new Exception("用户昵称不能为空!");
            }

            $avatar = isset($_POST['avatar'])?$_POST['avatar']:'';

            if($avatar == ''){
                throw new Exception("用户头像不能为空!");
            }

            $sex = isset($_POST['sex'])?$_POST['sex']:'';

            if($sex == ''){
                throw  new Exception("用户性别不能为空!");
            }

            $birthday = isset($_POST['birthday'])?$_POST['birthday']:'';

            if($birthday == ''){
                throw  new Exception("生日不能为空");
            }

            $height = isset($_POST['height'])?$_POST['height']:'';

            if($height == ''){
                throw  new Exception("身高不能为空");
            }

            $education = isset($_POST['education'])?$_POST['education']:'';

            if($education == ''){
                throw  new Exception("学历不能为空");
            }

            $marital = isset($_POST['marital'])?$_POST['marital']:'';

            if($education == ''){
                throw  new Exception("婚姻状态不能为空");
            }

            $career = isset($_POST['career'])?$_POST['career']:'';

            if($career == ''){
                throw  new Exception("职业不能为空");
            }

            $profession = isset($_POST['profession'])?$_POST['profession']:'';

            if($profession == ''){
                throw  new Exception("行业不能为空");
            }

            $havecar = isset($_POST['havecar'])?$_POST['havecar']:'';

            if($havecar == ''){
                throw  new Exception("购车情况不能为空");
            }

            $havehouse = isset($_POST['havehouse'])?$_POST['havehouse']:'';

            if($havehouse == ''){
                throw  new Exception("购房情况不能为空");
            }

            $zodiac = isset($_POST['zodiac'])?$_POST['zodiac']:'';

            if($zodiac == ''){
                throw  new Exception("属相不能为空");
            }

            $constellation = isset($_POST['constellation'])?$_POST['constellation']:'';

            if($constellation == ''){
                throw  new Exception("星座不能为空");
            }

            $livingArea = isset($_POST['livingArea'])?$_POST['livingArea']:'';

            if($livingArea == ''){
                throw  new Exception("居住区域不能为空");
            }

            $unitNature = isset($_POST['unitNature'])?$_POST['unitNature']:'';

            if($unitNature == ''){
                throw  new Exception("单位性质不能为空");
            }

            $province = isset($_POST['province'])?$_POST['province']:'';

            $province_code = isset($_POST['province_code'])?$_POST['province_code']:'';

            $city = isset($_POST['city'])?$_POST['city']:'';

            $city_code = isset($_POST['city_code'])?$_POST['city_code']:'';

            if($province == '' || $city == ''){
                throw  new Exception("户籍不能为空");
            }

            $annual_salary = isset($_POST['annual_salary'])?$_POST['annual_salary']:'';

            if($annual_salary == '' ){
                throw  new Exception("年薪不能为空");
            }

            $session_key = isset($_POST['session_key'])?$_POST['session_key']:'';

            if($session_key == '' ){
                throw  new Exception("系统错误,请重启小程序!");
            }

             $sql = $wpdb->prepare("select * from fd_cache where id = %s ",array($session_key));

            $result = $wpdb->get_row($sql);

            if($result == null || !isset($result->c_value)){
                throw  new Exception("系统错误,请重启小程序!");
            }

            $openid = $result->c_value;


            $wx_nickname = isset($_POST['wx_nickname'])?$_POST['wx_nickname']:'';

            if($wx_nickname == '' ){
                throw  new Exception("系统错误,请重启小程序!");
            }

            $wx_avatar = isset($_POST['wx_avatar'])?$_POST['wx_avatar']:'';

            if($wx_avatar == '' ){
                throw  new Exception("系统错误,请重启小程序!");
            }

            $form_type = isset($_POST['type'])?$_POST['type']:'';

            if($form_type == ''){
                throw  new Exception("参数错误,非法请求!");
            }

            if($form_type == 'add'){

                $add_result = $wpdb->insert('fd_user',array('nickName'=>$nickName,'avatar'=>$avatar,'sex'=>$sex,'birthday'=>$birthday,'height'=>$height,'education'=>$education,'marital'=>$marital,'career'=>$career,'profession'=>$profession,'havecar'=>$havecar,'havehouse'=>$havehouse,'zodiac'=>$zodiac,'constellation'=>$constellation,'livingArea'=>$livingArea,'unitNature'=>$unitNature,'province'=>$province,'province_code'=>$province_code,'city'=>$city,'city_code'=>$city_code,'annual_salary'=>$annual_salary,'createdAt'=>date('Y-m-d H:i:s',time()),'openid'=>$openid,'wx_nickname'=>$wx_nickname,'wx_avatar'=>$wx_avatar));


                if($add_result){

                    echo  json_encode(array("ret" => 200,"message" => "保存成功!"));

                }else{

                    echo  json_encode(array("ret" => 201,"message" => "保存失败,请重试!"));
                }

            }

            if($form_type == 'editor'){


                $editor_result = $wpdb->update('fd_user',array('nickName'=>$nickName,'avatar'=>$avatar,'sex'=>$sex,'birthday'=>$birthday,'height'=>$height,'education'=>$education,'marital'=>$marital,'career'=>$career,'profession'=>$profession,'havecar'=>$havecar,'havehouse'=>$havehouse,'zodiac'=>$zodiac,'constellation'=>$constellation,'livingArea'=>$livingArea,'unitNature'=>$unitNature,'province'=>$province,'province_code'=>$province_code,'city'=>$city,'city_code'=>$city_code,'annual_salary'=>$annual_salary,'updatedAt'=>date('Y-m-d H:i:s',time()),'wx_nickname'=>$wx_nickname,'wx_avatar'=>$wx_avatar),array('openid'=>$openid));


                echo  json_encode(array("ret" => 200,"message" => "编辑成功!"));

            }


        }catch (Exception $e){

            echo json_encode(array('ret'=>500,'message'=>$e->getMessage()));

        }

        die();


    }

    function db_table_install(){
        global $wpdb;

        $installed_ver = get_option("find_love_db_version");

        if($installed_ver == $this->find_love_db_version){

            return;
        }

        $fl_user =  'fd_user';

        $charset_collate = $wpdb->get_charset_collate();

        $sql_fl_user ="CREATE TABLE fd_user (
  id int(6) NOT NULL AUTO_INCREMENT,
  nickName varchar(255)   NOT NULL,
  avatar varchar(255)   NOT NULL,
  sex int(1) NOT NULL,
  birthday date NOT NULL,
  height int(4) DEFAULT NULL,
  education varchar(255)   DEFAULT NULL,
  marital varchar(255)   DEFAULT NULL,
  career varchar(255)   DEFAULT NULL,
  profession varchar(255)   DEFAULT NULL,
  havecar varchar(255)   DEFAULT NULL,
  havehouse varchar(255)   DEFAULT NULL,
  referrer int(6) DEFAULT NULL,
  zodiac varchar(255)   DEFAULT NULL,
  constellation varchar(255)   DEFAULT NULL,
  livingArea varchar(255)   DEFAULT NULL,
  unitNature varchar(255)   DEFAULT NULL,
  province varchar(255)   DEFAULT NULL,
  province_code varchar(255)   DEFAULT NULL,
  city varchar(255)   DEFAULT NULL,
  city_code varchar(255)   DEFAULT NULL,
  annual_salary varchar(255)   DEFAULT NULL,
  createdAt date DEFAULT NULL,
  updatedAt date DEFAULT NULL,
  openid varchar(255)   DEFAULT NULL,
  wx_avatar varchar(255)   DEFAULT NULL,
  wx_nickname varchar(255)   DEFAULT NULL,
  is_finish int(1) DEFAULT '0',
  PRIMARY KEY (id)
) AUTO_INCREMENT=10000 $charset_collate;";

        $sql_fd_banner = "CREATE TABLE fd_banner (
  id int(5) NOT NULL AUTO_INCREMENT,
  banner_name varchar(255) DEFAULT NULL,
  image_url varchar(255) DEFAULT NULL,
  is_use int(1) DEFAULT '0',
  PRIMARY KEY (id)
) AUTO_INCREMENT=1 $charset_collate;";

        $sql_fd_cache = "CREATE TABLE fd_cache (
   id varchar(32) NOT NULL,
   c_key varchar(255) NOT NULL,c_value varchar(255) NOT NULL,
   PRIMARY KEY (id)
) $charset_collate;";

        $sql_fd_usermeta = "CREATE TABLE fd_usermeta (
  umeat_id int(6) NOT NULL AUTO_INCREMENT,
  openid varchar(255) DEFAULT NULL,
  meta_key varchar(255) DEFAULT NULL,
  meta_value varchar(255) DEFAULT NULL,
  PRIMARY KEY (umeat_id)
) $charset_collate;";


        require_once(ABSPATH . "wp-admin/includes/upgrade.php");

        dbDelta($sql_fl_user);

        dbDelta($sql_fd_cache);
//
        dbDelta($sql_fd_banner);
//
        dbDelta($sql_fd_usermeta);

        add_option( 'find_love_db_version', $this->find_love_db_version );
    }

    /**
     * 保存兴趣爱好和标签
     */
    function save_second_user_info(){
        global $wpdb;

        try{


            $type = isset($_POST['type'])?$_POST['type']:'';

            if($type == '' ){
                throw  new Exception("缺少参数,非法请求!");
            }


            $forum = isset($_POST['forum'])?$_POST['forum']:'';

            if($forum == '' ){
                throw  new Exception("请填写个人宣言!");
            }

            $select_tag = isset($_POST['select_tag'])?$_POST['select_tag']:'';

            if($select_tag == '' ){
                throw  new Exception("请选择个人标签!");
            }



            $session_key = isset($_POST['session_key'])?$_POST['session_key']:'';

            if($session_key == '' ){
                throw  new Exception("参数错误,非法请求!");
            }


            $sql = $wpdb->prepare("select * from fd_cache where id = %s ",array($session_key));

            $result = $wpdb->get_row($sql);

            if($result == null || !isset($result->c_value)){
                throw  new Exception("系统错误,请重启小程序!");
            }

            $openid = $result->c_value;

            if($type == 'add'){

                $wpdb->insert("fd_usermeta",array("openid" => $openid,"meta_key"=>"forum","meta_value"=>$forum));

                $wpdb->insert("fd_usermeta",array("openid" => $openid,"meta_key"=>"select_tag","meta_value"=>$select_tag));

            }else if($type == 'editor'){

                $wpdb->update("fd_usermeta",array("meta_value"=>$forum),array("openid" => $openid,"meta_key"=>"forum"));

                $wpdb->update("fd_usermeta",array("meta_value"=>$select_tag),array("openid" => $openid,"meta_key"=>"select_tag"));

            }

            echo json_encode(array("ret"=>200,"message" => "保存成功!"));

        }catch (Exception $e){

            echo json_decode(array("ret"=>500,"message"=>$e->getMessage()));

        }
        wp_die();

    }

    /**
     * 获取当前用户的兴趣爱好
     * @throws Exception
     */
    function get_second_user_info(){
        global $wpdb;

        try{

            $session_key = isset($_POST['session_key'])?$_POST['session_key']:'';

            if($session_key == '' ){
                throw  new Exception("参数错误,非法请求!");
            }

            $sql = $wpdb->prepare("select * from fd_cache where id = %s ",array($session_key));

            $result = $wpdb->get_row($sql);

            if($result == null || !isset($result->c_value)){
                throw  new Exception("系统错误,请重启小程序!");
            }

            $openid = $result->c_value;

            $sql = $wpdb->prepare("select * from fd_usermeta where openid = %s",array($openid));

            $result = $wpdb->get_results($sql);


            $res = array("forum"=>"","select_tag" =>[]);

            foreach ($result as $_result){


                if($_result->meta_key == 'forum'){

                    $res['forum'] = $_result->meta_value;

                }else if($_result->meta_key == 'select_tag'){

                    $arr = explode(",",$_result->meta_value);

                    $res['select_tag'] = $arr;

                }

            }

            echo json_encode(["ret"=>200,"result" => $res]);



        }catch (Exception $e){

            echo json_encode(array("ret" => 500,"message"=>$e->getMessage()));
        }

        wp_die();

    }

    /**
     *  第三步,保存系统
     */
    function save_three_user_info(){

        global $wpdb;

        try{

            $session_key = isset($_POST['session_key'])?$_POST['session_key']:'';

            if($session_key == '' ){
                throw  new Exception("参数错误,非法请求!");
            }

            $memberid = isset($_POST['memberid'])?$_POST['memberid']:'';

            $sql = $wpdb->prepare("select * from fd_cache where id = %s ",array($session_key));

            $result = $wpdb->get_row($sql);

            if($result == null || !isset($result->c_value)){
                throw  new Exception("系统错误,请重启小程序!");
            }

            $openid = $result->c_value;

            $result = $wpdb->update("fd_user",array("referrer"=>$memberid,"is_finish"=>1),array("openid" => $openid,"is_finish"=>0));

            if($result){
                echo json_encode(array("ret"=>200,"message" => "保存成功!"));
            }else{
                throw new Exception("保存失败!");
            }

        }catch (Exception $e){

            echo json_encode(array("ret"=>500,"message" => "保存成功!"));

        }

        wp_die();

    }

    /**
     * 获取banner图片
     */
    function get_banner(){
        global $wpdb;

        try{

            $sql = $wpdb->prepare("select * from fd_banner where is_use = %d ",array(1));

            $row = $wpdb->get_row($sql);

            if(is_null($row)){

                throw new Exception("无banner图");

            }else{

                $this->add_cos_header();
                echo json_encode(["ret" => 200,"image_url"=>$row->image_url]);

            }

        }catch (Exception $e){

            $this->add_cos_header();
            echo json_encode(["ret"=>200,"message"=>$e->getMessage()]);

        }

        wp_die();

    }

    /**
     * 获取当前用户信息
     */
    function get_curr_user_info(){
        global $wpdb;

        try{

            $session_key = isset($_POST['session_key'])?$_POST['session_key']:'';

            if($session_key == '' ){
                throw  new Exception("参数错误,非法请求!");
            }

            $sql = $wpdb->prepare("select * from fd_cache where id = %s ",array($session_key));

            $result = $wpdb->get_row($sql);

            if($result == null || !isset($result->c_value)){
                throw  new Exception("系统错误,请重启小程序!");
            }

            $openid = $result->c_value;

            $sql_1 = $wpdb->prepare("select * from fd_user where openid = %s and is_finish = %d ",array($openid,1));

            $query = $wpdb->get_row($sql_1);

            if(is_null($query)){

                echo json_encode(["ret" => 201,"message" => "无查询结果"]);

            }else{
                echo json_encode(["ret" => 200,"message" => "查询结果返回"]);
            }


        }catch (Exception $e){

            echo json_encode(["ret"=>500,"message"=>$e->getMessage()]);

        }

        wp_die();

    }


    /**
     * 获取已注册用户列表
     */
    function get_user_list(){
        global $wpdb;

        try{

            $page =  isset($_POST['page'])?$_POST['page']:1;

            $pageSize = isset($_POST['pagesize'])?$_POST['pagesize']:20;

            $sql = $wpdb->prepare("select * from fd_user where is_finish = %d limit %d , %d ",array(2,($page - 1) * $pageSize,$pageSize));

            $result = $wpdb->get_results($sql);

            $res = [];

            if(is_null($result)){

                echo json_encode(['ret' => 201, 'message'=>"无数据"]);
            }else{

                foreach ($result as $_result){

                    $_t = array("id"=>$_result->id,"nickName" => $_result->nickName,"avatar" => $_result->avatar,"sex" => $_result->sex == 0?"女":"男","age"=> $this->birthday($_result->birthday),
                        "livingArea" => $_result->livingArea
                        );

                    $res[] = $_t;

                }

                echo json_encode(["ret" => 200, "list" =>$res ]);

            }


        }catch (Exception $e){

            echo json_encode(["ret" => 500, "message" =>"系统错误!" ]);

        }
        wp_die();

    }

    /**
     * 获取用户详细信息
     */
    function  get_db_user_detail(){

        global $wpdb;

        try{

            $session_key = isset($_POST['session_key'])?$_POST['session_key']:'';

            if($session_key == '' ){
                throw  new Exception("参数错误,非法请求!");
            }

            $sql = $wpdb->prepare("select * from fd_cache where id = %s ",array($session_key));

            $result = $wpdb->get_row($sql);

            if($result == null || !isset($result->c_value)){
                throw  new Exception("系统错误,请重启小程序!");
            }

            $openid = $result->c_value;

            $query = $wpdb->prepare("select * from fd_user where openid = %s and is_finish = %d ",array($openid,2));

            $row = $wpdb->get_row($query,ARRAY_A);


            $sql = $wpdb->prepare("select * from fd_usermeta where openid = %s",array($openid));

            $result = $wpdb->get_results($sql);


            $res = array("forum"=>"","select_tag" =>[]);

            foreach ($result as $_result){


                if($_result->meta_key == 'forum'){

                    $res['forum'] = $_result->meta_value;

                }else if($_result->meta_key == 'select_tag'){

                    $arr = explode(",",$_result->meta_value);

                    $res['select_tag'] = $arr;

                }

            }





        }catch (Exception $e){

            echo json_encode(["ret"=>500,"message"=>$e->getMessage()]);
        }

        wp_die();

    }

    /**
     * 计算年龄
     * @param $birthday
     * @return bool|int
     */
    private function birthday($birthday){
        $age = strtotime($birthday);
        if($age === false){
            return false;
        }
        list($y1,$m1,$d1) = explode("-",date("Y-m-d",$age));
        $now = strtotime("now");
        list($y2,$m2,$d2) = explode("-",date("Y-m-d",$now));
        $age = $y2 - $y1;
        if((int)($m2.$d2) < (int)($m1.$d1))
            $age -= 1;
        return $age;
    }

}
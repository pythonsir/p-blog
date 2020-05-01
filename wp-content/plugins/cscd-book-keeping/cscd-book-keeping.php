<?php
/*
  Plugin Name: 8081牙膏记账分成插件
  Plugin URI:
  Description: 记账插件,计算各个门诊的医生或者护士的提成
  Version: 1.0
  Author: pythonsir
  Author URI: https://colorlib.cn/
 */

define('CSCDBOOKKING_URL', plugins_url('', __FILE__));
define('CSCDBOOKKING_DIR', plugin_dir_path(__FILE__));

date_default_timezone_set("PRC");


global $cscd_bk;

$cscd_bk = new CscdBookKeeping();

class CscdBookKeeping{

    public  $bookkeeping_db_version = '1.0';


    function  __construct(){


        $this->db_table_install();

        add_action('admin_init',array($this,'add_cscd_role'));

        add_action('admin_init',array($this,'change_admin_page'));

        add_action('admin_init',array($this,'display_my_menu'));

        add_action('admin_init',array($this,'ajax_action'));

        add_action('admin_menu',array($this,'add_cscd_book_keeping_menu'));

        add_action('admin_enqueue_scripts', array($this, 'load_styles'));



    }


    function add_cscd_role(){

        add_role('bookkeeping','记账管理员',array('read'=>true,'doctor-list'=>true,'manage_book_list'=>true,'hz_list'=>true));

    }

    function change_admin_page(){
        global $plugin_page;

        if(current_user_can("manage_book_list")){

            if(!isset($_REQUEST['page'])){

                $url = admin_url().'admin.php?page=cscd-book-keeping';

                wp_redirect($url);
                exit;

            }
        }
    }


    function ajax_action(){

        add_action( 'wp_ajax_add_sell', array($this,'add_sell') );

        add_action( 'wp_ajax_get_sell_list', array($this,'get_sell_list'));

        add_action( 'wp_ajax_delete_sell', array($this,'delete_sell'));

        add_action('wp_ajax_editor_sell',array($this,'editor_sell'));

        add_action('wp_ajax_get_zh_sell_list',array($this,'get_zh_sell_list'));

        add_action('wp_ajax_add_doctor',array($this,'add_doctor'));

        add_action('wp_ajax_get_doctor_list',array($this,'get_doctor_list'));

        add_action('wp_ajax_editor_doctor',array($this,'editor_doctor'));

        add_action('wp_ajax_delete_doctor',array($this,'delete_doctor'));


    }


    /**
     * 添加提出记录
     */
    function add_sell() {
        global $wpdb; // this is how you get access to the database

        $doctor_id = isset($_POST['doctor_id'])?$_POST['doctor_id']:'';

        $doctor_name =  isset($_POST['doctor_name'])?$_POST['doctor_name']:'';

        $price_num =  isset($_POST['price_num'])?$_POST['price_num']:'';

        $price =  isset($_POST['price'])?$_POST['price']:'';

        $total_price =  isset($_POST['total_price'])?$_POST['total_price']:'';

        $sell_numbers = isset($_POST['sell_numbers'])?$_POST['sell_numbers']:'';

        $sell_time = isset($_POST['sell_time'])?$_POST['sell_time']:'';

        $commission = isset($_POST['commission'])?$_POST['commission']:'';

        $wp_doctor_sell_list = $wpdb->prefix . 'doctor_sell_list';

        $create_time = date('Y-m-d H:i:s',time());

        try{

            $wpdb->insert($wp_doctor_sell_list,array('doctor_id'=>$doctor_id,'doctor_name'=>$doctor_name,
                'price'=>$price,'total_price'=>$total_price,'price_num'=>$price_num,'sell_numbers' => $sell_numbers,'sell_time' => $sell_time,'commission' => $commission
            ,'create_time'=>$create_time));

            $result = array('ret'=>200,'message'=>'保存成功!');

            echo json_encode($result);

        }catch (Exception $e){

            $result = array('ret'=>$e->getCode(),'message'=>$e->getMessage());

            echo json_encode($result);

        }

        wp_die(); // this is required to terminate immediately and return a proper response
    }


    /**
     * 获取医生的提出列表
     */
    function get_sell_list(){

        global $wpdb;

        $start_time = isset($_POST['start_time'])?$_POST['start_time']:'';

        $end_time = isset($_POST['end_time'])?$_POST['end_time']:'';

        $doctor_id = isset($_POST['doctor_id'])?$_POST['doctor_id']:'';

        $wp_doctor_sell_list = $wpdb->prefix . 'doctor_sell_list';

        $page =  isset($_POST['page'])?$_POST['page']:1;

        $pageSize = isset($_POST['limit'])?$_POST['limit']:10;

        $sql = " SELECT * FROM $wp_doctor_sell_list t where 1=1 ";

        if($doctor_id != ''){

            $sql .= " and t.doctor_id = $doctor_id ";
        }

        if ($start_time != '' && $end_time == ''){

            $sql .= " and t.sell_time >= '$start_time'";

        }

        if ($end_time !='' && $start_time == ''){

            $sql .= " and t.sell_time <= '$end_time' " ;
        }


        if($start_time !='' && $end_time != '' && $start_time != $end_time){
            $sql .= " and t.sell_time >= '$start_time' and t.sell_time <= '$end_time' ";
        }


        try{

            $count = $wpdb->query($sql);

            $sql .= ' limit ' . ($page - 1) * $pageSize . ' , ' . $pageSize ;

            $result = $wpdb->get_results($sql);

            $res = ["code"=>0,"msg"=>"","count"=>$count,"data"=>$result];

            echo  json_encode($res);

        }catch (Exception $e){

            $res = ["code"=>$e->getCode(),"msg"=>$e->getMessage(),"count"=>0,"data"=>null];

            echo  json_encode($res);

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
     * 获取汇总统计list
     */
    function get_zh_sell_list(){

        global $wpdb;

        $start_time = isset($_POST['start_time'])?$_POST['start_time']:'';

        $end_time = isset($_POST['end_time'])?$_POST['end_time']:'';

        $doctor_id = isset($_POST['doctor_id'])?$_POST['doctor_id']:'';

        $wp_doctor_sell_list = $wpdb->prefix . 'doctor_sell_list';

        $page =  isset($_POST['page'])?$_POST['page']:1;

        $pageSize = isset($_POST['limit'])?$_POST['limit']:10;

        $sql_count = " SELECT COUNT(*) FROM  $wp_doctor_sell_list t where 1=1 ";

        $sql = " SELECT doctor_name,
	SUM(sell_numbers) AS sell_numbers,
	SUM(total_price) AS total_price,
	 SUM(commission) AS commission
	 FROM $wp_doctor_sell_list t where 1=1 ";

        if($doctor_id != ''){

            $sql_count .= " and t.doctor_id = $doctor_id ";
            $sql .= " and t.doctor_id = $doctor_id ";
        }

        if ($start_time != '' && $end_time == ''){

            $sql_count .= " and t.sell_time >= '$start_time'";
            $sql .= " and t.sell_time >= '$start_time'";

        }

        if ($end_time !='' && $start_time == ''){

            $sql_count .= " and t.sell_time <= '$end_time' ";
            $sql .= " and t.sell_time <= '$end_time' " ;
        }


        if($start_time !='' && $end_time != '' && $start_time != $end_time){
            $sql_count .= " and t.sell_time >= '$start_time' and t.sell_time <= '$end_time' ";
            $sql .= " and t.sell_time >= '$start_time' and t.sell_time <= '$end_time' ";
        }

        $sql_count .= " GROUP BY doctor_id  ORDER BY total_price desc ";

        $sql .= ' GROUP BY doctor_id  ORDER BY total_price desc limit ' . ($page - 1) * $pageSize . ' , ' . $pageSize ;

        try{

            $count = $wpdb->query($sql_count);

            $result = $wpdb->get_results($sql);

            $res = ["code"=>0,"msg"=>"","count"=>$count,"data"=>$result];

            echo  json_encode($res);

        }catch (Exception $e){

            $res = ["code"=>0,"msg"=>$e->getMessage(),"count"=>$count,"data"=>$result];

            echo  json_encode($res);

        }

        wp_die();

    }


    function get_doctor_info_by_id($id){
        global $wpdb;

        $wp_doctor = $wpdb->prefix . 'doctor';

        $sql = $wpdb->prepare("select * from $wp_doctor where id = %d ",array($id));

        try{

            $res =  $wpdb->get_row($sql);

            return array("ret"=> 200, "data" => $res,"msg" => "");

        }catch (Exception $e){

            return array("ret" => 500, "msg" => $e->getMessage() );
        }
        wp_die();

    }

    function delete_doctor(){

        global $wpdb;

        $id = isset($_POST['id'])?$_POST['id']:'';

        $wp_doctor = $wpdb->prefix . 'doctor';


        $sql = "delete from $wp_doctor where id in (" . $id . ")";


        try{

            $flag =  $wpdb->query($sql);

            if($flag){
                echo json_encode(array("ret" => 200,"msg" => "删除记录成功!" ));
            }else{
                echo json_encode(array("ret" => 200,"msg" => "删除记录失败,请稍后再试!" ));
            }

        }catch (Exception $e){

            echo json_encode(array("ret" => 500,"msg" => $e->getMessage() ));
        }

        wp_die();

    }

    function editor_doctor(){

        global $wpdb;

        $id = isset($_POST['id']) ? $_POST['id']: null;

        $doctor_name = isset($_POST['doctor_name']) ? $_POST['doctor_name']: null;

        $shop_name = isset($_POST['shop_name']) ? $_POST['shop_name']: null;

        $shop_id = isset($_POST['shop_id']) ? $_POST['shop_id']: null;

        $wp_doctor = $wpdb->prefix . 'doctor';

        try{

            $wpdb->update($wp_doctor,array('doctor_name' => $doctor_name,'shop_name' => $shop_name,'shop_id' => $shop_id),array("id" => $id));

            echo json_encode(array("ret" => 200,"msg" => "编辑医生成功!"));

        }catch (Exception $e){

            echo json_encode(array("ret" => 500,"msg" => $e->getMessage() ));
        }
        wp_die();


    }


    function get_editor_info_by_id($id){

        global $wpdb;

        $wp_doctor_sell_list = $wpdb->prefix . 'doctor_sell_list';

        $sql = $wpdb->prepare("select * from $wp_doctor_sell_list where id = %d ",array($id));

        try{

            $res =  $wpdb->get_row($sql);

            return array("ret"=> 200, "data" => $res,"msg" => "");

        }catch (Exception $e){

            return array("ret" => 500, "msg" => $e->getMessage() );
        }

    }

    /**
     *  门店列表
     */
    function get_shop_list(){

        global $wpdb;

        $wp_shop = $wpdb->prefix . 'shop';

        $shops = $wpdb->get_results(" select shop_id,shop_name from $wp_shop");

        return $shops;

    }


    /**
     * 删除分红记录
     */
    function delete_sell(){

        global $wpdb;

        $id = isset($_POST['id'])?$_POST['id']:'';

        $wp_doctor_sell_list = $wpdb->prefix . 'doctor_sell_list';


        $sql = "delete from $wp_doctor_sell_list where id in (" . $id . ")";


        try{

          $flag =  $wpdb->query($sql);

            if($flag){
                echo json_encode(array("ret" => 200,"msg" => "删除记录成功!" ));
            }else{
                echo json_encode(array("ret" => 200,"msg" => "删除记录失败,请稍后再试!" ));
            }

        }catch (Exception $e){

            echo json_encode(array("ret" => 500,"msg" => $e->getMessage() ));
        }

        wp_die();


    }


    /**
     * 编辑分工数据
     */
    function editor_sell(){

        global $wpdb;

        $id = isset($_POST['id'])?$_POST['id']:'';

        $doctor_id = isset($_POST['doctor_id'])?$_POST['doctor_id']:'';

        $doctor_name =  isset($_POST['doctor_name'])?$_POST['doctor_name']:'';

        $price_num =  isset($_POST['price_num'])?$_POST['price_num']:'';

        $price =  isset($_POST['price'])?$_POST['price']:'';

        $total_price =  isset($_POST['total_price'])?$_POST['total_price']:'';

        $sell_numbers = isset($_POST['sell_numbers'])?$_POST['sell_numbers']:'';

        $sell_time = isset($_POST['sell_time'])?$_POST['sell_time']:'';

        $commission = isset($_POST['commission'])?$_POST['commission']:'';

        $wp_doctor_sell_list = $wpdb->prefix . 'doctor_sell_list';

        try{

            $wpdb->update($wp_doctor_sell_list,array("doctor_id" => $doctor_id,"doctor_name" => $doctor_name,'price'=>$price,"total_price" => $total_price,'price_num'=>$price_num,"sell_numbers" => $sell_numbers,"sell_time" => $sell_time,"commission" =>$commission,'create_time'=>date('Y-m-d H:i:s',time()) ),array("id" => $id));

            echo json_encode(array("ret" => 200, "msg" =>"编辑成功"));

        }catch (Exception $e){

            echo json_encode(array("ret" => 500, "msg" => $e->getMessage()));

        }

        wp_die();



    }




    function display_my_menu(){

        global $menu;

        $restricted = array(__('Dashboard'),__('User'));

        end($menu);

        if(current_user_can("manage_book_list")){

            while (prev($menu)){
                $value = explode(' ',$menu[key($menu)][0]);
                if(in_array($value[0] != NULL?$value[0]:"" , $restricted))
                {
                    unset($menu[key($menu)]);
                }
            }

        }
        remove_menu_page("profile.php");
    }


    function load_styles() {

        if(current_user_can("manage_book_list")){

            wp_enqueue_style('layui.css', plugins_url( 'assets/layui/css/layui.css', __FILE__ ), null, null, 'screen');

            wp_enqueue_style('cscd.css', plugins_url( 'assets/css/cscd.css', __FILE__ ), array('layui.css'), '201811281036', 'screen');


            wp_enqueue_script('layui.js',plugins_url('assets/layui/layui.js',__FILE__),array(),'2.4.5',true);

            wp_enqueue_script('cscd.js',plugins_url('assets/js/cscd.js',__FILE__),array('layui.js'),'201811281036',true);

        }

    }


    /**
     * select 需要数据集
     * @return array|null|object
     */
    function getDoctorList(){

        global $wpdb;

        $wp_doctor = $wpdb->prefix . 'doctor';

        $doctors = $wpdb->get_results(" select id,doctor_name from $wp_doctor ");

        return $doctors;
    }


    /**
     * table 需要数据集
     */
    function get_doctor_list(){

        global $wpdb;

        $doctor_name = isset($_POST['doctor_name']) ? $_POST['doctor_name']: '';

        $shop_id = isset($_POST['shop_id']) ? $_POST['shop_id']: '';

        $wp_doctor = $wpdb->prefix . 'doctor';

        $page =  isset($_POST['page'])?$_POST['page']:1;

        $pageSize = isset($_POST['limit'])?$_POST['limit']:10;



        $sql = "select * from $wp_doctor where 1=1 ";

        if($doctor_name != ''){

            $sql .= " and doctor_name like '%" . $doctor_name . "%'";
        }

        if($shop_id != ''){


            $sql .= " and shop_id = " . $shop_id ;

        }

        try{

            $count = $wpdb->query($sql);

            $sql .= ' limit ' . ($page - 1) * $pageSize . ' , ' . $pageSize ;

            $result = $wpdb ->get_results($sql);

            $res = ["code"=>0,"msg"=>"","count"=>$count,"data"=>$result];

            echo  json_encode($res);

        }catch (Exception $e){

            $res = ["code"=>$e->getCode(),"msg"=>$e->getMessage(),"count"=>0,"data"=>null];

            echo  json_encode($res);

        }

        wp_die();





    }



    function add_doctor(){

        global $wpdb;

        $doctor_name = isset($_POST['doctor_name']) ? $_POST['doctor_name']: null;

        $shop_name = isset($_POST['shop_name']) ? $_POST['shop_name']: null;

        $shop_id = isset($_POST['shop_id']) ? $_POST['shop_id']: null;

        $wp_doctor = $wpdb->prefix . 'doctor';

        try{

            $wpdb->insert($wp_doctor,array('doctor_name' => $doctor_name,'shop_name' => $shop_name,'shop_id' => $shop_id));

            echo json_encode(array("ret" => 200,"msg" => "添加医生成功!"));

        }catch (Exception $e){

            echo json_encode(array("ret" => 500,"msg" => $e->getMessage() ));
        }
        wp_die();

    }



    function db_table_install(){
        global $wpdb;

        $installed_ver = get_option("bookkeeping_db_version");

        if($installed_ver == $this->bookkeeping_db_version){

            return;
        }

        $wp_doctor_name = $wpdb->prefix . 'doctor';

        $wp_doctor_sell_list = $wpdb->prefix . 'doctor_sell_list';

        $wp_shop = $wpdb->prefix . 'shop';

        $charset_collate = $wpdb->get_charset_collate();

        $sql_1 ="CREATE TABLE $wp_doctor_name (
              id int(6) NOT NULL AUTO_INCREMENT,
              doctor_name tinytext NOT NULL,
              shop_id int(6) DEFAULT NULL,
              shop_name VARCHAR(255) DEFAULT NULL,
              PRIMARY KEY  (id)
              ) $charset_collate;";


        $sql_2 = "CREATE TABLE $wp_doctor_sell_list (
                id int(6) NOT NULL AUTO_INCREMENT,
                doctor_id int(6) NOT NULL,
                doctor_name tinytext NOT NULL ,
                price VARCHAR(20) NOT NULL ,
                total_price VARCHAR(20) NOT NULL ,
                price_num int(5) NOT NULL ,
                sell_numbers int(5) NOT NULL ,
                sell_time DATE NOT NULL ,
                commission int(5) NOT NULL ,
                create_time datetime NOT NULL ,
                PRIMARY KEY  (id)
          ) $charset_collate";

        $sql_3 = "CREATE TABLE $wp_shop (
                id int(6) NOT NULL AUTO_INCREMENT,
                shop_id int(6) NOT NULL,
                shop_name tinytext NOT NULL,
                create_time datetime NOT NULL,
                PRIMARY KEY  (id)       
          ) $charset_collate";

        require_once(ABSPATH . "wp-admin/includes/upgrade.php");
        dbDelta($sql_1);
        dbDelta($sql_2);
        dbDelta($sql_3);
        add_option( 'bookkeeping_db_version', $this->bookkeeping_db_version );
    }

    function add_cscd_book_keeping_menu(){

       add_menu_page("8018销售管理","8018销售管理","manage_book_list","cscd-book-keeping",array($this,'add_index_page'));

        add_submenu_page("cscd-book-keeping","医生提成管理","医生提成管理","manage_book_list","cscd-book-keeping",array($this,'add_index_page'));

        add_submenu_page("cscd-book-keeping","提成汇总管理","提成汇总管理","hz_list","hz_list",array($this,'add_hz_page'));


        add_submenu_page("cscd-book-keeping","医生管理","医生管理列表",'doctor-list','doctor-list',array($this,'add_doctor_page'));

    }

    function add_doctor_page(){

        require CSCDBOOKKING_DIR . 'doctor-list.php';
    }

    function add_index_page(){

        require CSCDBOOKKING_DIR . 'index.php';
    }

    function add_hz_page(){
        require CSCDBOOKKING_DIR . 'hz-list.php';
    }



}



function db_table_uninstall(){

    global $wpdb;


    remove_role("bookkeeping");

    delete_option("bookkeeping_db_version");

}

register_deactivation_hook(__FILE__,'db_table_uninstall');
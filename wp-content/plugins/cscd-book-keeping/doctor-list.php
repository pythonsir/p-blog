<?php

global $cscd_bk;

$action = isset($_GET['action']) ? $_GET['action'] : 'list';

if($action == 'list'):

?>

<div class="warp_list">
    <h1><?php echo esc_html('8018医生管理'); ?></h1>
    <div class="layui-fluid">
        <div class="layui-row">
            <form class="layui-form">
                <input type="hidden" name="action"  value="get_doctor_list">
                <div class="layui-inline">
                    <label class="layui-form-label">医生姓名</label>
                    <div class="layui-input-inline">
                        <input id="doctor_name" type="text" name="doctor_name"  class="layui-input">
                    </div>
                </div>
                <div class="layui-inline">
                    <label class="layui-form-label">门店名称</label>
                    <div class="layui-input-inline">
                        <select name="shop_id" lay-verType="tips" lay-search readonly  >
                            <option value=""><?php esc_html_e('请选择门店'); ?></option>
                            <?php

                            $shops = $cscd_bk->get_shop_list();
                                foreach ($shops as $shop){

                            ?>
                            <option value="<?php  echo  $shop->shop_id; ?>"><?php  echo $shop->shop_name?></option>
                            <?php
                                }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="layui-inline">
                    <button class="layui-btn"  lay-submit lay-filter="doctor_search">查询</button>
                </div>
            </form>
        </div>
        <div class="layui-row" style="margin-top: 10px;">
            <div class="layui-col-md12">
                <table id="doctor—list" lay-filter="doctor—list"></table>
            </div>
        </div>
    </div>

</div>

<?php
  endif;
    if($action == 'add'):
?>

        <div class="warp">
            <h1><?php echo esc_html('新增医生'); ?></h1>
            <div class="layui-fluid">
                <div class="layui-row">
                    <div class="layui-col-md6">
                        <form class="layui-form" action="">

                            <input type="hidden" name="action"  value="add_doctor">
                            <input type="hidden" id="shop_name"  name="shop_name" value="">
                            <div class="layui-form-item">
                                <label class="layui-form-label">请选择门店</label>
                                <div class="layui-input-inline">
                                    <select lay-filter="shop_id" name="shop_id" lay-verType="tips" lay-search lay-verify="required" >

                                        <option value=""><?php esc_html_e('请选择门店'); ?></option>
                                        <?php

                                        $shops = $cscd_bk->get_shop_list();
                                        foreach ($shops as $shop){

                                            ?>
                                            <option value="<?php  echo  $shop->shop_id; ?>"><?php  echo $shop->shop_name?></option>
                                            <?php
                                        }
                                        ?>

                                    </select>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">医生名称</label>
                                <div class="layui-input-inline">
                                    <input id="doctor_name" type="text" name="doctor_name" lay-verType="tips" required  lay-verify="required"  autocomplete="off" class="layui-input">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <div class="layui-input-block">
                                    <button id="add_doctor" class="layui-btn" lay-submit lay-filter="add_doctor">立即提交</button>
                                    <button type="reset" class="layui-btn layui-btn-primary">重置</button>
                                </div>
                            </div>
                        </form>
                    </div>

                </div>
            </div>

        </div>


<?php

 endif;

    if($action == 'editor'):

        $id = isset($_GET['id'])?$_GET['id']:NULL;


        if($id == NULL):

            ?>

            <div class="warp">

                <h3>请求参数不正确!</h3>

            </div>

            <?php

            exit;
        endif;


        $info = $cscd_bk->get_doctor_info_by_id($id);


        if($info['ret'] == 200):

            $data = $info['data'];


?>

        <div class="warp">
            <h1><?php echo esc_html('编辑医生'); ?></h1>
            <div class="layui-fluid">
                <div class="layui-row">
                    <div class="layui-col-md6">
                        <form class="layui-form" action="">

                            <input type="hidden" name="action"  value="editor_doctor">
                            <input type="hidden" name="id" value="<?php  echo $id;?>">
                            <input type="hidden" id="shop_name"  name="shop_name" value="<?php echo $data->shop_name;?>">
                            <div class="layui-form-item">
                                <label class="layui-form-label">请选择门店</label>
                                <div class="layui-input-inline">
                                    <select lay-filter="shop_id"  name="shop_id" lay-verType="tips" lay-search lay-verify="required" >

                                        <option value=""><?php esc_html_e('请选择门店'); ?></option>
                                        <?php

                                        $shops = $cscd_bk->get_shop_list();
                                        foreach ($shops as $shop){

                                            ?>
                                            <option <?php  if ($data->shop_id == $shop->shop_id): echo "selected"; endif; ?>  value="<?php  echo  $shop->shop_id; ?>"><?php  echo $shop->shop_name?></option>
                                            <?php
                                        }
                                        ?>

                                    </select>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">医生名称</label>
                                <div class="layui-input-inline">
                                    <input id="doctor_name" type="text" name="doctor_name" lay-verType="tips" required  lay-verify="required"  autocomplete="off" class="layui-input" value="<?php  echo $data->doctor_name;?>">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <div class="layui-input-block">
                                    <button id="editor_doctor" class="layui-btn" lay-submit lay-filter="editor_doctor">立即提交</button>
                                    <button type="reset" class="layui-btn layui-btn-primary">重置</button>
                                </div>
                            </div>
                        </form>
                    </div>

                </div>
            </div>

        </div>



<?php
        else:
            ?>

            <div class="warp">
                <h2><?php  esc_html_e($info['msg']) ?></h2>
            </div>

<?php

        endif;

    endif;

        ?>
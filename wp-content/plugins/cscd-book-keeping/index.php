<?php
/**
 * Created by PhpStorm.
 * User: python
 * Date: 2018/11/26
 * Time: 下午11:55
 */
global $cscd_bk;

$action = isset($_GET['action']) ? $_GET['action'] : 'list';

if ($action == 'list'):

    ?>
    <div class="warp_list">
        <h1><?php echo esc_html('8018销售管理'); ?></h1>
        <div class="layui-fluid">
            <div class="layui-row">
                <form class="layui-form">
                    <input type="hidden" name="action"  value="get_sell_list">
                    <div class="layui-inline">
                        <label class="layui-form-label">选择医生</label>
                        <div class="layui-input-block">
                            <select name="doctor_id" >
                                <option value=""><?php esc_html_e('请选择医生'); ?></option>

                                <?php

                                $doctors = $cscd_bk->getDoctorList();

                                foreach ($doctors as $doctor) {

                                    ?>

                                    <option
                                        value="<?php echo $doctor->id; ?>"><?php echo $doctor->doctor_name; ?></option>
                                    <?php

                                }

                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="layui-inline">
                        <label class="layui-form-label">开始日期</label>
                        <div class="layui-input-inline">
                            <input id="start" type="text" name="start_time" readonly class="layui-input">
                        </div>
                    </div>
                    <div class="layui-inline">
                        <label class="layui-form-label">结束日期</label>
                        <div class="layui-input-inline">
                            <input id="end" type="text" name="end_time" readonly class="layui-input">
                        </div>
                    </div>
                    <div class="layui-inline">
                        <button class="layui-btn"  lay-submit lay-filter="search">查询</button>
                    </div>
                </form>
            </div>
            <div class="layui-row" style="margin-top: 10px;">
                <div class="layui-col-md12">
                    <table id="sell—list" lay-filter="sell_list"></table>
                </div>
            </div>
        </div>

    </div>
    <?php
endif;

if ($action == 'add'):

    ?>

    <div class="warp">
        <h1><?php echo esc_html('新增销售记录'); ?></h1>
        <div class="layui-fluid">
            <div class="layui-row">
                <div class="layui-col-md6">
                    <form class="layui-form" action="">

                        <input type="hidden" name="action"  value="add_sell">
                        <input type="hidden" id="doctor_name"  name="doctor_name" value="">
                        <div class="layui-form-item">
                            <label class="layui-form-label">请选择医生</label>
                            <div class="layui-input-inline">
                                <select name="doctor_id" lay-verType="tips" lay-verify="required" lay-filter="doctor_id">
                                    <option value=""><?php esc_html_e('请选择医生'); ?></option>

                                    <?php

                                    $doctors = $cscd_bk->getDoctorList();

                                    foreach ($doctors as $doctor) {

                                        ?>

                                        <option
                                            value="<?php echo $doctor->id; ?>"><?php echo $doctor->doctor_name; ?></option>

                                        <?php

                                    }

                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="layui-form-item">
                            <label class="layui-form-label">单价</label>
                            <div class="layui-input-block">
                                <input type="radio" name="price" lay-filter="price" value="65" title="65" checked lay-skin="primary" >
                                <input type="radio" name="price" lay-filter="price" value="120" title="120" lay-skin="primary">
                                <input type="radio" name="price" lay-filter="price" value="150" title="150" lay-skin="primary" >
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">出价次数</label>
                            <div class="layui-input-inline">
                                <input type="text" name="price_num" id="price_num" lay-verType="tips"   lay-verify="required|number"  autocomplete="off" class="layui-input">
                            </div>
                            <div class="layui-form-mid layui-word-aux">当前价格出价次数</div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">销售数量</label>
                            <div class="layui-input-inline">
                                <input type="text" name="sell_numbers" id="sell_numbers" lay-verType="tips"   lay-verify="required|number" readonly  autocomplete="off" class="layui-input">
                            </div>
                            <div class="layui-form-mid layui-word-aux">牙膏数量</div>
                        </div>

                        <div class="layui-form-item">
                            <label class="layui-form-label">销售金额</label>
                            <div class="layui-input-inline">
                                <input id="total_price" type="text" name="total_price" lay-verType="tips" readonly required  lay-verify="required|number"  autocomplete="off" class="layui-input">
                            </div>
                            <div class="layui-form-mid layui-word-aux">元</div>
                        </div>

                        <div class="layui-form-item">
                            <label class="layui-form-label">提成金额</label>
                            <div class="layui-input-inline">
                                <input type="text" name="commission" id="commission" lay-verType="tips" readonly  lay-verify="required|number"  autocomplete="off" class="layui-input">
                            </div>
                            <div class="layui-form-mid layui-word-aux">元</div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">销售日期</label>
                            <div class="layui-input-inline">
                                <input id="sell_time" type="text" name="sell_time" lay-verType="tips" readonly lay-verify="required" lay-verType="tips"   class="layui-input">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <div class="layui-input-block">
                                <button id="formreport" class="layui-btn" lay-submit lay-filter="formreport">立即提交</button>
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

if( $action == 'editor'):

    $id = isset($_GET['id'])?$_GET['id']:NULL;

    if($id == NULL):

        ?>

        <div class="warp">

            <h3>请求参数不正确!</h3>

        </div>

        <?php

            exit;
        endif;



    $info = $cscd_bk->get_editor_info_by_id($id);


    if($info['ret'] == 200):

    $data = $info['data'];

?>

    <div class="warp">
        <h1><?php echo esc_html('销售记录编辑'); ?></h1>
        <div class="layui-fluid">
            <div class="layui-row">
                <div class="layui-col-md6">
                    <form class="layui-form" >
                        <input type="hidden" name="id" value="<?php echo $data->id; ?>">
                        <input type="hidden" name="action"  value="editor_sell">
                        <input type="hidden" id="doctor_name"  name="doctor_name" value="<?php echo $data->doctor_name; ?>">
                        <div class="layui-form-item">
                            <label class="layui-form-label">请选择医生</label>
                            <div class="layui-input-inline">
                                <select name="doctor_id" lay-verType="tips" lay-verify="required" >

                                    <option
                                        value="<?php echo $data->doctor_id; ?>"><?php echo $data->doctor_name; ?></option>

                                </select>
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">单价</label>
                            <div class="layui-input-block">
                                <input type="radio" lay-filter="price"  name="price" value="65" title="65" <?php echo $data->price == '65'?'checked':''; ?> lay-skin="primary" >
                                <input type="radio" lay-filter="price" name="price" value="120" title="120"  <?php echo $data->price == '120'?'checked':''; ?> lay-skin="primary">
                                <input type="radio" lay-filter="price" name="price" value="150" title="150" <?php echo $data->price == '150'?'checked':''; ?> lay-skin="primary" >
                            </div>
                        </div>

                        <div class="layui-form-item">
                            <label class="layui-form-label">出价次数</label>
                            <div class="layui-input-inline">
                                <input type="text" name="price_num" id="price_num" lay-verType="tips"   lay-verify="required|number"  autocomplete="off" class="layui-input" value="<?php echo  $data->price_num;?>">
                            </div>
                            <div class="layui-form-mid layui-word-aux">当前价格出价次数</div>
                        </div>

                        <div class="layui-form-item">
                            <label class="layui-form-label">销售数量</label>
                            <div class="layui-input-inline">
                                <input type="text" name="sell_numbers" id="sell_numbers" lay-verType="tips"   lay-verify="required|number"  value="<?php  esc_html_e($data->sell_numbers) ?>" autocomplete="off" readonly class="layui-input">
                            </div>
                            <div class="layui-form-mid layui-word-aux">牙膏数量</div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">销售金额</label>
                            <div class="layui-input-inline">
                                <input id="total_price" type="text" name="total_price" lay-verType="tips" readonly  required  lay-verify="required|number|verprice" value="<?php  esc_html_e($data->total_price) ?>" autocomplete="off" class="layui-input">
                            </div>
                            <div class="layui-form-mid layui-word-aux">元</div>
                        </div>

                        <div class="layui-form-item">
                            <label class="layui-form-label">提成金额</label>
                            <div class="layui-input-inline">
                                <input type="text" name="commission" id="commission" lay-verType="tips" readonly  lay-verify="required|number"  autocomplete="off" class="layui-input" value="<?php  esc_html_e($data->commission) ?>">
                            </div>
                            <div class="layui-form-mid layui-word-aux">元</div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">上报时间</label>
                            <div class="layui-input-inline">
                                <input id="sell_time" type="text" name="sell_time" lay-verType="tips" readonly lay-verify="required" lay-verType="tips" value="<?php  esc_html_e($data->sell_time) ?>"  class="layui-input">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <div class="layui-input-block">
                                <button id="editor_form" class="layui-btn" lay-submit lay-filter="editor_form">立即提交</button>
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
<?php

global $cscd_bk;

?>

<div class="warp_list">
    <h1><?php echo esc_html('8018提成汇总查询'); ?></h1>
    <div class="layui-fluid">
        <div class="layui-row">
            <form class="layui-form">
                <input type="hidden" name="action"  value="get_zh_sell_list">
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
                    <button class="layui-btn"  lay-submit lay-filter="hz_search">查询</button>
                </div>
            </form>
        </div>
        <div class="layui-row" style="margin-top: 10px;">
            <div class="layui-col-md12">
                <table id="hz—list" lay-filter="hz_list"></table>
            </div>
        </div>
    </div>

</div>

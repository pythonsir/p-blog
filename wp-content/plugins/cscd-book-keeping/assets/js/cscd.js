/**
 * Created by pythonsir on 2018/11/28.
 * baidu211@vip.qq.com
 * https://colorlib.cn
 *
 */


layui.use(['layer', 'form','table','laydate'], function(){
    var layer = layui.layer;

    var form = layui.form;

    var table = layui.table;

    var laydate = layui.laydate;

    var $ = layui.jquery;


    //提成管理table
    var _table =  table.render({
        elem: '#sell—list'
        ,method:'post'
        ,url: '/wp-admin/admin-ajax.php' //数据接口
        ,where:{'action':'get_sell_list'}
        ,page: true //开启分页
        ,toolbar: 'default'
        ,cols: [[ //表头
            {type: 'checkbox',fixed: 'left'}
            ,{field: 'doctor_name', title: '医生姓名', width:100}
            ,{field: 'price', title: '销售价格', width:100}
            ,{field: 'sell_numbers', title: '销售数量', width:100}
            ,{field: 'total_price', title: '销售金额', width:100}
            ,{field: 'commission', title: '提成金额', width: 100}
            ,{field: 'sell_time', title: '上报时间', width: 200}
            ,{field: 'create_time', title: '创建时间', width: 200}

        ]]
    });



    var index1;

    //监听头工具栏事件
   table.on('toolbar(sell_list)', function(obj){

        var checkStatus = table.checkStatus(obj.config.id);
         var data = checkStatus.data; //获取选中的数据

        switch(obj.event){
            case 'add':
                window.location.href='/wp-admin/admin.php?page=cscd-book-keeping&action=add'
                break;
            case 'update':
                if(data.length === 0){
                    layer.msg('请选择一行');
                } else if(data.length > 1){
                    layer.msg('只能同时编辑一个');
                } else {

                    window.location.href='/wp-admin/admin.php?page=cscd-book-keeping&action=editor&id='+data[0].id;
                }
                break;
            case 'delete':
                if(data.length === 0){
                    layer.msg('请选择一行');
                } else {

                    layer.confirm('确定删除记录?', function(index){

                        let str = '';
                        for(let i =0; i < data.length; i ++){
                            str += data[i]['id']+','

                        }
                        str = str.substr(0,str.length-1);

                        $.ajax({
                            url:'/wp-admin/admin-ajax.php?page=2018',
                            method:'POST',
                            data:{"action":"delete_sell","id":str},
                            dataType:'json',
                            beforeSend:function () {
                                index1 = layer.load();
                            },
                            success:function (data) {

                                if(data.ret == 200){

                                    layer.msg(data.msg);

                                    _table.reload();

                                }else{
                                    layer.msg(data.msg);
                                }

                                layer.close(index1);
                            },
                            error:function (xhr, ajaxOptions, thrownError) {
                                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                            }


                        })

                        layer.close(index);
                    });

                }
                break;
        };
    });


    laydate.render({
        elem: '#start', //开始日期
        btns: ['clear','now', 'confirm']
    });

    laydate.render({
        elem: '#end', //结束日期
        btns: ['clear','now', 'confirm']
    });

    laydate.render({
        elem: '#sell_time', //指定元素
        btns: ['clear','now', 'confirm']
    });



    form.verify({
        verprice:function (value,item) {
            if(value < 65){
                return '付款金额必须大于或者等于65元'
            }
        }
    })

    $("#price_num").blur(function () {

        let price = $('input[name="price"]:checked').val();

        let price_num = $(this).val();

        let tc,yn;

        if(price == '65'){

            tc = 30;

            yn = 1;


        }else if(price == '120'){

            tc = 40;

            yn = 2;
        }else if(price == '150'){
            tc = 50;
            yn = 3;
        }

        if(!price_num == ''){

            let total_price =  price * price_num;

            let tc_price = tc * price_num;


            $("#sell_numbers").val(price_num * yn);

            $("#total_price").val(total_price);

            $("#commission").val(tc_price);
        }


    })


    //新增
    form.on('submit(formreport)',function (data) {
        $.ajax({
            url:'/wp-admin/admin-ajax.php?page=2018',
            method:"POST",
            data:data.field,
            dataType:'json',
            beforeSend:function () {

                $('#formreport').addClass('layui-btn-disabled');
                $('#formreport').text('提交中...');
            },
            success:function (data) {
                $('#formreport').removeClass('layui-btn-disabled');
                $('#formreport').text('立即提交');
                if(data.ret == 200){
                    layer.msg(data.message);
                }else{
                    layer.msg(data.message);
                }
            },
            error:function (xhr, ajaxOptions, thrownError) {
                $('#formreport').removeClass('layui-btn-disabled');
                $('#formreport').text('立即提交');
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }

        })


        return false;
    })


    //编辑
    form.on('submit(editor_form)',function (data) {
        $.ajax({
            url:'/wp-admin/admin-ajax.php?page=2018',
            method:"POST",
            data:data.field,
            dataType:'json',
            beforeSend:function () {

                $('#editor_form').addClass('layui-btn-disabled');
                $('#editor_form').text('提交中...');
            },
            success:function (data) {
                $('#editor_form').removeClass('layui-btn-disabled');
                $('#editor_form').text('立即提交');
                if(data.ret == 200){
                    layer.msg(data.msg);
                }else{
                    layer.msg(data.msg);
                }
            },
            error:function (xhr, ajaxOptions, thrownError) {
                $('#editor_form').removeClass('layui-btn-disabled');
                $('#editor_form').text('立即提交');
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }

        })


        return false;
    })


    var _table_1 =  table.render({
        elem: '#hz—list'
        ,method:'post'
        ,url: '/wp-admin/admin-ajax.php' //数据接口
        ,where:{'action':'get_zh_sell_list'}
        ,page: true //开启分页
        ,toolbar: 'false'
        ,totalRow: true
        ,defaultToolbar:['filter', 'print', 'exports']
        ,cols: [[ //表头
            {field: 'doctor_name', title: '医生姓名', width:100,totalRowText: '合计：'}
            ,{field: 'total_price', title: '总销售额', width:100,totalRow: true}
            ,{field: 'sell_numbers', title: '总销售数量', width:100,totalRow: true}
            ,{field: 'commission', title: '总提成金额', width: 100,totalRow: true}
        ]]
    });



    form.on('submit(search)', function(data){

        _table.reload({

            where:data.field

        })

        return false;
    });


    form.on('submit(hz_search)', function(data){

        _table_1.reload({

            where:data.field

        })

        return false;
    });

    form.on('radio(price)',function (data) {


        let nums = $("#price_num").val();

        let price = data.value;

        if(nums == ''){
            return;
        }

        let tc,yn;

        if(price == '65'){

            tc = 30;

            yn = 1;

        }else if(price == '120'){

            tc = 40;
            yn = 2;
        }else if(price == '150'){
            tc = 50;
            yn = 3;
        }

        let total_price =  price * nums;

        let tc_price = tc * nums;

        $("#sell_numbers").val(nums * yn);

        $("#total_price").val(total_price);

        $("#commission").val(tc_price);



    })


    // 医生列表
    var _table_2 =  table.render({
        elem: '#doctor—list'
        ,method:'post'
        ,url: '/wp-admin/admin-ajax.php' //数据接口
        ,where:{'action':'get_doctor_list'}
        ,page: true //开启分页
        ,toolbar: 'default'
        ,cols: [[ //表头
            {type: 'checkbox',fixed: 'left'}
            ,{field: 'doctor_name', title: '医生姓名', width:100}
            ,{field: 'shop_name', title: '门诊名称', width:100}

        ]]
    });


    //监听头工具栏事件
    table.on('toolbar(doctor—list)', function(obj){

        var checkStatus = table.checkStatus(obj.config.id);
        var data = checkStatus.data; //获取选中的数据

        switch(obj.event){
            case 'add':
                window.location.href='/wp-admin/admin.php?page=doctor-list&action=add'
                break;
            case 'update':
                if(data.length === 0){
                    layer.msg('请选择一行');
                } else if(data.length > 1){
                    layer.msg('只能同时编辑一个');
                } else {

                    window.location.href='/wp-admin/admin.php?page=doctor-list&action=editor&id='+data[0].id;
                }
                break;
            case 'delete':
                if(data.length === 0){
                    layer.msg('请选择一行');
                } else {

                    layer.confirm('请确认医生没有销售记录?', function(index){

                        let str = '';
                        for(let i =0; i < data.length; i ++){
                            str += data[i]['id']+','

                        }
                        str = str.substr(0,str.length-1);

                        $.ajax({
                            url:'/wp-admin/admin-ajax.php?page=2018',
                            method:'POST',
                            data:{"action":"delete_doctor","id":str},
                            dataType:'json',
                            beforeSend:function () {
                                index1 = layer.load();
                            },
                            success:function (data) {

                                if(data.ret == 200){

                                    layer.msg(data.msg);

                                    _table_2.reload();

                                }else{
                                    layer.msg(data.msg);
                                }

                                layer.close(index1);
                            },
                            error:function (xhr, ajaxOptions, thrownError) {
                                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                            }


                        })

                        layer.close(index);
                    });

                }
                break;
        };
    });





    form.on('select(shop_id)', function(data){

        // $("#shop_name").val();

        let index=data.elem.selectedIndex;

        $("#shop_name").val(data.elem.options[index].text);

    });



    form.on('select(doctor_id)', function(data){

        let index=data.elem.selectedIndex;

        $("#doctor_name").val(data.elem.options[index].text);

    });

    // 新增医生
    form.on('submit(add_doctor)',function (data) {
        $.ajax({
            url:'/wp-admin/admin-ajax.php?page=2018',
            method:"POST",
            data:data.field,
            dataType:'json',
            beforeSend:function () {

                $('#add_doctor').addClass('layui-btn-disabled');
                $('#add_doctor').text('提交中...');
            },
            success:function (data) {
                $('#add_doctor').removeClass('layui-btn-disabled');
                $('#add_doctor').text('立即提交');
                if(data.ret == 200){
                    layer.msg(data.msg);
                }else{
                    layer.msg(data.msg);
                }
            },
            error:function (xhr, ajaxOptions, thrownError) {
                $('#add_doctor').removeClass('layui-btn-disabled');
                $('#add_doctor').text('立即提交');
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }

        })


        return false;


    })


    form.on('submit(doctor_search)', function(data){

        _table_2.reload({

            where:data.field

        })

        return false;
    });

    form.on('submit(editor_doctor)',function (data) {
        $.ajax({
            url:'/wp-admin/admin-ajax.php?page=2018',
            method:"POST",
            data:data.field,
            dataType:'json',
            beforeSend:function () {

                $('#editor_doctor').addClass('layui-btn-disabled');
                $('#editor_doctor').text('提交中...');
            },
            success:function (data) {
                $('#editor_doctor').removeClass('layui-btn-disabled');
                $('#editor_doctor').text('立即提交');
                if(data.ret == 200){
                    layer.msg(data.msg);
                }else{
                    layer.msg(data.msg);
                }
            },
            error:function (xhr, ajaxOptions, thrownError) {
                $('#editor_doctor').removeClass('layui-btn-disabled');
                $('#editor_doctor').text('立即提交');
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }

        })


        return false;
    })


});
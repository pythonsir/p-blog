$(function () {


    $(document).scroll(function () {

        if($(document).scrollTop() >200){

            $(".site-branding").addClass("site-branding-fixed");

        }else{
            $(".site-branding").removeClass("site-branding-fixed");

        }

    });


    $("form").submit(function(e){
        if($("#comment").val() == ''){
            layer.msg('评论内容不能为空!', {
                time: 2000,
            });
            return false;
        }
        if($("#author").val() == ''){
            layer.msg('姓名不能为空!', {
                time: 2000,
            });
            return false;
        }

        if($("#email").val() == ''){
            layer.msg('电子邮件不能为空!', {
                time: 2000,
            });
            return false;
        }
        return true;
    });





})
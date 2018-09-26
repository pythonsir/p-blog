let curWwwPath = window.document.location.href;

let pathName = window.document.location.pathname;

let pos = curWwwPath.indexOf(pathName);

let localhostPath = curWwwPath.substring(0, pos);

let gotourl =localhostPath+"/wp-login.php?redirect_to="+localhostPath+"/archives/20";

$vuetify = new Vue({
    el: '#app',
    data(){
        return {
            scrollTop:0,
            duration: 300,
            offset: 0,
            easing: 'easeInOutCubic',
            comments:{
                lists:[]
            },
            page: 1
        }
    },
    created(){
        let _this = this;
        $.ajax({
            url:'/wp-comments.php',
            type:'POST',
            data:{'action':'getComments','id':post_id},
            dataType:"json",
            success:function (data) {
                _this.comments.lists = data.reverse();
            },
            error:function () {
                
            }
        })
    },
    computed:{
        target () {
            const value = this[this.type]
            if (!isNaN(value)) return Number(value)
            else return value
        },
        options () {
            return {
                duration: this.duration,
                offset: this.offset,
                easing: this.easing
            }
        },
        visiable:function () {

            if(this.scrollTop > 150){
                return true
            }else{
                return false
            }

        }
    },
    mounted () {
        window.addEventListener('scroll', this.handleScroll)
    },
    methods:{
        handleScroll:function(){
           this.scrollTop = document.documentElement.scrollTop;
        },
        onScroll:function() {
            document.documentElement.scrollTop = "0px";
        },
        hiddenComm:function (index) {

            let comm = this.comments.lists[index];

            comm.children_flag = false;
        },
        viewComm:function (index) {
            let comm = this.comments.lists[index];

            comm.children_flag = true;
        },
        gotoLogin:function () {
            window.location.href=gotourl;
        },
        replyComments:function ($vuetify) {

            if (is_user_logged_in){

                $vuetify.goTo('#content');

            }else{
                window.location.href=gotourl;
            }
        }
    }


})
let curWwwPath = window.document.location.href;

let pathName = window.document.location.pathname;

let pos = curWwwPath.indexOf(pathName);

let localhostPath = curWwwPath.substring(0, pos);

let gotourl =localhostPath+"/wp-login.php?redirect_to="+curWwwPath;

$vuetify = new Vue({
    el: '#app',
    data(){
        return {
            scrollTop:0,
            duration: 300,
            offset: 0,
            easing: 'easeInOutCubic',
            commentFlag:false,
            comments:{
                lists:[]
            },
            reply:{
              flag:false,
                reply_user:'',
                id:0,
                karma:0,
                content:""
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
                _this.commentFlag = true;
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

        },
        btnflag:function () {
            if(this.reply.content.trim() != "" ){
                return true;
            }else {
                return false;
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
        replyComment_p0:function ($vuetify,item) {

            if (is_user_logged_in){
                this.reply={
                    flag:true,
                    reply_user:item.comment_author,
                    id:item.comment_ID,
                    karma:item.comment_ID
                }
                this.$refs.textarea.focus();

                $vuetify.goTo('#content');

            }else{
                window.location.href=gotourl;
            }

        },
        replyComments:function ($vuetify,item_1,item) {

            if (is_user_logged_in){
                this.reply={
                    flag:true,
                    reply_user:item_1.comment_author,
                    id:item_1.comment_ID,
                    karma:item.comment_ID

                }
                this.$refs.textarea.focus();

                $vuetify.goTo('#content');

            }else{
                window.location.href=gotourl;
            }
        },
        clearComm:function () {
            this.reply = {
                flag:false,
                reply_user:"",
                id:"",
                karma:0,
                content:""

            }
        },
        newComm:function () {


            let _this = this;

            $.ajax({
                url:'/wp-comments.php',
                type:'POST',
                data:{
                    'action':'addComment',
                    'comment_post_ID':post_id,
                    'comment_parent':_this.reply.id,
                    'comment_karma': _this.reply.karma,
                    'comment':_this.reply.content
                },
                dataType:"json",
                success:function (data) {

                },
                error:function (data) {
                    
                }
                

            })

        }
    }


})
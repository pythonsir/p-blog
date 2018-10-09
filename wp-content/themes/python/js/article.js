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
                content:''
            },
            currpage:1,
            pageSize:15,
            pagenum:0,
            total:0,
            snackbar:false,
            message:''

        }
    },
    created(){
        this.getlist();

    },
    watch:{
        currpage:function(newPage, oldPage){
            this.commentFlag = false;
            this.getlist();
        }
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
            if(this.reply.content != undefined && this.reply.content.trim() != ""){
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
        getlist:function () {
            let _this = this;
            $.ajax({
                url:'/wp-comments.php',
                type:'POST',
                data:{'action':'getComments',
                    'id':post_id,
                    'page': _this.currpage,
                    'pageSize': _this.pageSize
                },
                dataType:"json",
                success:function (data) {
                    _this.currpage = data.page;
                    _this.pagenum = data.pagenum;

                    _this.comments.lists = data.lists;

                    _this.commentFlag = true;
                    _this.total = data.total;
                },
                error:function () {
                    _this.commentFlag = true;
                }
            })
        },
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

            let content = _this.reply.content;

            $.ajax({
                url:'/wp-comments.php',
                type:'POST',
                data:{
                    'action':'addComment',
                    'comment_post_ID':post_id,
                    'comment_parent':_this.reply.id,
                    'comment_karma': _this.reply.karma,
                    'comment':content
                },
                dataType:"json",
                beforeSend:function () {
                    _this.reply.content = "";
                },
                success:function (data) {
                    console.log(data);
                        if(data.ret == 500){
                            _this.snackbar = true;
                            _this.message = data.message;
                        }else{
                            _this.getlist()
                        }
                },
                error:function (data) {
                    _this.snackbar = true;
                    _this.message = data.message;
                },
                complete:function () {
                    _this.clearComm();
                }

            })

        }
    }


})
let curWwwPath = window.document.location.href;

let pathName = window.document.location.pathname;

let pos = curWwwPath.indexOf(pathName);

let localhostPath = curWwwPath.substring(0, pos);

new Vue({
    el: '#app',
    data(){
        return {
            page:1,
            pageNum:1,
            skills:[
                {
                    title:"职业",
                    info:"全栈工程师、热爱编程、技能全栈、有代码洁癖。"
                },
                {
                    title:"坐标",
                    info:"山西太原"
                },
                {
                    title:'GitHub地址',
                    info:"https://github.com/pythonsir",
                    url:"https://github.com/pythonsir"
                }
            ]
        }
    },
    created:function () {
        this.page = page1 == 0? 1: page1;
        this.pageNum = pageNum;
    },
    methods:{
        gotoNext:function () {
            this.pageurl();
        },
        gotoPre:function () {
            this.pageurl();
        },
        gopage:function () {
            this.pageurl();
        },
        pageurl:function () {
            window.location.href=localhostPath+"/page/"+this.page;
        }
        
    }


})
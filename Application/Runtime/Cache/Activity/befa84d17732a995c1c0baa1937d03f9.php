<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>我的奖品</title>
    <meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no, width=device-width">

    <!-- CSS Code -->
    <link rel="stylesheet" href="/test/Public/Activity/common/css/1028_common.css" type="text/css">

    <!-- JavaScript Code -->
    <script src="/test/Public/Activity/common/js/jquery.min.js"></script>
</head>

<body bgcolor="#fff">
	<!-- 主要内容 -->
    <div id="prizePage">
        <ul>
           
        </ul>
        <div class='loading'>
            加载中<img src='/test/Public/Activity/common/image/loading.gif' alt='load' style='margin: 0.25rem;height: 0.7rem;'>
        </div>  
        <div class='over'>
            已显示全部
        </div>  
    </div>
<script type="text/javascript">   
    //数据接口
    var page=1,offnum=8,protect=false,gofor;
    function createListItems(){      
        $.ajax({
            url:"/test/index.php/Activity/Ajax/prize_data.html",//需要处理数据的页面
            type:"POST",
            dataType:"json",
            data:{
                'page':page,
                'offnum':offnum,
                'uid':"<?php echo ($uid); ?>",
                'member_id':"<?php echo ($member_id); ?>",
            },  
            success: function(data){         
                for(var d=0;d<data.length;d++){
                    $('#prizePage ul').append(
                        "<li data-href='"+data[d].href+"' data-id='"+data[d].id+"'>"+
                            "<div class='banner'>"+
                                "<img src='"+data[d].pic+"'>"+
                            "</div>"+
                            "<div class='main'>"+
                                "<h1>"+data[d].name+"</h1>"+
                                "<h2>有效期: "+data[d].expiry_time+"</h2>"+
                            "</div>"+
                        "</li>"
                    );
                };
                $('.loading').show(0);
                if(data.length<offnum){
                    $('.loading').hide(0);
                    $('.over').show(0);
                }else{
                    loaded=1;
                }                 
                page++;    
                get();
            },
            error:function(res){

            }
        });        
    }
    // 简单的防抖动函数
    function debounce(func, wait, immediate) {
        // 定时器变量
        var timeout;
        return function() {
            // 每次触发 scroll handler 时先清除定时器
            clearTimeout(timeout);
            // 指定 xx ms 后触发真正想进行的操作 handler
            timeout = setTimeout(func, wait);
        };
    };

    // 执行下拉函数
    function realFunc(){
        if($(window).scrollTop()+$(window).height()===$(document).height()){
            if(loaded!==1){
                return;
            }
            loaded=0;
            $('.loading').show(0);
            createListItems();      
        }
    }
    // 采用了防抖动
    window.addEventListener('scroll',debounce(realFunc,500));
    createListItems();

    function get(){
        $('#prizePage ul li').click(function(){
            gofor=$(this).attr('data-href');
            if(protect===true){
                return;
            }
            protect=true;
            $.ajax({
                url:"/test/index.php/Activity/Ajax/receive.html",
                type:"POST",
                dataType:"json",
                data:{
                    uid:"<?php echo ($uid); ?>",
                    media_id:"<?php echo ($media_id); ?>",
                    entry_id:"<?php echo ($entry_id); ?>",
                    record_id:$(this).attr('data-id')
                },                                                   
                success: function(data){
                    protect=false;
                    if(data.status===1){
                        window.location.href=gofor;
                    }           
                },
                error: function(){
                    //丢包统计
                    protect=false;
                    window.location.href=gofor;
                },
            });         
        });    
    }
    
</script>      
</body>
</html>
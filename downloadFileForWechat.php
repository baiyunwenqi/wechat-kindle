<?php
function remote_filesize($url_file){ 
    $headInf = get_headers($url_file,1); 
    return $headInf['Content-Length']; 
}
$url = $_GET['url'];
$writer = $_GET['writer'];
$bookName = str_replace(' ','',$_GET['bookName']);
$strArr = explode('.',$url);
$fileName=$bookName.".".$strArr[4];
$fileType= $strArr[4];
?> 
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<link rel="stylesheet" href="res/wechat/weui.min.css">
<script type="text/javascript"
	src="http://apps.bdimg.com/libs/jquery/2.1.4/jquery.min.js"></script>
</head>
<body>
<div class="weui_cells_title">书籍详情</div>
<div class="weui_cells">
    <div class="weui_cell">
        <div class="weui_cell_ft">
            <p align="left">  <?php echo $bookName;?></p>
        </div>
    </div>
	<div class="weui_cell">
        <div class="weui_cell_ft">
            <p align="left">  <?php echo $writer;?></p>
        </div>
    </div>
	<div class="weui_cell">
        <div class="weui_cell_ft">
            <p align="left">  <?php 
			$fileSize =remote_filesize($url);
		    if($fileSize<=1048576){
			   $s =sprintf("%.2f",$fileSize/1024)."kB";
			   echo "$s";
		   }else{
			   $s =sprintf("%.2f",$fileSize/(1024*1024))."MB";
			   echo "$s";
		   };?></p>
        </div>
    </div>
    <div class="weui_cell">
        <div class="weui_cell_ft">
            <p align="left"> <?php echo $url;?></p>
        </div>
    </div>
    <div class="weui_cell">
         <div class="weui_cell_bd weui_cell_primary">
            <p></p>
        </div>
        <div class="weui_cell_ft">
            <p align="right"></p>
        </div>
    </div>
 <?php 
if(!strstr($url,'pan')){
if($fileType=='mobi' ||$fileType=='azw'){
        echo "<div class=\"button_sp_area\"><a onclick=send2Kindle('$url','$bookName',this) class=\"weui_btn weui_btn_plain_primary\">推送至kindle</a></div>";
		echo "<div class=\"button_sp_area\"><a onclick=send2MailUrl('$url','$bookName',this) class=\"weui_btn weui_btn_plain_primary\">发送至邮箱</a></div>";
}else{
     echo "<div class=\"button_sp_area\"><a onclick=send2MailUrl('$url','$bookName',this) class=\"weui_btn weui_btn_plain_primary\">发送至邮箱</a></div>";
}
}
    ?>
</div>
    <div class="weui_dialog_alert" id="dialog1" style="display: none;">
        <div class="weui_mask"></div>
        <div class="weui_dialog">
            <div class="weui_dialog_hd"><strong class="weui_dialog_title">推送结果</strong></div>
            <div class="weui_dialog_bd">推送成功，稍后推送至您的kindle!</div>
            <div class="weui_dialog_ft">
                <a href="javascript:close2();" class="weui_btn_dialog primary">确定</a>
            </div>
        </div>
    </div>
    <div class="weui_dialog_alert" id="dialog2" style="display: none;">
        <div class="weui_mask"></div>
        <div class="weui_dialog">
            <div class="weui_dialog_hd"><strong class="weui_dialog_title">推送结果</strong></div>
            <div class="weui_dialog_bd">sorry,推送失败！</div>
            <div class="weui_dialog_ft">
                <a href="javascript:close1();" class="weui_btn_dialog primary">确定</a>
            </div>
        </div>
    </div>
	<div class="weui_dialog_alert" id="dialog6" style="display: none;">
        <div class="weui_mask"></div>
        <div class="weui_dialog">
            <div class="weui_dialog_hd"><strong class="weui_dialog_title">推送结果</strong></div>
            <div class="weui_dialog_bd">sorry,推送失败,电子书太大！</div>
            <div class="weui_dialog_ft">
                <a href="javascript:close6();" class="weui_btn_dialog primary">确定</a>
            </div>
        </div>
    </div>
     <div class="weui_dialog_alert" id="dialog3" style="display: none;">
        <div class="weui_mask"></div>
        <div class="weui_dialog">
            <div class="weui_dialog_hd"><strong class="weui_dialog_title">发送结果</strong></div>
            <div class="weui_dialog_bd">发送成功,稍后将书籍发送至您的邮箱,请不要重复发送！</div>
            <div class="weui_dialog_ft">
                <a href="javascript:close3();" class="weui_btn_dialog primary">确定</a>
            </div>
        </div>
    </div>
    <div class="weui_dialog_alert" id="dialog4" style="display: none;">
        <div class="weui_mask"></div>
        <div class="weui_dialog">
            <div class="weui_dialog_hd"><strong class="weui_dialog_title">发送结果</strong></div>
            <div class="weui_dialog_bd">sorry,发送失败！</div>
            <div class="weui_dialog_ft">
                <a href="javascript:close4();" class="weui_btn_dialog primary">确定</a>
            </div>
        </div>
    </div>
	<div class="weui_dialog_alert" id="dialog5" style="display: none;">
        <div class="weui_mask"></div>
        <div class="weui_dialog">
            <div class="weui_dialog_hd"><strong class="weui_dialog_title">推送结果</strong></div>
            <div class="weui_dialog_bd">您还未添加kindle推送邮箱，请点击微信菜单【添加推送邮箱】添加！</div>
            <div class="weui_dialog_ft">
                <a href="javascript:close5();" class="weui_btn_dialog primary">确定</a>
            </div>
        </div>
    </div>
</body>
 <script>
    function send2Kindle(url,bookName,e){
        $.ajax({
            url:"kindle/send2Kindle.php",
            type:"post",
            data:{url:url,bookName:bookName},
            success:function(data){
                if(data=="y"){
                   $("#dialog1").show();
                }else if(data=="n"){
                   $("#dialog2").show(); 
                }else if(data=="noEmail"){
				   $("#dialog5").show(); 
				}else if(data=="l"){
				   $("#dialog6").show(); 
				}
            }
        });
		$(e).hide();
		setTimeout(function(){$(e).show();},5000);
    }
     function send2MailUrl(url,bookName,e){
        $.ajax({
            url:"kindle/send2MailUrl.php",
            type:"post",
            data:{url:url,bookName:bookName},
            async:false,
            success:function(data){
                if(data=='y'){
                   $("#dialog3").show();
                }else{
                   $("#dialog4").show(); 
                }
            }
        });
		$(e).hide();
		setTimeout(function(){$(e).show();},5000);
    }
     function close1(){
          $("#dialog2").hide(); 
     }
     function close2(){
          $("#dialog1").hide(); 
     }
     function close3(){
          $("#dialog3").hide(); 
     }
     function close4(){
          $("#dialog4").hide(); 
     }
	 function close5(){
          $("#dialog5").hide(); 
     }
	 function close6(){
          $("#dialog6").hide(); 
     }
</script>
</html>
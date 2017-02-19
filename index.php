<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>IP定位</title>
    <link rel="shortcut icon" href="image/favi.ico" />
    <link rel="stylesheet" href="http://cdn.bootcss.com/bootstrap/3.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/bootstrapValidator.min.css"/>
    <link rel="stylesheet" href="css/flat-ui.css"/>
    <script src="http://cdn.bootcss.com/jquery/1.11.1/jquery.min.js"></script>
    <script src="http://cdn.bootcss.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
    <script src="js/bootstrapValidator.min.js"></script>
    <script src="js/flat-ui.min.js"></script>
    <script src="js/application.js"></script>
	<!-- 为使用方便，直接使用jquery.js库，如您代码中不需要，可以去掉 -->
	<!-- <script src="http://code.jquery.com/jquery-1.12.3.min.js"></script>-->
	<!-- 引入封装了failback的接口--initGeetest -->
	<script src="http://static.geetest.com/static/tools/gt.js"></script>
<!-- 若是https，使用以下接口 -->
<!-- <script src="https://code.jquery.com/jquery-1.12.3.min.js"></script> -->
<!-- <script src="https://static.geetest.com/static/tools/gt.js"></script> -->
    <script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=32f38c9491f2da9eb61106aaab1e9739"></script>
<style type="text/css">
<!--
a { text-decoration: none; }
#showtext { cursor: hand; cursor:pointer;}
.none { display: none; }
-->
</style>
	</head>
<body ondragstart="window.event.returnValue=false" oncontextmenu="window.event.returnValue=false" onselectstart="event.returnValue=false">
	<div class="container" style="border-radius: 5px; border: 1px solid #e2e2e9; margin-top: 10px; width: 80%;">
        <div class="col-lg-6 col-lg-offset-3">
            <div class="page-header" style="text-align: center;">
                <h4 style="color: grey;">IP定位</h4>
            </div><br>
        </div>
	<div class="col-lg-6 col-lg-offset-3" style="text-align: center;">
	<a id="showtext" onClick="showdiv('contentid','showtext')">若想查询其他IP信息请点击此处！</a>
	<div class="popup">
	<div id="contentid" class="none">
	            <form id="defaultForm" class="form-horizontal" action="" method="post">
                <div class="form-group">                    
                    <div class="col-lg-12">
                        <input type="text" class="form-control input-lg" placeholder="Ip地址" name="ip" />
                    </div>
                </div><br>
                <div class="form-group">
                    <div class="col-lg-8 col-lg-offset-2">
                        <button type="submit" class="btn btn-wide btn-success">查询</button>
						
                    </div>
                </div>
            </form>
			<a class="btn btn-wide btn-success" type="submit" id="popup-submit">获取AK</a>
	</div>	
	<div id="popup-captcha"></div>
	</div>
	</div>	
	</div><br>
<center>
    <?php
	 require_once 'lib/class.geetestlib.php';
	 $error_info = array(
            '1'=>'服务器内部错误',
            '167'=>'定位失败',
            '101'=>'AK参数不存在'
,           '200'=>'应用不存在，AK有误请检查重试',
            '201'=>'应用被用户自己禁止',
            '202'=>'应用被管理员删除',
            '203'=>'应用类型错误',
            '210'=>'应用IP校验失败',
            '211'=>'应用SN校验失败',
            '220'=>'应用Refer检验失败',
            '240'=>'应用服务被禁用',
            '251'=>'用户被自己删除',
            '252'=>'用户被管理员删除',
            '260'=>'服务不存在',
            '261'=>'服务被禁用',
            '301'=>'永久配额超限，禁止访问',
            '302'=>'当天配额超限，禁止访问',
            '401'=>'当前并发超限，限制访问',
            '402'=>'当前并发和总并发超限'
        );
		$ak=array(*);
				
        foreach($ak as $value) {
			if (isset($_POST['ip'])){
				$req = file_get_contents('http://api.map.baidu.com/highacciploc/v1?qcip='.$_POST['ip'].'&ak='.$value.'&qterm=pc&extensions=1&coord=bd09ll&extensions=3');
				$ip = $_POST['ip'];
			}
			else{
				$req = file_get_contents('http://api.map.baidu.com/highacciploc/v1?qcip='.$_SERVER["REMOTE_ADDR"].'&ak='.$value.'&qterm=pc&extensions=1&coord=bd09ll&extensions=3');
				$ip = $_SERVER["REMOTE_ADDR"];
			}
			$filename="ip.log";
			$handle=fopen($filename,"a+");
			$str=fwrite($handle,"$req\n");
			fclose($handle);
			if (!empty($req)) {
                $res = json_decode($req, true);
				$error_code = $res["result"]["error"];
               if($error_code != 161) {
                                   /* echo '<div class="alert alert-warning" style="width: 70%;">
                                              <a class="close" data-dismiss="alert">&times;</a>
                                              <strong>定位失败！</strong> 
											  <strong>'.$GLOBALS['error_info'][$error_code].'</strong> 
                                          </div>';
									*/
                                if($error_code == 167){
									echo '<div class="alert alert-warning" style="width: 70%;">
                                              <a class="close" data-dismiss="alert">&times;</a>
                                              <strong>定位失败！</strong> 
											  <strong>'.$GLOBALS['error_info'][$error_code].'</strong> 
                                          </div>';
								break;
								}
								
								}
								
                else{      
                        $lng = $res['content']['location']['lng'];
						$lat = $res['content']['location']['lat'];
						$addr = $res["content"]["formatted_address"];
						$business = $res['content']['business'];
						$loc_time = $res['result']['loc_time'];
						$radius = $res['content']['radius'];
						$confidence = $res['content']['confidence'] * 100;
						$area_code = $res['content']['address_component']['admin_area_code'];
						 echo '<div class="alert alert-info" style="width: 70%;">
                                      <a class="close" data-dismiss="alert">&times;</a>
                                      <strong>定位成功！</strong> '.$ip.'地址为：'.$addr.''.$business.'
                                  </div>';
						 echo '<div class="alert alert-info" style="width: 70%;">
                                      <a class="close" data-dismiss="alert">&times;</a>
                                      定位可信度： '.$confidence.'
                                  </div>';	
						echo '<div class="alert alert-info" style="width: 70%;">
                                      <a class="close" data-dismiss="alert">&times;</a>
                                      纬度： '.$lat.'.
									  经度： '.$lng.'.
                                  </div>';			  
                break;    
                }
			}
		}  
    ?>

    <div id="map" style="width: 60%; height: 300px;"></div>
</center>
    <script type="text/javascript">
		
        var map = new BMap.Map("map");
        var lng = <?php echo $lng;?>;
        var lat = <?php echo $lat;?>;
        
        map.centerAndZoom(new BMap.Point(116.331398,39.897445),16);
        map.enableScrollWheelZoom(true);
            
        if (lng != "" && lat != ""){
            map.clearOverlays(); 
            var new_point = new BMap.Point(lng, lat);
            var marker = new BMap.Marker(new_point);  // 创建标注
            map.addOverlay(marker);              // 将标注添加到地图中
            map.panTo(new_point);      
        }
    </script>

    <script type="text/javascript">
        $(function() {
            $('#defaultForm').bootstrapValidator({
                message: 'This value is not valid',
                feedbackIcons: {
                    valid: 'glyphicon glyphicon-ok',
                    invalid: 'glyphicon glyphicon-remove',
                    validating: 'glyphicon glyphicon-refresh'
                },
                fields: {
                    ip: {
                        validators: {
                            notEmpty: {
                                message: 'The ip is required and can\'t be empty'
                            },
                            ip: {
                                message: 'The input is not ip'
                            }
                        }
                    }
                }
            })        
		
		});
    </script>
	<script language="JavaScript" type="text/JavaScript">
<!--
function showdiv(targetid,objN){
   
      var target=document.getElementById(targetid);
      var clicktext=document.getElementById(objN)

            if (target.style.display=="block"){
                target.style.display="none";
                clicktext.innerText="若想查询其他IP信息请点击此处！";
  

            } else {
                target.style.display="block";
                clicktext.innerText='关闭';
            }
   
}
-->
</script>
<script>
    var handlerPopup = function (captchaObj) {
        // 成功的回调
        captchaObj.onSuccess(function () {
            var validate = captchaObj.getValidate();
            $.ajax({
                url: "web/VerifyLoginServlet.php", // 进行二次验证
                type: "post",
                dataType: "json",
                data: {
                    type: "pc",
                    ip: $('#ip').val(),
                    geetest_challenge: validate.geetest_challenge,
                    geetest_validate: validate.geetest_validate,
                    geetest_seccode: validate.geetest_seccode
                },
                success: function (data) {
                    if (data && (data.status === "success")) {
						document.write('RiiKh6SjFTWsF3aQ5T1Nk4bDADyRE6aI')
						document.write('<br>')
						document.write('acTuxaqGSwUsdjMCoCtOfWzpo8mSsBZW')
						document.write('<br>')
						document.write('倘若此AK不可使用（每日有固定配额，超限此日将无法使用），可在我博客www.steven7.top中留言,我将会将可用ak发送到您的邮箱，由于百度精准IP已经关闭申请，所以ak都是稀有资源，望大家妥善使用。')
                    }
                }
            });
        });
        $("#popup-submit").click(function () {
            captchaObj.show();
        });
        // 将验证码加到id为captcha的元素里
        captchaObj.appendTo("#popup-captcha");
        // 更多接口参考：http://www.geetest.com/install/sections/idx-client-sdk.html
    };
    // 验证开始需要向网站主后台获取id，challenge，success（是否启用failback）
    $.ajax({
        url: "web/StartCaptchaServlet.php?type=pc&t=" + (new Date()).getTime(), // 加随机数防止缓存
        type: "get",
        dataType: "json",
        success: function (data) {
            // 使用initGeetest接口
            // 参数1：配置参数
            // 参数2：回调，回调的第一个参数验证码对象，之后可以使用它做appendTo之类的事件
            initGeetest({
                gt: data.gt,
                challenge: data.challenge,
                product: "popup", // 产品形式，包括：float，embed，popup。注意只对PC版验证码有效
                offline: !data.success // 表示用户后台检测极验服务器是否宕机，一般不需要关注
                // 更多配置参数请参见：http://www.geetest.com/install/sections/idx-client-sdk.html#config
            }, handlerPopup);
        }
    });
</script>
    <footer><p style="text-align: center; color: grey; padding-top: 100px;">© 2017 by steven. api由百度提供，本网站仅做展示使用，不承担任何风险和责任.</p></footer>
    
</body>
</html>

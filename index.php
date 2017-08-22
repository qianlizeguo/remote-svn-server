<?php 
error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE );

$root_name = array('1' => '小滨汽修');
$root_dir = array('1' => 'xiaobinqixiu');

$status_msg = '无';
if (isset($_POST['root']) && $_POST['root']) {

    $do_arr = array(1=>'update', 2=>'add', 3=>'commit');

    $error = array();
    $status_msg = '失败';

    $users = array(
    );
    $do = $do_arr[$_POST['type']];
    if (!in_array($_POST['passport'], $users)) {
        $error = array('用户不合法');
    }elseif (!$do) {
        $error = array('请选择正确的操作类型');
    }elseif ($do== 'commit' && !$_POST['dir']) {
        $error = array('请填写正确的目录');
    } else {

        //dir
        $root = 'E:/workspace/' . $root_dir[$_POST['root']] . '/';
        $dir = $root . $_POST['dir'];

        //excu
        if ($do == 'update') {
                $bash = 'svn update ' . $dir;
        } elseif ($do == 'add') {
                $bash = 'svn add ' . $dir;
        } elseif ($do == 'commit') {
                $msg = $_POST['message'];
                $msg = $msg ? iconv("UTF-8", "GB2312//IGNORE", $msg) : 'fix';
                $msg = $msg . '  --' . $_POST['passport'];
                $bash = 'svn commit -m "' . $msg . '"  ' .  $dir;
        }

        if (is_dir($dir) || file_exists($dir)) {
            exec("$bash", $out,$status);
            //var_dump($out);
            //echo $status;
            if ($status == 0) {
                $status_msg = '成功';
                $error = $out;
            } else {
                $error = $out;
            }
        } else {
            $error = array('目录或文件不存在，请重试');
        }
    }
}

?>


<!DOCTYPE html>
<!-- saved from url=(0046)https://app.getpostman.com/signup?redirect=web -->
<html lang="en"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    
    <meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Cache-Control" content="no-cache">
<meta http-equiv="Expires" content="0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>svn操作设置</title>
    <link rel="apple-touch-icon" href="https://app.getpostman.com/img/apple-touch-icon.png?00ca15c7914d3cb19c6ee545d692b0ae&amp;">
    <link href="./up_files/css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="./up_files/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="./up_files/resp.css">
<style type="text/css">
  *{list-style: none;}
  .sign-in-box{width: 80%;height:auto}
  .form-control{width: 82%;display: inline-block;}
  #mySelect{border-radius: 5px;height: 38px; width:100px}
  #signin-btn{display: block;margin:0 auto; width: 100%;}
  .status li h5{display: inline-block;}
</style>
</head>
<body class="signup-page-body">
  <div class="pm-page-wrap">
    <div class="sign-in-up-container">

  <div id="signin" class="auth-tab row signin-container">
    <div class="sign-in-box">
      <form id="sign-in-form" method="post" action="">
        <label class="pm-form-label pm-email">
          <div class="pm-message-wrap" data-pm-message="">
            <div class="pm-input-state">
            <input name="type" type="radio" value="1" id="type1" <?php if ($_POST['type'] == 1 || !$_POST['type']) { ?> checked <?php }?> onclick="javascript:document.getElementById('message_header').style.display = 'none'"/>&nbsp update &nbsp
                <input name="type" type="radio" value="2" id="type2" <?php if ($_POST['type'] == 2) { ?> checked <?php }?> onclick="javascript:document.getElementById('message_header').style.display = 'none'"/>&nbsp add &nbsp
                <input name="type" type="radio" value="3" id="type3" <?php if ($_POST['type'] == 3) { ?> checked <?php }?> onclick="javascript:document.getElementById('message_header').style.display = 'block'"/>&nbsp commit &nbsp
            </div>
          </div>
        </label>

        <label class="pm-form-label pm-email">
          <div class="pm-message-wrap" data-pm-message="">
            <div class="pm-input-state">
              <!--div class="pm-form-label-text">需要操作的目录</div-->
              <select id="mySelect" name="root">
                <?php foreach($root_name AS $k =>$v) {?>
                <option value="<?php echo $k ?>" ><?php echo $v ?></option>
                <?php }?>
              </select>
              <input name="dir" id="sign-in-email" class="form-control" type="text" placeholder="目录" value="<?php echo $_POST['dir'] ?>" >
            </div>
          </div>
        </label>

        <label class="pm-form-label pm-email" <?php if ($_POST['type'] == 3) { ?>style="display:block"<?php }else {?>style="display:none" <?php } ?> id="message_header">
          <div class="pm-message-wrap" data-pm-message="">
            <div class="pm-input-state">
              <input style="width: 100%" id="message" class="form-control" type="text" name="message" placeholder="注释" value="<?php echo $_POST['message'] ?>"/>
            </div>
          </div>
        </label>

        <label class="pm-form-label pm-password">
          <div class="pm-message-wrap" data-pm-message="">
            <div class="pm-input-state">
              <input style="width: 100%" id="sign-in-password" class="form-control" type="password" name="passport" placeholder="密码" value="<?php echo $_POST['passport'] ?>">
            </div>
          </div>
        </label>
        <label class="pm-form-label pm-login-btn">
          <button type="submit" id="signin-btn" class="pm-btn pm-btn-primary" data-pm-transient-message="Signing in...">start</button>
        </label>
      </form>
        <ul class="status">
          <li>
            <h5>状态:</h5>
            <h5 style="color:red;"><?php echo $status_msg ?></h5> </br>
            <?php foreach ($error AS $v) { ?>
            <h5 style="color:red;font-size:12px"><?php echo $v ?></h5></br>
            <?php }?>
          </li>
        </ul>
    </div>
  </div>
</div>
  </div>
<script src="https://apps.bdimg.com/libs/jquery/2.1.4/jquery.min.js">
</script>
<script type="text/javascript">
var is_start = parseInt(<?php echo $_POST['is_start']?>);
var dir = '<?php echo $_POST['dir']?>';
var passport = '<?php echo $_POST['passport'] ?>';
var message = '<?php echo $_POST['message'] ?>';

window.onload=function(){
　　if(navigator.userAgent.toLowerCase().indexOf("chrome") != -1){
　　　　var selectors = document.getElementsByTagName("input");
　　　　for(var i=0;i<selectors.length;i++){
　　　　if((selectors[i].type !== "submit") && (selectors[i].type !== "password")){
　　　　　　selectors[i].value = " ";
　　　　}
　　}
　　setTimeout(function(){
　　　　for(var i=0;i<selectors.length;i++){
　　　　　　if(selectors[i].type !== "submit" && selectors[i].type !== "hidden"){
　　　　　　　　selectors[i].value = "";
　　　　　　}
　　　　}
        document.getElementById("sign-in-email").value = dir;
        document.getElementById("sign-in-password").value = passport;
        document.getElementById("type1").value = 1;
        document.getElementById("type2").value = 2;
        document.getElementById("type3").value = 3;
        document.getElementById("message").value = message;
　　},500);
 }
}

</script>
</body></html>

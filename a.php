<!DOCTYPE html>
<html>
<title>Login</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="css/w3.css">
<link rel="stylesheet" href="css/font-awesome.min.css">
<link rel="stylesheet" type="text/css" href="css/pace.css">
<style>
body {
  background: url(images/bg4.jpg) no-repeat center center fixed;
  -webkit-background-size: cover;
  -moz-background-size: cover;
  -o-background-size: cover;
  background-size: cover;
}
.w3-theme {color:#fff !important;background-color:#4CAF50 !important;}
.w3-btn {background-color:#4CAF50 ;margin-bottom:4px;}
.w3-code{border-left:4px solid #4CAF50}
@media only screen and (max-width: 601px) {.w3-top{position:static;} #main{margin-top:0px !important}}
</style>

<script src="js/pace.min.js"></script>
<body class='w3-grey' onload="document.login.username.focus();">

<div style="margin-top:200px;"></div>

<div class="w3-row-padding">
  <div class="w3-col s9">&nbsp;</div>

  <div class="w3-col s3 w3-card-2 w3-light-grey">
    <?php
      include"login_check.php";

      if(isset($_SESSION['login_user'])){
        header("location: index.php");
      }
    if(!empty($error)) :
    ?>
    <div class="w3-container w3-red">
      <span onclick="this.parentElement.style.display='none'" class="w3-closebtn">x</span> 
      <p><?php echo $error; ?></p>
    </div>
    <?php endif; ?>

    <div class="w3-container w3-blue">
      <h2>Login Administrator</h2>
    </div>

    <form id="form-login" name="login" class="w3-container" method="POST">
      <p>
        <label class="w3-label w3-text-black"><b>Username :</b></label>
        <input class="w3-input w3-border" type="text" name="username" placeholder="ketik username ... " required>
      </p>
      
      <p>
        <label class="w3-label w3-text-black"><b>Password :</b></label>
        <input class="w3-input w3-border" type="password" name="password" placeholder="ketik password ..." required>
      </p>

      <p><button class="w3-btn w3-blue w3-large" name="submit" value="submit">Login</button>
        <button class="w3-btn w3-red w3-large" type="reset">Reset</button></p>
     
    </form>

  </div>
</div>

<div class="w3-bottom">
  <div class="w3-navbar w3-light-grey w3-small w3-card-4" style="height:10px;"></div>
</div>


</body>
</html> 
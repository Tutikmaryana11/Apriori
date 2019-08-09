<html>
<head>
    <title>Sistem Informasi Penjualan</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <br/>
    <br/>
    <center><h2>Sistem Informasi Penjualan</h2></center>  
    <br/>
    <div class="w3-container" style="text-align: center;">
    <img src="chart.png" width="100" >
    </div>
    <div class="login">
    <br/>
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


    <form id="form-login" name="login" method="POST">
      <div>
        <label>Username</label>
         <input  type="text" name="username" placeholder="ketik username" required> 

      </div>

      <div>
        <label>Password</label>
         <input type="password" name="password" placeholder="ketik password" required> 

      </div>
    <button class="tombol" name="submit" value="submit">Login</button>
        <button class="tombol" type="reset">Reset</button></p>
     
    </form>
    </div>
</body>
 
<script type="text/javascript">
    function validasi() {
        var username = document.getElementById("username").value;
        var password = document.getElementById("password").value;       
        if (username != "" && password!="") {
            return true;
        }else{
            alert('Username dan Password harus di isi !');
            return false;
        }
    }
 
</script>
</html>
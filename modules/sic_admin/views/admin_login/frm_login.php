<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, maximum-scale=1">
    <title>Admin Login</title>
    <link rel="shortcut icon" href="<?php echo url::base() ?>favicon.ico">
    <link href="<?php echo url::base() ?>public/users/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" >  
    <link href="<?php echo url::base() ?>public/users/css/font-awesome.css" rel="stylesheet" type="text/css"> 

</head>
<body>
<div class="container"> 
  <div class="row" id="pwd-container">
    <div class="col-md-4"></div>  
    <div class="col-md-4">
      <section class="login-form" style="padding-top: 200px;">
        <form method="post" action="<?php echo url::base() ?>admin_login/login" role="login">
            <div class="col-lg-12" style="margin-bottom: 10px;">
                <input type="email" name="email" placeholder="Email" required class="form-control input-lg"/>        
            </div>
            <div class="col-lg-12" style="margin-bottom: 10px;">
                <input type="password" name="password" class="form-control input-lg" id="password" placeholder="Password" required="" /> 
            </div>
            <div class="pwstrength_viewport_progress"></div>       
            <div class="col-lg-12">   
                <button type="submit" name="go" class="btn btn-lg btn-primary btn-block">Log in</button>      
            </div>  
        </form>        
      </section>  
      </div>    
  </div>
</div>
</body>
</html>
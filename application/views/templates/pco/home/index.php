<div class="jumbotron vertical-center">
    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4 text-center">   
                <div class="caption">
                    <h3>Advance Password Validation</h3>
                    <p>Find to All</p>
                </div>
                <form id="frm-login" action="<?php echo url::base() ?>login/auth" class="loginForm" method="POST">
                    <input type="text" name="email" class="form-control" placeholder="Email">
                    <input type="password" name="password" class="form-control" placeholder="Password">
                    <input type="submit" id="submit" class="form-control" value="Log-in">
                </form>
            </div>
        </div>
    </div>
</div>

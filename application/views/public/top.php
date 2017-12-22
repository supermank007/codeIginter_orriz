<div class="header">
    <div class="container">
        <div class="row">
            <div class="col-sm-3">
                <div class="logo">
                    <a href="<?= base_url() ?>"><img src="<?= base_url('public/images/logo.png') ?>" alt="Logo"/></a>
                </div>
            </div>
            <div class="col-sm-9">
                <div class="header-form-section">
                    <form role="form" action="<?php echo base_url(); ?>public_gate/login" method="post">
                        <div style="color: #F8BB22">   <?php echo $this->session->flashdata('message'); ?> </div>
                        <div class="form-section">
                            <div class="custom-form-group">
                                <input type="email" placeholder="Email" name="identity" class="custom-form-control" required />
                            </div>
                            <div class="input-bottom-text">
                                <input type="checkbox" name="remember" class="remember-checkbox"/><label id="remember" class="remember-sign">Keep me signed in</label>
                            </div>
                        </div>
                        <div class="form-section">
                            <div class="custom-form-group">
                                <input type="password" placeholder="Password" name="login_password" class="custom-form-control" required />
                            </div>
                            <div class="input-bottom-text">
                                <label id="forgot-password" class="remember-sign"><a href="<?php echo base_url('public_gate/forgot_password'); ?>"> Forgot Password? </a></label>
                            </div>
                        </div>
                        <div class="submit-section">
                            <div class="custom-form-group">
                                <input type="submit" value="Sign In" class="form-submit-button" />
                            </div>
                        </div>
                        <input type="hidden" name="uri" value="<?= isset($uri) ? $uri : '' ?>" />
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
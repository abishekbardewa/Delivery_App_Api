<div class="page-title-overlap bg-dark pt-4">
  <div class="container d-lg-flex justify-content-between py-2 py-lg-3">
    <div class="order-lg-2 mb-3 mb-lg-0 pt-lg-2">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb breadcrumb-light flex-lg-nowrap justify-content-center justify-content-lg-start">
          <li class="breadcrumb-item"><a class="text-nowrap" href="<?=site_url('home')?>"><i class="czi-home"></i>Home</a></li>
          <li class="breadcrumb-item text-nowrap"><a href="#">Account</a>
          </li>
          <li class="breadcrumb-item text-nowrap active" aria-current="page"><?=$title?></li>
        </ol>
      </nav>
    </div>
    <div class="order-lg-1 pr-lg-4 text-center text-lg-left">
      <h1 class="h3 text-light mb-0"><?=$title?></h1>
    </div>
  </div>
</div>

<?php include 'block/menu.php';?>
<section class="col-lg-8" id="changepwdArea">


    
<div class="d-none d-lg-flex justify-content-between align-items-center pt-lg-3 pb-4 pb-lg-5 mb-lg-3" >
<h6 class="font-size-base text-light mb-0">Change your password and secure it.</h6>
<a class="btn btn-primary btn-sm" href="<?=site_url('login/logout')?>"><i class="czi-sign-out mr-2"></i>Sign out</a>
</div>
<div class="col-sm-12">
<?php if(@$this->session->flashdata('msg')){
    echo "<div class='alert alert-info'>".$this->session->flashdata('msg')."</div>";
}?>
    <form class="form-horizontal" action="<?php echo site_url('users/check_password')?>" method="post" />
        	<fieldset>
        	<div class="registerbox">
              <fieldset>
              
              <div class="control-group">
                  <label class="control-label"><span style="color:red">*</span>Current Password:</label>
                    <div class="controls">
                    <input type="password" class="form-control" name="currentpwd" value="<?php echo set_value('currentpwd')?>"><br>
                  <?php echo form_error('currentpwd', '<font color="red">', '</font>'); ?>
                  <?php if (isset($_GET['msg'])) echo "<font color='red'>".$_GET['msg']."</font>"?>
                  </div>
                </div>
              
                <div class="control-group">
                  <label class="control-label"><span style="color:red">*</span> New Password:</label>
                    <div class="controls">
                    <input type="password" class="form-control" name="newpwd" value="<?php echo set_value('newpwd')?>"><br>
                  <?php echo form_error('newpwd', '<font color="red">', '</font>'); ?>
                  </div>
                </div>
                
                <div class="control-group">
                  <label class="control-label"><span style="color:red">*</span>Confirm Password :</label>
                  <div class="controls">
                     <input type="password" class="form-control" name="confirmpwd" value="<?php echo set_value('confirmpwd')?>"><br>
                  <?php echo form_error('confirmpwd', '<font color="red">', '</font>'); ?>
                  </div>
                </div>
              </fieldset>
            </div>
            
            <div class="pull-left">
              <input type="Submit" class="btn btn-success" value="Submit">
              
                <input type="reset" class="btn btn-secondary" value="Clear">
              </label> 
            </div>
          </form>
</div>
</section>
</div>
</div>
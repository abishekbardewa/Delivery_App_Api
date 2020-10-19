<div class="py-4 bg-dark">
  <div class="container d-lg-flex justify-content-between py-2 py-lg-3">
    <div class="order-lg-2 mb-3 mb-lg-0 pt-lg-2">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb flex-lg-nowrap justify-content-center justify-content-lg-start">
          <li class="breadcrumb-item"><a class="text-nowrap" href="<?=site_url('home')?>"><i class="czi-home"></i>Home</a></li>
          <li class="breadcrumb-item text-nowrap active" aria-current="page">Password Reset</li>
        </ol>
      </nav>
    </div>
    <div class="order-lg-1 pr-lg-4 text-center text-lg-left">
      <h1 class="h3 mb-0">Password Reset</h1>
    </div>
  </div>
</div>
<section class="container-fluid pt-grid-gutter">
  <div class="row">
    <div class="col-xl-3"></div>
    <div class="col-xl-6">
        <br>
        <?php 
//         print_r($_SESSION);
        if(@$this->session->flashdata('msg')){
            echo "<div class='alert alert-info'>".$this->session->flashdata('msg')."</div>";
        }
        if(@$_SESSION['temail']){?>
        <form class="form-horizontal" id="resetForm" onsubmit="return false">
            <div class="control-group">
              <label class="control-label"><span style="color:red">*</span> New Password:</label>
                <div class="controls">
                <input type="password" class="form-control" name="newpwd" value="<?php echo set_value('newpwd')?>" autofocus><br>
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
            <div id="resultreset" style="margin:4px"></div>
            <div class="pull-left">
                <input type="button" class="btn btn-success" value="Submit" id="restbtn">
                <input type="reset" class="btn btn-secondary" value="Clear">
            </div>
        </form>
        <?php }?>       
        
        <br><br><br><br>
    </div>
    <div class="col-xl-3"></div>
  </div>
</section>
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
<section class="col-lg-8" id="profileArea">
<!-- Toolbar-->
<div class="d-none d-lg-flex justify-content-between align-items-center pt-lg-3 pb-4 pb-lg-5 mb-lg-3">
<h6 class="font-size-base text-light mb-0">Update you profile details below:</h6><a class="btn btn-primary btn-sm" href="<?=site_url('login/logout')?>"><i class="czi-sign-out mr-2"></i>Sign out</a>
</div>
<!-- Profile form-->
  <form action="<?=site_url('users/profile')?>" enctype="multipart/form-data" method="post">
    <div class="bg-secondary rounded-lg p-4 mb-4">
      <div class="media align-items-center"><img src="<?=base_url("assets/uploads/users/thumb")?>/<?php if(@$_POST['old_image']) echo $_POST['old_image'];else echo @$_POST['img']?>" width="90" alt="">
        <div class="media-body pl-3">
          <input type="file" class="btn btn-secondary btn-shadow" name="image">
          <div class="p mb-0 font-size-ms text-muted">Upload JPG, GIF or PNG image. 300 x 300.</div>
          <input value="<?php if(@$_POST['old_image']) echo $_POST['old_image'];else echo @$_POST['img']?>" name="old_image" hidden>
        </div>
      </div>
    </div>
    <?php if(@$this->session->flashdata('msg')){
    echo "<div class='alert alert-info'>".$this->session->flashdata('msg')."</div>";
}?>
    <div class="row">
    <?php if(validation_errors()){?>
    <div class="col-sm-12 alert alert-danger">
        <?=validation_errors()?>
    </div>
    <?php }?>
   

      <div class="col-sm-4">
        <label>Full Name</label>
        <input class="form-control" name='name' value="<?=@$_POST['name']?>">
      </div>
      <div class="col-sm-4">
        <label>Gender</label>
        <select class="form-control" name='gender'>
            <option value="M">Male</option>
            <option value="F">Female</option>
        </select>
      </div>
      <div class="col-sm-4">
        <label>Email address</label>
        <input class="form-control" type="email" name='email' value="<?=@$_POST['email']?>">
      </div>
      <div class="col-sm-4">
        <label>Phone</label>
        <input class="form-control" type="number" value="<?=@$_POST['phone']?>" name='phone'>
      </div>
      <div class="col-sm-4">
        <label>Date of Birth</label>
        <input class="form-control" type="date" value="<?=@$_POST['dob']?>" name='dob'>
      </div>
      
      <div class="col-sm-4">
      <br>
          <div class="custom-control custom-checkbox d-block">
            <input class="custom-control-input" type="checkbox" id="subscribe_me" checked="checked" disabled>
            <label class="custom-control-label" for="subscribe_me">Subscribe me to Newsletter</label>
          </div>
        </div>
              
      <div class="col-12">
        <hr class="mt-2 mb-3">
<button class="btn btn-primary mt-3 mt-sm-0" type="submit">Update profile</button>        
      </div>
    </div>
  </form>
</section>
  
      </div>
    </div>
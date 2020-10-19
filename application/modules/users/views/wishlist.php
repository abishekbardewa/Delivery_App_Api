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
<section class="col-lg-8" id="wishlArea">


    
<div class="d-none d-lg-flex justify-content-between align-items-center pt-lg-3 pb-4 pb-lg-5 mb-lg-3">
<h6 class="font-size-base text-light mb-0">Welcome <?=$this->session->userdata('name')?> </h6>
<a class="btn btn-primary btn-sm" href="<?=site_url('login/logout')?>"><i class="czi-sign-out mr-2"></i>Sign out</a>
</div>
<?php $this->load->view('wishlist/wishlist')?>

</section>
  
      </div>
    </div>
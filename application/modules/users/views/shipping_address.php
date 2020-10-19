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
<section class="col-lg-8" id="shippingArea">
<!-- Toolbar-->
<div class="d-none d-lg-flex justify-content-between align-items-center pt-lg-3 pb-4 pb-lg-5 mb-lg-3">
<h6 class="font-size-base text-light mb-0">Enter multiple shipping addresses</h6><a class="btn btn-primary btn-sm" href="<?=site_url('login/logout')?>"><i class="czi-sign-out mr-2"></i>Sign out</a>
</div>
<!-- Profile form-->
  <form action="<?=site_url('users/shipping_address')?>" method="post">
        <?php if(@$this->session->flashdata('msg')){
            echo "<div class='alert alert-info'>".$this->session->flashdata('msg')."</div>";
        }?>
    <div class="row">
        <?php if(validation_errors()){?>
        <div class="col-sm-12 alert alert-danger">
            <?=validation_errors()?>
        </div>
        <?php }?>
      <input name="addr_id" value="<?=$this->input->get('addr_id')?>" hidden>
    
    <div class="col-sm-4">
        <label>First Name</label>
        <input class="form-control" value="<?=@$_POST['name']?>" name='name'>
      </div>
      <div class="col-sm-4">
        <label>Last Name</label>
        <input class="form-control" value="<?=@$_POST['lname']?>" name='lname'>
      </div>
      <div class="col-sm-4">
        <label>Pin</label>
        <input class="form-control" value="<?=@$_POST['pin']?>" name='pin'>
      </div>
      <div class="col-sm-4">
        <label>City</label>
        <input class="form-control" value="SILIGURI" readonly name='city'>
      </div>
      <div class="col-sm-4">
        <label>Country</label>
        <select class="form-control" name='country_id'>
        <?php $cq=$this->db->get('oc_country')->result();
            foreach($cq as $c){?>
            <option value="<?=$c->country_id?>"><?=$c->name?></option>
            <?php }?>
        </select>
      </div>
      <div class="col-sm-4">
        <label>Zone</label>
        <select class="form-control" name='zone_id'>
            <?php $rq=$this->db->get('oc_zone')->result();
            foreach($rq as $z){?>
            <option value="<?=$z->zone_id?>"><?=$z->name?></option>
            <?php }?>
        </select>
      </div>
      <div class="col-sm-4">
        <label>Address</label>
        <textarea class="form-control" name='address'><?=@$_POST['address']?></textarea>
      </div>
      <div class="col-sm-4">
      <br>
        <button class="btn btn-primary mt-3 mt-sm-0" type="submit">Add</button>        
      </div>
    </div>
  </form>
  <div class="col-sm-12 mb-lg-3">
  <br>
  <?php 
  $shipqry=$this->db->where('user_id',$_SESSION['user_id'])->join('oc_zone','zone_id')->get('users_address');
  if($shipqry->num_rows()<1){
      echo "<br><div class='alert alert-info'>No Shipping Informations added.</div>";
  }else{
  ?>
  <table class="table table-bordered table-hover">
  <tr style="background: #06f;color:#fff"><th>Pin</th><th>City</th><th>Zone</th><th>Address</th><th style="width:130px">Action</th></tr>
  <?php foreach ($shipqry->result() as $s){?>
      <tr><td><?=$s->pin?></td><td><?=$s->city?></td><td><?=$s->name?></td><td><?=$s->address?></td><td><a class='btn btn-warning btn-sm' href='<?=site_url('users/shipping_address?addr_id='.$s->addr_id)?>'>Edit</a> <a class='btn btn-danger btn-sm' href='<?=site_url('users/shipping_address?del=1&addr_id='.$s->addr_id)?>'>X</a></td></tr>
      <?php }
  }?>
  </table>
  </div>
</section>
  
      </div>
    </div>
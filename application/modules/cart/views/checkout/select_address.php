<style>
#showfrom,#hidefrom {float: right;margin-top: -59px;}
.red{color:red !important;font-weight:bold;padding-left:5px}
</style>
<script>
function saveShip(e){
	$.ajax({
    	type: "POST",url: "<?=site_url('cart/shipping_add')?>",data: $("#shipFrm").serialize(),
    	beforeSend: function(){$('#shpresult').html('<p>Please Wait...</p>');},success: function(data){
    		$('#shpresult').empty();
    		if(data=='1'){
    			html="<div  class='alert alert-success'>Success !</small></div>";
    			window.location.assign("<?=site_url('cart/payment')?>");
    		}else html=data;
    		$('#shpresult').html(html);
    	}
    });
	return false;
}
function showshipFormNew(i){
	if(i=='1'){
		$("#shipFormNew").css('display','block');
		$("#showfrom").css('display','none');
	}else{
		$("#shipFormNew").css('display','none');
		$("#showfrom").css('display','block');
	}
}
</script>
<button onclick="showshipFormNew(1)" id="showfrom" class="btn btn-info btn-sm">+ New</button>
<section id="shipFormNew" style="display:none">
<?php 
  if($shipqry->num_rows()<1){
      echo "<br><div class='alert alert-info'>No Shipping Informations added.</div>";
  }
  
  if(@$_GET['id'] && $_GET['id']){
      $shipqy=$this->db->where('addr_id',$_GET['id'])->where('user_id',$_SESSION['user_id'])->get('users_address')->result();
      $_POST=json_decode(json_encode($shipqy[0]),true);
      echo "<script>$('#shipFormNew').css('display','block');$('#showfrom').css('display','none');</script>";
  }else{
      $_POST['name']=$_SESSION['name'];
  }
  ?>
  <button onclick="showshipFormNew(0)" class="btn btn-danger btn-sm" id="hidefrom">X Close</button>
<!-- Profile form-->
  <form method="post" id="shipFrm" onsubmit="return saveShip()">
        <?php if(@$this->session->flashdata('msg')){
            echo "<div class='alert alert-info'>".$this->session->flashdata('msg')."</div>";
        }?>
    <div class="row">
        <div class="col-sm-12" style="color:red" id="shpresult"></div>
        <input name="addr_id" value="<?=@$_POST['addr_id']?>" hidden>
    
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
        <input class="form-control" value="<?=@$_POST['city']?>" name='city'>
      </div>
      <?php $cq=$this->db->get('oc_country');
      if($cq->num_rows()>1){?>
          <div class="col-sm-4">
            <label>Country</label>
            <select class="form-control" name='country_id'>
            <?php 
                foreach($cq->result() as $c){?>
                <option value="<?=$c->country_id?>"><?=$c->name?></option>
                <?php }?>
            </select>
          </div>
      <?php }else{
      $c=$cq->result();?>
      <input hidden name="country_id" value="<?=$c[0]->country_id?>">
      <?php }?>
      <div class="col-sm-4">
        <label>Zone</label>
        <select class="form-control" name='zone_id'>
            <?php $rq=$this->db->get('zone_master')->result();
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
      <?php if(@$_GET['id'] && $_GET['id']){?>
        <button class="btn btn-primary mt-3 mt-sm-0" type="submit">Save</button>
        <?php }else{?>
        <button class="btn btn-primary mt-3 mt-sm-0" type="submit">Add</button>
        <?php }?>        
      </div>
    </div>
  </form>
</section>
  
  
  <form action="<?=site_url('cart/shipping')?>" method="post">
<div class="table-responsive">
    <table class="table table-hover font-size-sm border-bottom">
      <thead class="thead-dark">
        <tr>
          <th>Action</th>
          <th>Name</th>
          <th>Address</th>
          <th>Pincode</th>
          <th>State</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($shipqry->result() as $s){?>
        <tr>
          <td>
            <div class="custom-control custom-radio mb-4">
              <input class="custom-control-input" type="radio" id="ship<?=$s->addr_id?>" value="<?=$s->addr_id?>" name="addr[]" <?php if(@$_SESSION['addr_id']==$s->addr_id) echo "checked"?>>
              <label class="custom-control-label" for="ship<?=$s->addr_id?>"></label>
            </div>
          </td>
          <td><span class="text-muted"><label for="ship<?=$s->addr_id?>"><?=$s->name?> <?=$s->lname?></label></span></td>
          <td><span class="text-muted"><label for="ship<?=$s->addr_id?>"><?=$s->address?></label></span></td>
          <td><span class="text-dark font-weight-medium"><label for="ship<?=$s->addr_id?>"><?=$s->pin?><br><small><?=$s->city?></small></label></span></td>
          <td><span class="text-muted"><label for="ship<?=$s->addr_id?>"><?=$s->zname?></label></span></td>
          <td><a href="<?=site_url("cart/shipping?id=".$s->addr_id)?>" class="btn btn-secondary" >Edit</a><a href="<?=site_url("cart/shipping_del?id=".$s->addr_id)?>" class="red" >X</a></td>
        </tr>
        <?php }?>
      </tbody>
    </table>
  </div>
  <div class="col-sm-12">
      <div class=" d-lg-flex pt-4 mt-3 row">
        <div class="w-50 pr-3">
            <a class="btn btn-secondary btn-block" href="<?=site_url('cart/checkout')?>">
                <i class="czi-arrow-left mt-sm-0 mr-1"></i><span class="d-none d-sm-inline">Back to Cart</span>
                <span class="d-inline d-sm-none">Back</span>
            </a>
        </div>
        <div class="w-50 pl-2">
            <button class="btn btn-primary btn-block" type="submit">
                <span class="d-none d-sm-inline">Proceed to Payment</span><span class="d-inline d-sm-none">Next</span><i class="czi-arrow-right mt-sm-0 ml-1"></i>
            </button>
        </div>
      </div>
  </div>
  </form> 
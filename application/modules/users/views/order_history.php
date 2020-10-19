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
<section class="col-lg-8" id="ordersArea">
<div class="d-none d-lg-flex justify-content-between align-items-center pt-lg-3 pb-4 pb-lg-5 mb-lg-3">
<h6 class="font-size-base text-light mb-0">Order History</h6><a class="btn btn-primary btn-sm" href="<?=site_url('login/logout')?>"><i class="czi-sign-out mr-2"></i>Sign out</a>
</div>

<?php if(@$this->session->flashdata('msg')){
    echo "<div class='alert alert-success'>".$this->session->flashdata('msg')."</div>";
}?>
       <div class="accordion" id="accordionExample">
            <?php 
            $pay['0']="<b style='color:red'>(Failed)</b>";
            $pay['1']="<b style='color:green'>(Paid)</b>";
            $i=1;
            $orwhere['user_id']=$this->session->userdata('user_id');
            $query=$this->db->where($orwhere)->order_by('order_id','desc')->join('order_status','order_status_id','left')->join('order_shipping','order_id','left')->get("order_details");
            foreach ($query->result() as $o)
            {
            	$link=site_url("users/order_details/$o->order_id");?>     
              <div class="card">
                <div class="card-header" id="heading<?=$o->order_id?>">
                  <h2 class="mb-0">
                    <a class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapse<?=$o->order_id?>" aria-expanded="true" aria-controls="collapseOne" style="width: 100%;text-align: left;color: #fe696a;">
                      <b>#<?=$o->order_id?></b> <small>Total: &#8377; <b style="color: <?=$o->color?>" ><?=$o->total?></b> </small>   <div class="float-right"><small><i><?=$o->date_added?> </i></small> &nbsp; <b style="color: <?=$o->color?>"><?=$o->name?> </b></div>
                    </a>
                  </h2>
                </div>
            
                <div id="collapse<?=$o->order_id?>" class="collapse <?php if($i=='1') echo 'show'?>" aria-labelledby="heading<?=$o->order_id?>" data-parent="#accordionExample">
                  <div class="card-body">
                    <h5>Product Informations</h5>
                    <?php 
                    $wh['order_id']=$o->order_id;
                    $query=$this->db->select('order_product.name,img,mrp,sp,order_product.quantity as qty,order_product.p_id,options')->where($wh)->join('products','p_id')->join('order_details','order_id')->get('order_product');?>
                    <div class="table-responsive">
                        <table class="table table-striped" id="carttable">
                        <tr style="background: #ffebd4;">
                        <th>Product</th>
                        <th>Image</th>
                        <th>Price</th>
                        <th>Qty</th>
                        <th>Total</th>
                        </tr>
                        <?php 
                        $gtotal=0;
                        foreach ($query->result() as $c):
                            $rtotal=0;
                            $p_id=$c->p_id;
                            $product_link=site_url("item/view/$p_id" ); ?>
                            <tr>
                              <td>
                            	<?="<a href='$product_link' target='_blank' title='($p_id)'><b>".character_limiter($c->name,30)."</a></b> <br><small>(#ID: P20$p_id)</small>";?>
                            	<small><i><?=@$c->options?></i></small>
                              </td>
                              <td><a href="<?=$product_link?>"><img src="<?=base_url("assets/uploads/products/thumb/".$c->img)?>" style="height:50px;width:50px"></a></td>
                              <td>&#8377;<?=$this->cart->format_number($c->sp); ?></td>
                              <td><?=$c->qty?></td>
                              <td style="color: #0071ff">&#8377;<?=$rtotal=$c->qty*$c->sp;?></td>
                            </tr>
                            <?php 
                            $gtotal+=$rtotal;
                        endforeach;?>
                        <tfoot>
                        <tr>
                        <th colspan='4' style="text-align: right">Total</th>
                        <th>&#8377;<?=$gtotal?></th>
                        </tr>
                        <tr>
                        <th colspan='4' style="text-align: right">Shipping</th>
                        <th>+ &#8377;<?=$o->shipping?></th>
                        </tr>
                        <tr>
                        <th colspan='4' style="text-align: right">Coupon</th>
                        <th>- &#8377;<?=$o->coupon_disc?></th>
                        </tr>
                        <tr>
                        <th colspan='4' style="text-align: right">Grand Total</th>
                        <th>&#8377;<?=$o->total?></th>
                        </tr>
                        </tfoot>
                        </table>
                    </div>
                    
          <div class="col">          
                <ol class="progress-meter">
                    <li class="progress-point done" data-placement="top" data-toggle="popover" data-trigger="hover" title="Ordered on" data-content="<?=$o->date_added?>">Ordered</li>
                <?php 
                $process='todo';$processd='';
                $ship='todo';$shipd='';;
                $complete='todo';$completed='';;
                
                $ost=$this->db->where($wh)->join('order_status','order_status_id')->order_by('id')->get('order_status_records');
                if($ost->num_rows()>0 || $o->order_status_id=='1'){
                    foreach($ost->result() as $os){
                        if($os->order_status_id=='5'){//complete
                            $complete='done';
                            $completed=$os->comment ."<br><small>".$os->timestamp."</small>";
                        }
                        elseif($os->order_status_id=='2'){//Processing
                            $process='done';
                            $processd=$os->comment ."<br><small>".$os->timestamp."</small>";
                        }
                        elseif($os->order_status_id=='3'){//shipped
                            $ship='done';
                            $shipd=$os->comment ."<br><small>".$os->timestamp."</small>";
                        }
                        
                        if($complete=='done'){
                            $ship=$process='done';
                        }
                        if($ship=="done")
                            $process='done';
                    }
                ?>
                    <li class="progress-point <?=$process?>" data-html="true" <?php if($process=='done'){?>data-placement="top" data-toggle="popover" data-trigger="hover" title="Processing on" data-content="<?=$processd?>"<?php }?>>Processing</li>
                    <li class="progress-point <?=$ship?>" data-html="true" <?php if($ship=='done'){?>data-placement="top" data-toggle="popover" data-trigger="hover" title="Shipped on" data-content="<?=$shipd?>" <?php }?>>Shipped</li>
                    <li class="progress-point <?=$complete?>" data-html="true" <?php if($complete=='done'){?>data-placement="top" data-toggle="popover" data-trigger="hover" title="Delivered on" data-content="<?=$completed?>" <?php }?>>Completed</li>
                    <?php }else{
//                         if($o->order_status_id=='1'){
                        ?>
                    <li class="progress-point done"><?=$o->name?></li>
                    <?php }?>
                  </ol>
                  <br>
                  <div class='row'>
                    <div class="col">
                        <h5>Shipping Informations</h5>
                        Address: <?=$o->address_1?><br>Pin:<?=$o->postcode?> <small><i><?=$o->city?></i></small><br>
                    </div>
                    <div class="col float-right">
                        <p>Payment Code: <i><?=ucfirst($o->payment_code)?></i> <?php if(@$o->payment_code=='online'){ echo $pay[$o->pay_status]; echo '<small><br>Transaction ID: <b>'.$o->txnid.'</b></small><br><br>';}?></p>
                    </div>
                  </div>
                  <br><br><hr>
          </div>             
                    
                  </div>
                </div>
              </div>
          <?php $i++;}?>
        </div>
</section>
</div></div>
  <style>
  #carttable th, #carttable td {padding: .35rem;}
  tfoot tr th{padding:2px 20px !important;font-size:14px}
  .float-right{text-align:right}
  .popover{box-shadow: rgba(49, 49, 49, 0.73) 2px 2px 5px;}
  .progress-meter {
  padding: 0;
}

ol.progress-meter {
  padding-bottom: 9.5px;
  list-style-type: none;
}
ol.progress-meter li {
  display: inline-block;
  text-align: center;
  text-indent: -19px;
  height: 36px;
  width: 24%;
  font-size: 12px;
  border-bottom-width: 4px;
  border-bottom-style: solid;
}
ol.progress-meter li:before {
  position: relative;
  float: left;
  text-indent: 0;
  left: -webkit-calc(50% - 9.5px);
  left: -moz-calc(50% - 9.5px);
  left: -ms-calc(50% - 9.5px);
  left: -o-calc(50% - 9.5px);
  left: calc(50% - 9.5px);
}
ol.progress-meter li.done {
  font-size: 12px;
}
ol.progress-meter li.done:before {
  content: "\2713";
  height: 19px;
  width: 19px;
  line-height: 21.85px;
  bottom: -25.175px;
  border: none;
  border-radius: 19px;
}
ol.progress-meter li.todo {
  font-size: 12px;
}
ol.progress-meter li.todo:before {
  content: "\2719";
  font-size: 17.1px;
  bottom: -25.175px;
  line-height: 18.05px;
}
ol.progress-meter li.done {
  color: black;
  border-bottom-color: #3a9c40;
}
ol.progress-meter li.done:before {
  color: white;
  background-color: #3a9c40;
}
ol.progress-meter li.todo {
  color: silver;
  border-bottom-color: silver;
}
ol.progress-meter li.todo:before {
  color: silver;
}
</style>
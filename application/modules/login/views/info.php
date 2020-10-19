<div class="bg-dark py-4">
      <div class="container d-lg-flex justify-content-between py-2 py-lg-3">
        <div class="order-lg-2 mb-3 mb-lg-0 pt-lg-2">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb flex-lg-nowrap justify-content-center justify-content-lg-start">
              <li class="breadcrumb-item"><a class="text-nowrap" href="<?=site_url('home')?>"><i class="czi-home"></i>Home</a></li>
              <li class="breadcrumb-item text-nowrap active" aria-current="page">Info</li>
            </ol>
          </nav>
        </div>
        <div class="order-lg-1 pr-lg-4 text-center text-lg-left">
          <h1 class="h3 mb-0">Information</h1>
        </div>
      </div>
    </div>
    <!-- Page Content-->
    <!-- Contact detail cards-->
    <section class="container-fluid pt-grid-gutter">
      <div class="row">
        <div class="col-xl-3"></div>
        <div class="col-xl-6">
            <?php 
            if($this->input->get('e'))
                $em="<h3>Email: ".$this->input->get('e')."</h3>";
            ?>
            <?php if(@$this->session->flashdata('fmsg')){
                echo "<br><div class='alert alert-info'>$em<br>".$this->session->flashdata('fmsg')."</div>";
            }?>
            <br><br><br><br><br>
        </div>
        <div class="col-xl-3"></div>
      </div>
    </section>
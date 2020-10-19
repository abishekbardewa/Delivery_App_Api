<div class="row">
    <div class="col-sm-4">
        <label>Pin</label>
        <input class="form-control" value="<?=@$_POST['pin']?>" name='pin'>
    </div>
    <div class="col-sm-4">
        <label>City</label>
        <input class="form-control" value="SILIGURI" readonly name='city'>
      </div>
      <div class="col-sm-4">
        <label>State</label>
        <input class="form-control" value="WB" readonly name='state'>
      </div>
      <div class="col-sm-4">
        <label>Country</label>
        <input class="form-control" value="INDIA" readonly name='country'>
      </div>
    <div class="col-sm-4">
        <label>Address</label>
        <textarea class="form-control" name='address'><?=@$_POST['address']?></textarea>
    </div>
</div>
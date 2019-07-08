<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Perangkat 
        <small>Detail Perangkat</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Perangkat</a></li>
        <li class="">Data Perangkat</li>
        <li class="active">Detail Perangkat</li>
      </ol>
    </section>
    <section class="content">
      <!-- Small boxes (Stat box) -->
      <div class="row">
        <div class="col-lg-12 col-xs-12">
        <div class="box box-danger">
        <div class="box-header with-border">
          <h3 class="box-title">Status</h3>
        </div>
          <div class="box-body">
            <div class="col-lg-4 col-xs-4">
              <table width="100%">
                <tr>
                  <td>Nama Perangkat</td>
                  <td>:
                    <?php 
                    foreach ($detail as $detail) {
                        $id = $detail['id_perangkat']; ?>
                        <?php echo "<b>".$detail['nama_perangkat']."<b>";?><br>
                    <?php    
                    }
                    ?>
                  </td>
                </tr>
                <tr>
                  <td>Uptime</td>
                  <td>: <span id="uptime" style="font-weight:bold"></span></td>
                </tr>
              </table>
            </div>
            <div class="col-lg-4 col-xs-4" align="center">
              <div class="knob-label" style="font-weight:bold">Memmory Usage</div><br>
                  <input type="text" id="knob" class="knob" value="30" data-width="90" data-height="90" data-max=<?php echo $snmp['totmem'];?> data-fgColor="#3c8dbc" data-readonly="true"/>              
                  <div class="knob-label" style="font-weight:bold">Total : <?php echo $snmp['totmem']." Kb";?></div>
              </div>
            <div class="col-lg-4 col-xs-4" align="center">
              <div class="knob-label" style="font-weight:bold">CPU Load</div><br>
                  <input type="text" id="knob1" class="knob1" value="30" data-width="90" data-height="90" data-max="100" data-fgColor="#3c8dbc" data-readonly="true"/> 
                  <div class="knob-label" style="font-weight:bold">Persen(%)</div> 
              </div>
            
            <!-- Knob Graph-->
            <br><br>
            
            <br><br>
            <br><br>
                     
            <br><br>
            <!-- End Knob Graph-->
          
            <!--
              Max Memmory : <span id="totmem" ></span><br>
              Used Memmory : <span id="usedmem" ></span>
            -->
            <br><br>
            </div>
          </div>
           <div class="box box-info">
          <div class="box-header with-border">
            <h3 class="box-title">Data Interface</h3>
          </div>
          <div class="box-body">

            <table id="detail_if" class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Nama Interface</th>
                  <th>Status</th>
                  <th>IP Address</th>
                  <th>CIDR</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <?php $i=1;foreach ($data_id as $data) { 
                  ?>
                  <tr>
                    <td><?php echo $i; ?></td>
                    <td><?php echo $data['nama_interface']; ?></td>
                    <td><?php echo $data['status'];?></td>
                    <td><?php echo $data['ip_address'];?></td>
                    <td><?php echo $data['cidrr'];?></td>
                    <td>
                      <a href="<?php echo base_url();?>device/detail_if?id_if=<?php echo $data['interface_index']; ?>&id_per=<?php echo $data['id_perangkat'];?>" class="btn btn-success">Detail</a>
                    </td>
                  </tr>
                <?php $i++ ;} ?>
              </tbody>
            </table>
        </div>
        </div>
        <a <?php if ($session['role']==2)echo 'disabled'; ?> href="<?php echo base_url();?>device/scan_interface?id=<?php echo $id; ?>" class="btn btn-primary" id="<?php #echo $a['id_perangkat']; ?>">Scan Interface</a>
      </div>
    </section>
</div><!-- /.content-wrapper -->

<script>
//$(document).ready(function(){
    $(function () {
        /* jQueryKnob */

        $(".knob").knob({
        });
        $(".knob1").knob({
        });
        /* END JQUERY KNOB */

      });

  function loadlink(){
    $.ajax({
        url:"../operation/uptime",              
        dataType : "json",
        type: "POST",

        success: function(data){
          //document.getElementById("uptime").value = data[0];
          $('#uptime').html(data['uptime']);
          //document.getElementById("knob").setAttribute("value", data['usedmem'].replace(/[INTEGER: ]/gi, ''));
          //document.getElementById("knob").setAttribute("data-max", data['totmem'].replace(/[INTEGER: ]/gi, ''));
          //$('#usedmem').html(data['usedmem'].replace(/[INTEGER: ]/gi, ''));
          //$('#totmem').html(data['totmem'].replace(/[INTEGER: ]/gi, ''));
          //Fungsi mngganti value knob 
          $('.knob')
            .val(data['usedmem'].replace(/[INTEGER: ]/gi, ''))
            .trigger('change');
          $('.knob1')
            .val(data['cpuload'].replace(/[INTEGER: ]/gi, ''))
            .trigger('change');
          }
    }); 
  }

  loadlink(); // This will run on page load
  setInterval(function(){
      loadlink() // this will run after every 5 seconds
  }, 3000);
  //});
</script>

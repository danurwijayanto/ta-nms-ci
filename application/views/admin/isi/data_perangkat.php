
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Data Perangkat 
        <small></small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Perangkat</a></li>
        <li class="active">Data Perangkat</li>
      </ol>
    </section>
    <section class="content">
      <!-- Small boxes (Stat box) -->
      <div class="row">
        <div class="col-lg-12 col-xs-12">
          <div class="box">
          <div class="box-body">
            <table id="dat_per" class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Nama Perangkat</th>
                  <th>Alamat IP</th>
                  <!-- <th>Lokasi</th> -->
                  <!-- <th>Community</th> -->
                  <!-- <th>Versi SNMP</th> -->
                  <th>Status</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                <?php $i=1;foreach ($data_perangkat as $a) { 
                  ?>
                  <tr>
                    <td><?php echo $i;?></td>
                    <td><?php echo $a['nama_perangkat'];?></td>
                    <td><?php echo $a['ip_address'];?></td>
                    <!-- <td><?php //echo $a['lokasi'];?></td> -->
                    <!-- <td><?php //echo $a['community'];?></td> -->
                    <!-- <td><?php //echo $a['ver_snmp'];?></td> -->
                    <td><?php echo $a['status']; ?></td>
                    <td>
                      <a <?php if ($session['role']==2)echo 'disabled'; ?> class="btn btn-primary edit_device" data-toggle="modal" data-target="#edit_device" id="<?php echo $a['id_perangkat']; ?>">Edit</a>
                      <a href="<?php echo base_url();?>device/detail_perangkat?id=<?php echo $a['id_perangkat']; ?>" class="btn btn-success">Detail</a>
                      <a <?php if ($session['role']==2)echo 'disabled'; ?> href="<?php echo base_url();?>device/hapus_perangkat?id=<?php echo $a['id_perangkat']; ?>" class="btn btn-danger">Hapus</a>
                    </td>
                  </tr>
                <?php $i++ ;} ?>
              </tbody>
            </table>
        </div>
        </div>
        <button <?php if ($session['role']==2)echo 'disabled'; ?> type="button" class="btn btn-warning" data-toggle="modal" data-target="#tambah_device">Tambah Perangkat</button>
      </div>
    </section>
</div><!-- /.content-wrapper -->

<!-- Modal Tambah Device -->
<div class="modal fade" id="tambah_device" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Tambah Perangkat</h4>
      </div>
      <div class="modal-body">
        <form class="form-horizontal" role="form" method="post" action="<?php echo base_url();?>index.php/welcome/tambah_perangkat">
          <div class="form-group">
            <label class="control-label col-sm-2" for="nama_perangkat">Nama Perangkat:</label>
            <div class="col-sm-10">
              <input type="text" class="form-control" name="nama_perangkat" id="nama_perangkat" placeholder="Nama Perangkat">
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-sm-2" for="ip">Alamat IP:</label>
            <div class="col-sm-10"> 
              <input type="text" class="form-control" name="ip" id="ip" placeholder="Alamat IP Perangkat">
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-sm-2" for="ver">Versi SNMP:</label>
            <div class="col-sm-10"> 
              <input type="text" class="form-control" name="ver" id="ver" value="v1" readonly>
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-sm-2" for="community">Community:</label>
            <div class="col-sm-10"> 
              <input type="text" class="form-control" name="community" id="community" placeholder="Nama Community SNMP">
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-sm-2" for="lokasi">Lokasi Perangkat:</label>
            <div class="col-sm-10"> 
              <input type="text" class="form-control" name="lokasi" id="lokasi" placeholder="Nama Community SNMP">
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-sm-2" for="os">OS :</label>
            <div class="col-sm-10"> 
              <select class="form-control" name="os" id="os">
                <option value="0">-- Pilih OS --</option>
                <option value="mikrotik">Mikrotik OS</option>
                <option value="linux">Linux</option>
              </select>
            </div>
          </div>
          <div class="form-group"> 
            <div class="col-sm-offset-2 col-sm-10">
              <button type="submit" class="btn btn-default">Submit</button>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<!-- End Modal Tambah Device -->

<!-- Modal Edit Device -->
<div class="modal fade" id="edit_device" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Edit Perangkat</h4>
      </div>
      <div class="modal-body">
        <form class="form-horizontal" role="form" method="post">
          <div class="form-group">
            <label class="control-label col-sm-2" for="nama_perangkat">Nama Perangkat:</label>
            <div class="col-sm-10">
              <input type="text" class="form-control" name="nama_perangkat1" id="nama_perangkat1" >
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-sm-2" for="ip">Alamat IP:</label>
            <div class="col-sm-10"> 
              <input type="text" class="form-control" name="ip1" id="ip1" >
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-sm-2" for="ver">Versi SNMP:</label>
            <div class="col-sm-10"> 
              <input type="text" class="form-control" name="ver1" id="ver1" readonly>
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-sm-2" for="community">Community:</label>
            <div class="col-sm-10"> 
              <input type="text" class="form-control" name="community1" id="community1" >
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-sm-2" for="lokasi">Lokasi Perangkat:</label>
            <div class="col-sm-10"> 
              <input type="text" class="form-control" name="lokasi1" id="lokasi1" >
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-sm-2" for="os1">OS :</label>
            <div class="col-sm-10"> 
              <select class="form-control" name="os1" id="os1">
                <option value="0">-- Pilih OS --</option>
                <option value="mikrotik">Mikrotik OS</option>
                <option value="linux">Linux</option>
              </select>
            </div>
          </div>
          <div class="form-group"> 
            <div class="col-sm-offset-2 col-sm-10">
              <button type="submit" class="btn btn-default simpan_edit_device">Rubah</button>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<!-- End Modal Edit Device -->

<!-- Fungsi Ajax -->
<script>
            
  //Menampilkan kategori di modal sebelum dirubah 
  $(document).ready(function(){
    var id;
    $(".edit_device").click(function(){
      var element = $(this);
      id = element.attr("id");
     
      $.ajax({
        url:"../device/get_perangkat?id="+id,              
        dataType : "json",
        type: "POST",

        success: function(data){
          document.getElementById("nama_perangkat1").value = data[0].nama_perangkat;
          document.getElementById("ip1").value = data[0].ip_address;
          document.getElementById("ver1").value = data[0].ver_snmp;
          document.getElementById("community1").value = data[0].community;
          document.getElementById("lokasi1").value = data[0].lokasi;
          document.getElementById("os1").value = data[0].os;
          //document.form_ganti_kat.action = "../operation/ganti_kategori?id="+id;   
       }
      });                        
    });

    //Menyimpan kategori baru telah dirubah
    $(".simpan_edit_device").click(function(){
      var myData = 'nama_perangkat=' + document.getElementById("nama_perangkat1").value 
                    + '&ip_address=' + document.getElementById("ip1").value
                    + '&lokasi=' + document.getElementById("lokasi1").value
                    + '&community=' + document.getElementById("community1").value 
                    + '&os=' + document.getElementById("os1").value ;
      
      $.ajax({
        url:"../device/simpan_edit_perangkat?id="+id,              
        dataType : "json",
        data : myData,
        type: "POST",
        // success: success()       
      });                        
    });

    // on success...
    function success(){
      alert('Perubahan Berhasil');
      redirect(site_url("device/data_perangkat"));
      //location.reload(); 
    }
  });

  </script>
<!-- End Fungsi Ajax-->




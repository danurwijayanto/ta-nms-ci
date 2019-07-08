
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

                <?php 
                  if (empty($data_perangkat)) { ?>
                    <tr>
                      <td colspan="5"> Tidak ada data</td>
                    </tr>
                  <?php } else { 
                  $i=1;
                  foreach ($data_perangkat as $a) { 
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
                      <a href="<?php echo base_url();?>controlperangkat/detail_perangkat?id=<?php echo $a['id_perangkat']; ?>" class="btn btn-success">Detail</a>
                      <a <?php if ($session['role']==2 || $a['id_perangkat']==1)echo 'disabled'; ?> href="<?php echo base_url();?>controlperangkat/hapus_perangkat?id=<?php echo $a['id_perangkat']; ?>" class="btn btn-danger" onclick="konfirmasihapus()">Hapus</a>
                    </td>
                  </tr>
                <?php $i++ ;} } ?>
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
        <form class="form-horizontal" id="perangkat" name="perangkat" role="form" method="post" action="<?php echo base_url();?>index.php/controlperangkat/tambah_perangkat" onsubmit="return validatetambahperangkat()">
          <div class="form-group">
            <label class="control-label col-sm-2" for="nama_perangkat">Nama Perangkat:</label>
            <div class="col-sm-10">
              <input type="text" class="form-control" name="nama_perangkat" id="nama_perangkat" placeholder="Nama Perangkat" required maxlength="30">
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-sm-2" for="ip">Alamat IP:</label>
            <div class="col-sm-10"> 
              <input type="text" class="form-control ip" name="ip" id="ip" placeholder="Alamat IP Perangkat" required maxlength="15">
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-sm-2" for="ver">Versi SNMP:</label>
            <div class="col-sm-10"> 
              <select class="form-control" class="ver" name="ver" id="ver">
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
              </select>
            </div>
            <!-- <div class="col-sm-10"> 
              <input type="text" class="form-control" name="ver" id="ver" value="v1" readonly>
            </div> -->
          </div>
        <div id="versi3">
          <div class="form-group">
            <label class="control-label col-sm-2" for="auth">Type:</label>
            <div class="col-sm-10"> 
              <select class="form-control" class="type" name="type" id="type">
                <option value="authPriv">authPriv</option>
                <option value="authNoPriv">authNoPriv</option>
                <option value="noAuthNoPriv">noAuthNoPriv</option>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-sm-2" for="authprot">Auth Protocol:</label>
            <div class="col-sm-10"> 
              <select class="form-control" class="authprot" name="authprot" id="authprot">
                <option value="MD5">MD5</option>
                <option value="SHA1">SHA1</option>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-sm-2" for="enprot">Encrypt Protocol:</label>
            <div class="col-sm-10"> 
              <select class="form-control" class="enprot" name="enprot" id="enprot">
                <option value="AES">AES</option>
                <option value="DES">DES</option>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-sm-2" for="authpass">Auth Password:</label>
            <div class="col-sm-10"> 
              <input type="text" class="form-control" name="authpass" id="authpass" placeholder="Authentication Password" pattern=".{8,}" title="Minimum 8 karakter" maxlength="15">
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-sm-2" for="encryptpass">Encrypt Password:</label>
            <div class="col-sm-10"> 
              <input type="text" class="form-control" name="encryptpass" id="encryptpass" placeholder="Encryption Password" pattern=".{8,}" title="Minimum 8 karakter" maxlength="15">
            </div>
          </div>
        </div>
          <div class="form-group">
            <label class="control-label col-sm-2" for="community">Community:</label>
            <div class="col-sm-10"> 
              <input type="text" class="form-control" name="community" id="community" placeholder="Nama Community SNMP" required maxlength="15">
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-sm-2" for="lokasi">Lokasi Perangkat:</label>
            <div class="col-sm-10"> 
              <input type="text" class="form-control" name="lokasi" id="lokasi" placeholder="Lokasi Perangkat" required maxlength="30">
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-sm-2" for="os">OS :</label>
            <div class="col-sm-10"> 
              <select class="form-control" class="os" name="os" id="os">
                <option value="0">-- Pilih OS --</option>
                <option value="mikrotik">Mikrotik OS</option>
                <option value="linux">Linux</option>
              </select>
            </div>
          </div>
          <div class="form-group"> 
            <div class="col-sm-offset-2 col-sm-10">
              <button type="submit" class="btn btn-primary" id="button" >Submit</button>
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
        <form class="form-horizontal" role="form" method="post" name="editperangkat">
          <div class="form-group">
            <label class="control-label col-sm-2" for="nama_perangkat1">Nama Perangkat:</label>
            <div class="col-sm-10">
              <input type="text" class="form-control" name="nama_perangkat1" id="nama_perangkat1" required>
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-sm-2" for="ip1">Alamat IP:</label>
            <div class="col-sm-10"> 
              <input type="text" class="form-control ip" name="ip1" id="ip1" required>
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-sm-2" for="ver1">Versi SNMP:</label>
            <div class="col-sm-10"> 
              <select class="form-control" class="ver1" name="ver1" id="ver1">
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
              </select>
            </div>
            <!-- <div class="col-sm-10"> 
              <input type="text" class="form-control" name="ver1" id="ver1" >
            </div> -->
          </div>
        <div id="versi31">
          <div class="form-group">
            <label class="control-label col-sm-2" for="type1">Type:</label>
            <div class="col-sm-10"> 
              <select class="form-control" class="type1" name="type1" id="type1">
                <option value="authPriv">authPriv</option>
                <option value="authNoPriv">authNoPriv</option>
                <option value="noAuthNoPriv">noAuthNoPriv</option>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-sm-2" for="authprot1">Auth Protocol:</label>
            <div class="col-sm-10"> 
              <select class="form-control" class="authprot1" name="authprot1" id="authprot1">
                <option value="MD5">MD5</option>
                <option value="SHA1">SHA1</option>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-sm-2" for="enprot1">Encrypt Protocol:</label>
            <div class="col-sm-10"> 
              <select class="form-control" class="enprot1" name="enprot1" id="enprot1">
                <option value="MD5">AES</option>
                <option value="DES">DES</option>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-sm-2" for="authpass1">Auth Password:</label>
            <div class="col-sm-10"> 
              <input type="text" class="form-control" name="authpass1" id="authpass1" placeholder="Authentication Password"  pattern=".{8,}" title="Minimum 8 karakter" maxlength="15">
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-sm-2" for="encryptpass1">Encrypt Password:</label>
            <div class="col-sm-10"> 
              <input type="text" class="form-control" name="encryptpass1" id="encryptpass1" placeholder="Encryption Password" pattern=".{8,}" title="Minimum 8 karakter" maxlength="15">
            </div>
          </div>
        </div>
          <div class="form-group">
            <label class="control-label col-sm-2" for="community1">Community:</label>
            <div class="col-sm-10"> 
              <input type="text" class="form-control" name="community1" id="community1" required>
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-sm-2" for="lokasi1">Lokasi Perangkat:</label>
            <div class="col-sm-10"> 
              <input type="text" class="form-control" name="lokasi1" id="lokasi1" required>
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-sm-2" for="os1">OS :</label>
            <div class="col-sm-10"> 
              <select class="form-control" class="os" name="os1" id="os1">
                <option value="0">-- Pilih OS --</option>
                <option value="mikrotik">Mikrotik OS</option>
                <option value="linux">Linux</option>
              </select>
            </div>
          </div>
          <div class="form-group"> 
            <div class="col-sm-offset-2 col-sm-10">
              <button type="submit" class="btn btn-primary simpan_edit_device" id="button1">Rubah</button>
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
  function validatetambahperangkat() {
    var namaperangkat = document.forms["perangkat"]["nama_perangkat"].value;
    var community = document.forms["perangkat"]["community"].value;
    var lokasi = document.forms["perangkat"]["lokasi"].value;
    var os = document.forms["perangkat"]["os"].value;
    if (namaperangkat.length>30){
        alert("Nama perangkat tidak boleh dari 30 karakter")
        return false
    }if (community>15){
        alert("Community tidak boleh dari 30 karakter")
        return false
    }if (lokasi>30){
        alert("Lokasi tidak boleh dari 30 karakter")
        return false
    }
    if (os == null || os == "0") {
        alert("Periksa pilihan OS");
        return false;
    }
  }

  function konfirmasihapus() {
        var txt;
        var r = confirm("Ingin Menghapus ?");
        if (r == true) {

            document.getElementById("hapus").href="<?php echo base_url();?>controlperangkat/hapus_perangkat?id=<?php echo @$a['id_perangkat']; ?>"; 
            return false;
        } else {
           return;
        }
        // document.getElementById("demo").innerHTML = txt;
    }
  //Menampilkan kategori di modal sebelum dirubah 
  $(document).ready(function(){
    var id;
    document.getElementById("versi3").style.display="none";
    // document.getElementById("versi31").style.display="none";
    // Validasi 
    // $("#nama_perangkat").change(function(){
    //        var namaperangkat   = $("#nama_perangkat").val();
    //        if(namaperangkat.length>30)  
    //        {  
    //           alert("Nama perangkat tidak boleh dari 30 karakter")
    //           document.getElementById("button").disabled = true;
    //           document.getElementById("button1").disabled = true;  
    //           return false;  
    //        }  else {
    //           document.getElementById("button").disabled = false;
    //           document.getElementById("button1").disabled = false; 
    //           return true
    //        }
    // });
    // $("#community").change(function(){
    //        var community   = $("#community").val();
    //        if(community.length>30)  
    //        {  
    //           alert("Community tidak boleh dari 30 karakter")
    //           document.getElementById("button").disabled = true;
    //           document.getElementById("button1").disabled = true;  
    //           return false;  
    //        }  else {
    //           document.getElementById("button").disabled = false;
    //           document.getElementById("button1").disabled = false; 
    //           return true
    //        }
    // });

    // Validasi IP ADDRESS
    $("#ip, #ip1").change(function(){
           var ip   = $("#ip").val();
           var ip1   = $("#ip1").val();
           var ipformat = /^(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/;  
           if(ip.match(ipformat) || ip1.match(ipformat))  
           {  
             document.perangkat.ip.focus();
             document.editperangkat.ip1.focus();
             document.getElementById("button").disabled = false;
             document.getElementById("button1").disabled = false;  
             return true;  
           }  
           else  
           {  
             alert("Alamat ip salah");  
             document.perangkat.ip.focus();
             document.editperangkat.ip1.focus();
             document.getElementById("button").disabled = true;
             document.getElementById("button1").disabled = true;
             return false;  
           }  
    });

    // Pemilihan Versi SNMP
    $("#ver").change(function(){
      ver = document.forms["perangkat"]["ver"].value;
      if (ver == 1 || ver == 2){
         document.getElementById("versi3").style.display="none";
      }else {
         document.getElementById("versi3").style.display="block";
      }
    });
    $("#ver1").change(function(){
      ver = document.forms["editperangkat"]["ver1"].value;
      if (ver == 1 || ver == 2){
         document.getElementById("versi31").style.display="none";
      }else {
         document.getElementById("versi31").style.display="block";
      }
    });
    // // Validasi options
    // document.getElementsByName('os')[0].onchange = function() {
    //  if (this.value=='0') alert('Pilih OS yang digunakan');
    // }

    // document.getElementsByName('os1')[0].onchange = function() {
    //  if (this.value=='0') alert('Pilih OS yang digunakan');
    // }

    $(".edit_device").click(function(){
      var element = $(this);
      id = element.attr("id");
     
      $.ajax({
        url:"../controlperangkat/get_perangkat?id="+id,              
        dataType : "json",
        type: "POST",

        success: function(data){
          document.getElementById("nama_perangkat1").value = data[0].nama_perangkat;
          document.getElementById("ip1").value = data[0].ip_address;
          document.getElementById("ver1").value = data[0].ver_snmp;
          document.getElementById("type1").value = data[0].type;
          document.getElementById("authprot1").value = data[0].authprot;
          document.getElementById("enprot1").value = data[0].encryptprot;
          document.getElementById("authpass1").value = data[0].authpass;
          document.getElementById("encryptpass1").value = data[0].encryptpass;
          document.getElementById("community1").value = data[0].community;
          document.getElementById("lokasi1").value = data[0].lokasi;
          document.getElementById("os1").value = data[0].os;
          if (data[0].ver_snmp == 1 || data[0].ver_snmp == 2){
           document.getElementById("versi31").style.display="none";
          }else {
             document.getElementById("versi31").style.display="block";
          }
          //document.form_ganti_kat.action = "../operation/ganti_kategori?id="+id;   
       }
      });                      
    });

    //Menyimpan kategori baru telah dirubah
    $(".simpan_edit_device").click(function(){
      var x = document.forms["editperangkat"]["os1"].value;
      if (x == null || x == "0") {
        alert("Periksa pilihan OS");
        return false;

      }else {
        var myData = 'nama_perangkat=' + document.getElementById("nama_perangkat1").value 
                    + '&ip_address=' + document.getElementById("ip1").value
                    + '&lokasi=' + document.getElementById("lokasi1").value
                    + '&community=' + document.getElementById("community1").value 
                    + '&ver_snmp=' + document.getElementById("ver1").value 
                    + '&type=' + document.getElementById("type1").value 
                    + '&authprot=' + document.getElementById("authprot1").value 
                    + '&encryptprot=' + document.getElementById("enprot1").value 
                    + '&authpass=' + document.getElementById("authpass1").value 
                    + '&encryptpass=' + document.getElementById("encryptpass1").value 
                    + '&os=' + document.getElementById("os1").value ;
      
        $.ajax({
          url:"../controlperangkat/simpan_edit_perangkat?id="+id,              
          dataType : "json",
          data : myData,
          type: "POST",
          // success: success()       
        }); 
      }                       
    });

    // on success...
    function success(){
      alert('Perubahan Berhasil');
      redirect(site_url("controlperangkat/data_perangkat"));
      //location.reload(); 
    }
  });
  </script>
<!-- End Fungsi Ajax-->




<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Daftar Pengguna
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Pengguna</a></li>
        <li class="active">Daftar Pengguna</li>
      </ol>
    </section>
    <section class="content">
      <!-- Small boxes (Stat box) -->
      <div class="row">
        <div class="col-lg-12 col-xs-12">
        <div class="box box-info">
          <div class="box-body">
            <table id="log_squid" class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Username</th>
                  <th>Email</th>
                  <th>Role</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                <?php $i=1;foreach ($user as $user) { 
                    //GETTING DOMAIN USING PREG MATCH
                    // get host name from URL
                  ?>
                  <tr>
                    <td><?php echo $i;?></td>
                    <td><?php echo $user['username'];?></td>
                    <td><?php echo $user['email'];?></td>
                    <td><?php if ($user['role']==1){echo "Administrator";}else{echo "Manager";};?></td>
                    <td>
                      <a class="btn btn-primary edit_user" data-toggle="modal" data-target="#edit_user" id="<?php echo $user['id_user']; ?>">Edit</a>
                      <a href="<?php echo base_url();?>user/hapus_user?id=<?php echo $user['id_user']; ?>" class="btn btn-danger" <?php if($user['id_user'] == 1)echo "disabled"; ?> >Hapus</a>
                    </td>
                  </tr>
                <?php $i++ ;} ?>
              </tbody>
            </table>
        </div>
      </div>
        <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#tambah_user">Tambah Pengguna</button>
      </div>
    </section>

<!-- Modal Tambah Device -->
<div class="modal fade" id="tambah_user" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Tambah Pengguna</h4>
      </div>
      <div class="col-sm-12">
                  <div class="alert alert-warning" id="peringatan">
                    <Strong>Peringatan !</Strong>  Cek Kembali Password dan Konfirmasi Password
                  </div>
                </div>
      <div class="modal-body">
        <form class="form-horizontal" role="form" method="post" action="<?php echo base_url();?>index.php/controlpengguna/tambah_user">
          <div class="form-group">
            <label class="control-label col-sm-2" for="username">Username</label>
            <div class="col-sm-10">
              <input type="text" class="form-control" name="username" id="username" placeholder="Username" required>
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-sm-2" for="email">Alamat Email</label>
            <div class="col-sm-10"> 
              <input type="text" class="form-control" name="email" id="email" placeholder="Alamat Email" required>
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-sm-2" for="role">Role</label>
            <div class="col-sm-10"> 
              <select class="form-control" name="role" id="role" required>
                <option value="0">-- Pilih Role --</option>
                <option value="1">Administrator</option>
                <option value="2">Manager</option>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-sm-2" for="password">Password</label>
            <div class="col-sm-10"> 
              <input type="password" class="form-control" name="password" id="password" placeholder="Password" required>
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-sm-2" for="cpassword">Confirm Password</label>
            <div class="col-sm-10"> 
              <input type="password" class="form-control" name="cpassword" id="cpassword" placeholder="Confirm Password" required>
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-sm-2" for="role"></label>
            <div class="col-sm-10"> 
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="notif" id="notif" class="notif"> Kirim notifikasi via email
                </label>
              </div>
            </div>
          </div>
          <div class="form-group"> 
            <div class="col-sm-offset-2 col-sm-10">
              <button type="submit" class="btn btn-default">Simpan</button>
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
<div class="modal fade" id="edit_user" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Edit Pengguna</h4>
      </div>
      <div class="col-sm-12">
                  <div class="alert alert-warning" id="peringatan">
                    <Strong>Peringatan !</Strong>  Cek Kembali Password dan Konfirmasi Password
                  </div>
                </div>
      <div class="modal-body">
        <form class="form-horizontal" role="form" method="post" >
          <div class="form-group">
            <label class="control-label col-sm-2" for="username">Username</label>
            <div class="col-sm-10">
              <input type="text" class="form-control" name="username" id="username1" placeholder="Username" required>
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-sm-2" for="email">Alamat Email</label>
            <div class="col-sm-10"> 
              <input type="text" class="form-control" name="email" id="email1" placeholder="Alamat Email" required>
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-sm-2" for="role">Role</label>
            <div class="col-sm-10"> 
              <select class="form-control" name="role" id="role1" required>
                <option value="0">-- Pilih Role --</option>
                <option value="1">Administrator</option>
                <option value="2">Manager</option>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-sm-2" for="password">Password</label>
            <div class="col-sm-10"> 
              <input type="password" class="form-control" name="password" id="password1" placeholder="Password" required>
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-sm-2" for="cpassword">Confirm Password</label>
            <div class="col-sm-10"> 
              <input type="password" class="form-control" name="cpassword" id="cpassword1" placeholder="Confirm Password" required>
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-sm-2" for="role"></label>
            <div class="col-sm-10"> 
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="notif1" id="notif1" class="notif1" > Kirim notifikasi via email
                </label>
              </div>
            </div>
          </div>
          <div class="form-group"> 
            <div class="col-sm-offset-2 col-sm-10">
              <button type="submit" class="btn btn-default simpan_edit_user" >Rubah</button>
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
<!-- End Modal Edit User-->

</div><!-- /.content-wrapper -->

<script type="text/javascript">
//Datatables
$(document).ready(function(){
    
    //Validasi password
    $(".alert").hide();  
    $("#password, #cpassword").change(function(){
           var password   = $("#password").val();
           var cpassword    = $("#cpassword").val();
           if(password!=cpassword){
            //alert('Password Didnt Match');
            $(".alert").show();            
           }else{
             $(".alert").hide(); 
           }
    });

    $("#password1, #cpassword1").change(function(){
           var password   = $("#password1").val();
           var cpassword    = $("#cpassword1").val();
           if(password!=cpassword){
            //alert('Password Didnt Match');
            $(".alert").show();            
           }else{
             $(".alert").hide(); 
           }
    });

    //Menampilkan form edit user
    var id;
    $(".edit_user").click(function(){
      var element = $(this);
      id = element.attr("id");
     
      $.ajax({
        url:"../controlpengguna/get_user?id="+id,              
        dataType : "json",
        type: "POST",

        success: function(data){
          document.getElementById("username1").value = data[0].username;
          document.getElementById("email1").value = data[0].email;
          document.getElementById("role1").value = data[0].role;
          // Pengecekan notifikasi
          if(data[0].notif == 1){
            document.getElementById("notif1").checked = true;
          } else {
            document.getElementById("notif1").checked = false;
          }
       }
      });
      if(id == 1){
         document.getElementById("role1").disabled = true;
      } else {
        document.getElementById("role1").disabled = false;
      }
    });

    //Menyimpan user
    $(".simpan_edit_user").click(function(){
      // notif = document.getElementById("notif1").checked;
      if (document.getElementById("notif1").checked == true) {
        notif1 = 1;
      } else {
        notif1 = 0;
      }
      var myData = 'username=' + document.getElementById("username1").value 
                    + '&email=' + document.getElementById("email1").value
                    + '&role=' + document.getElementById("role1").value
                    + '&password=' + document.getElementById("password1").value 
                    + '&notif=' + notif1 ;
      
      $.ajax({
        url:"../controlpengguna/simpan_edit_user?id="+id,              
        dataType : "json",
        data : myData,
        type : "POST",
        // success :  function(data){
        //       alert(data[0]);
        // }   
        // success: success()   
      });                        
    });

    // $("#notif1").click(function(){
    //   // notif = document.getElementById("notif1").checked;
    //   if (document.getElementById("notif1").checked == true) {
    //     alert(1);
    //   } else {
    //     alert(0);
    //   }
                       
    // });

    // on success...
    // function success(){
      // alert('Perubahan Berhasil');
      // redirect(site_url("device/data_user"));
      // redirect(site_url("controlpengguna/data_user"));
      //location.reload(); 
    // }    
});
</script>>
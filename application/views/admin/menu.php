<body class="skin-blue sidebar-mini">
    <div class="wrapper">
      
      <header class="main-header">
        <!-- Logo -->
        <a href="index2.html" class="logo">
          <!-- mini logo for sidebar mini 50x50 pixels -->
          <span class="logo-mini"><b>N</b>MS</span>
          <!-- logo for regular state and mobile devices -->
          <span class="logo-lg">ApMon Jaringan</span>
        </a>
        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top" role="navigation">
          <!-- Sidebar toggle button-->
          <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
          </a>
          <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
              <li class="dropdown user user-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <!-- <img src="<?php echo base_url();?>etc/dist/img/user2-160x160.jpg" class="user-image" alt="User Image"/> -->
                  <span class="hidden-xs"><?php echo $session['username'];?></span>
                </a>
                <ul class="dropdown-menu">
                  <!-- User image -->
                  <li class="user-header">
                    <!-- <img src="<?php echo base_url();?>etc/dist/img/user2-160x160.jpg" class="img-circle" alt="User Image" /> -->
                    <p>
                      <?php echo $session['username'];?> - <?php if ($session['role']==1){echo "Administrator";}else{echo "Manager";}?>
                    </p>
                  </li>
                  
                  <!-- Menu Footer-->
                  <li class="user-footer">
                    <div class="pull-left">
                    <a class="btn btn-primary profilku" data-toggle="modal" data-target="#profilku" id="<?php echo $session['id_user']; ?>">Profile</a>
                    </div>
                    <div class="pull-right">
                      <a href="<?php echo base_url();?>index.php/controllogin/logout" class="btn btn-default btn-flat">Sign out</a>
                    </div>
                  </li>
                </ul>
              </li>
              <!-- Control Sidebar Toggle Button -->
              <li>
                <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
              </li>
            </ul>
          </div>
        </nav>
      </header>
      <!-- Left side column. contains the logo and sidebar -->
      <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
          <!-- Sidebar user panel -->
          <!-- search form -->
          <!-- /.search form -->
          <!-- sidebar menu: : style can be found in sidebar.less -->
          <ul class="sidebar-menu">
            <li class="header">Menu Navigasi</li>
            <li class="active treeview">
              <a href="<?php echo base_url();?>">
                <i class="fa fa-dashboard"></i> 
                <span>Home</span>
              </a>
            </li>
            <li class="treeview <?php if($this->uri->segment(2) == "data_perangkat" || $this->uri->segment(2) == "detail_perangkat" || $this->uri->segment(2) == "detail_if") {echo "active";}  ?>">
              <a href="#">
                <i class="fa fa-files-o"></i>
                <span>Perangkat</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li class="<?php if($this->uri->segment(2) == "data_perangkat" || $this->uri->segment(2) == "detail_perangkat" || $this->uri->segment(2) == "detail_if") {echo "active";}  ?>"><a href="<?php echo base_url();?>controlperangkat/data_perangkat"><i class="fa fa-circle-o"></i> Data Perangkat</a></li>
              </ul>
            </li>
            <li class="treeview <?php if($this->uri->segment(2) == "log_squid" || $this->uri->segment(2) == "popular_site" || $this->uri->segment(2) == "statistik") {echo "active";}  ?>">
              <a href="#">
                <i class="fa fa-pie-chart"></i>
                <span>Squid Proxy</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li <?php if($this->uri->segment(2) == "log_squid") {echo "class='active'";}?> ><a href="<?php echo base_url();?>controllogsquid/log_squid"><i class="fa fa-circle-o"></i> Log</a></li>
                <li <?php if($this->uri->segment(2) == "popular_site") {echo "class='active'";}?>><a href="<?php echo base_url();?>controllogsquid/popular_site"><i class="fa fa-circle-o"></i> Popular Site</a></li>
                <li <?php if($this->uri->segment(2) == "statistik" || $this->uri->segment(2) == "cari_statistik" ) {echo "class='active'";}?>><a href="<?php echo base_url();?>controllogsquid/statistik"><i class="fa fa-circle-o"></i> Statistik</a></li>
              </ul>
            </li>
            <?php if ($session['role']==1){ ?>
            <li class="treeview <?php if($this->uri->segment(2) == "data_user") {echo "active";}  ?>">
              <a <?php if($this->uri->segment(2) == "data_user") {echo "class='active'";}  ?> href="#">
                <i class="fa fa-laptop"></i>
                <span>Pengguna</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li><a href="<?php echo base_url();?>controlpengguna/data_user"><i class="fa fa-circle-o"></i> Daftar Pengguna</a></li>
              </ul>
            </li>
            <?php } ?>
          </ul>
        </section>
        <!-- /.sidebar -->
      </aside>

<!-- Modal Edit Device -->
<div class="modal fade" id="profilku" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Profilku</h4>
      </div>
      <div class="col-sm-12">
        <div class="alert alert-warning" id="peringatan">
          <Strong>Peringatan !</Strong>  Cek Kembali Password dan Konfirmasi Password
        </div>
      </div>
      <div class="modal-body">
        <form class="form-horizontal" role="form" method="post" action="<?php echo base_url();?>controlpengguna/simpan_profile" id="form_ganti" onsubmit="return validatepassword()">
          <div class="form-group">
            <input type="hidden" name="id2" id="id2" hidden>
            <label class="control-label col-sm-2" for="username">Username</label>
            <div class="col-sm-10">
              <input type="text" class="form-control" name="username" id="username2" placeholder="Username" required>
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-sm-2" for="email">Alamat Email</label>
            <div class="col-sm-10"> 
              <input type="text" class="form-control" name="email" id="email2" placeholder="Alamat Email" required>
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-sm-2" for="role">Role</label>
            <div class="col-sm-10"> 
              <select class="form-control" name="role" id="role2" required>
                <option value="0">-- Pilih Role --</option>
                <option value="1">Administrator</option>
                <option value="2">Manager</option>
              </select>
              <select  class="form-control role3" name="role" id="role3" required>
                <option value="0">-- Pilih Role --</option>
                <option value="1">Administrator</option>
                <option value="2">Manager</option>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-sm-2" for="password">Password</label>
            <div class="col-sm-10"> 
              <input type="password" class="form-control" name="password" id="password2" placeholder="Password" required>
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-sm-2" for="cpassword">Confirm Password</label>
            <div class="col-sm-10"> 
              <input type="password" class="form-control" name="cpassword" id="cpassword2" placeholder="Confirm Password" required>
            </div>
          </div>
          <div class="form-group"> 
            <div class="col-sm-offset-2 col-sm-10">
              <button type="submit" class="btn btn-default simpan_profile" >Rubah</button>
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



<script type="text/javascript">
//Datatables
$(document).ready(function(){
    $(".alert").hide();  
    $(".role3").hide();  
    $("#password2, #cpassword2").change(function(){
           var password   = $("#password2").val();
           var cpassword    = $("#cpassword2").val();
           if(password!=cpassword){
            //alert('Password Didnt Match');
            $(".alert").show();            
           }else{
             $(".alert").hide(); 
           }
    });

    //Menampilkan form edit user
    var id;
    $(".profilku").click(function(){
      var element = $(this);
      id = element.attr("id");
     
      $.ajax({
        url:"../controlpengguna/get_user?id="+id,              
        dataType : "json",
        type: "POST",

        success: function(data){
          document.getElementById("id2").value = data[0].id_user;
          document.getElementById("username2").value = data[0].username;
          document.getElementById("email2").value = data[0].email;
          document.getElementById("role2").value = data[0].role;
          document.getElementById("role3").value = data[0].role;
       }
      });
      if(id == 1){
         document.getElementById("role2").disabled = true;
      } else {
        document.getElementById("role2").disabled = false;
      }                          
    });

    //Menyimpan user
    $(".simpan_profile").click(function(){
      var myData = 'username=' + document.getElementById("username2").value 
                    + '&email=' + document.getElementById("email2").value
                    + '&role=' + document.getElementById("role2").value
                    + '&password=' + document.getElementById("password2").value ;
      
      $.ajax({
        url:"../controlpengguna/simpan_profile?id="+id,              
        dataType : "json",
        data : myData,
        type: "POST",
        // success: success()       
      });                        
    });

    // on success...
    function success(){
      alert('Perubahan Berhasil');
      // redirect(site_url("device/data_user"));
      redirect(site_url("controlpengguna/data_user"));
      //location.reload(); 
    }

     //Validate Ganti Password
  function validatepassword(){ 
    var password   = $("#password2").val();
    var cpassword    = $("#cpassword2").val();
       if(password!=cpassword){
        //alert('Password Didnt Match');
        $(".alert").show();
        return false;            
       }else{
          document.getElementById("form_ganti").action="<?php echo base_url();?>user/simpan_profile"; 
         $(".alert").hide(); 
       }
  }    
});
</script>>
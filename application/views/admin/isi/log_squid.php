<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Log 
        <small>Squid Proxy</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Squid Proxy</a></li>
        <li class="active">Log</li>
      </ol>
    </section>
    <section class="content">
      <!-- Small boxes (Stat box) -->
      <div class="row">
        <div class="col-lg-12 col-xs-12">
        <div class="box box-danger">
          <div class="box-body">
            <table id="log_squid" class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Waktu</th>
                  <th>IP Asal</th>
                  <th>Situs</th>
                  <th>IP Tujuan</th>
                  <th>Interface</th>
                  <th>Perangkat</th>
                </tr>
              </thead>
              <tbody>
                <?php $i=1;foreach ($log_squid as $log) { 
                    //GETTING DOMAIN USING PREG MATCH
                    // get host name from URL
                    preg_match('@^(?:http://)?([^/]+)@i', $log['domain_tujuan'], $matches); $host = $matches[1];

                    // get last two segments of host name
                    preg_match('/[^.]+\.[^.]+$/', $host, $matches); 
                    $domain = $matches[0];

                  ?>
                  <tr>
                    <td><?php echo $i;?></td>
                    <td><?php echo $log['waktu'];?></td>
                    <td><?php echo $log['user_ip'];?></td>
                    <td><?php echo $domain;?></td>
                    <td><?php echo $log['ip_tujuan'];?></td>
                    <td><?php echo $log['nama_interface'];?></td>
                    <td><?php echo $log['nama_perangkat'];?></td>
                  </tr>
                <?php $i++ ;} ?>
              </tbody>
            </table>
        </div>
        </div>
      </div>
    </section>
</div><!-- /.content-wrapper -->


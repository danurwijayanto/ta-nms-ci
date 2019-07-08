<?php 
  function url_view($data){
    //GETTING DOMAIN USING PREG MATCH
    // get host name from URL
    preg_match('@^(?:http://)?([^/]+)@i', $data, $matches); $host = $matches[1];

    // get last two segments of host name
    preg_match('/[^.]+\.[^.]+$/', $host, $matches); 
    return $domain = $matches[0];

  }
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Konten 1-->
  <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Situs Populer 
        <!-- <small>Situs Terpopuler Semua Interface</small> -->
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Squid Proxy</a></li>
        <li class="active">Popular Site</li>
      </ol>
    </section>
    <section class="content">
      <!-- Small boxes (Stat box) -->
      <div class="row">
        <div class="col-lg-12 col-xs-12">
         <!-- Custom Tabs -->
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#tab_1" data-toggle="tab">Semua Interface</a></li>
              <li><a href="#tab_2" data-toggle="tab">Per Interface</a></li>
            </ul>
            <div class="tab-content">
              <div class="tab-pane active" id="tab_1">
              <!-- KONTEN -->  
                <table id="pop_site" class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th>No</th>
                      <th>Situs</th>
                      <th>Hit</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php 
                      $domhit = array();
                      foreach ($pop_site as $pop) {  
                        //Memasukkak ke array baru    
                        array_push($domhit,url_view($pop['domain_tujuan']));
                      } 
                      //Menghitung Jumlah Value Array yang Sama
                      $domhit = array_count_values($domhit);
                      //Sort Array (Descending Order), According to Value - arsort()
                      arsort($domhit);
                      $i=1;
                      foreach ($domhit as $domhit1 => $value) { ?>
                        <tr>
                          <th><?php echo $i; ?></th>
                          <th><?php echo $domhit1; ?></th>
                          <th><?php echo $value; ?></th>
                        </tr>
                      <?php 
                      $i++; 
                      } ?>           
                  </tbody>
              </table>
              <!-- END KONTEN -->
            </div><!-- /.tab-pane -->
            <div class="tab-pane" id="tab_2">
            <!-- KONTEN -->  
              <table id="pop_site_2" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>No</th>
                  <th>Perangkat</th>
                  <th>Interface</th>
                  <th>Situs</th>
                  <th>Hit</th>
                </tr>
              </thead>
              <tbody>
              <?php $i=1;foreach ($stats as $stats) {  
                $domain = url_view($stats['domain']);    
              ?>
                
                <tr>
                    <th><?php echo $i; ?></th>
                    <th><?php echo $stats['nama_perangkat']; ?></th>
                    <th><?php echo $stats['nama_if']; ?></th>
                    <th><?php echo $domain; ?></th>
                    <th><?php echo $stats['hit']; ?></th>
                </tr>
              
                <?php $i++; } ?>
              </tbody>
              </table>
            <!-- END KONTEN -->
            </div><!-- /.tab-pane -->
          </div><!-- /.tab-content -->
        </div><!-- nav-tabs-custom -->
      </div>
    </div>
    </section>

</div><!-- /.content-wrapper -->


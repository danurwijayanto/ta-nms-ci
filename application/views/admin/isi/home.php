 <?php 
  function url_view($data){
    //GETTING DOMAIN USING PREG MATCH
    // get host name from URL
    preg_match('@^(?:http://)?([^/]+)@i', $data, $matches); $host = $matches[1];

    // get last two segments of host name
    preg_match('/[^.]+\.[^.]+$/', $host, $matches); 
    // return $domain = $matches[0];

  }
?>
 <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Dashboard
            <small>Aplikasi Monitoring Jaringan FSM UNDIP</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Dashboard</li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content">
          <!-- Small boxes (Stat box) -->
          <div class="row">
            <div class="col-lg-4 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-aqua">
                <div class="inner">
                  <h3><?php echo count($total_user);?></h3>
                  <p>Users</p>
                </div>
                <div class="icon">
                  <i class="ion ion-ios-person"></i>
                </div>
                <a href="<?php echo base_url()?>controlpengguna/data_user" class="small-box-footer">Lihat Selengkapnya <i class="fa fa-arrow-circle-right"></i></a>
              </div>
            </div><!-- ./col -->
            <div class="col-lg-4 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-green">
                <div class="inner">
                  <h3><?php echo count($total_device);?></h3>
                  <p>Perangkat<?php echo "  (".$statperup." Up, ".$statperdown." Down)";?></p>
                </div>
                <div class="icon">
                  <i class="ion ion-monitor"></i>
                </div>
                <a href="<?php echo base_url()?>controlperangkat/data_perangkat" class="small-box-footer">Lihat Selengkapnya <i class="fa fa-arrow-circle-right"></i></a>
              </div>
            </div><!-- ./col -->
            <div class="col-lg-4 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-yellow">
                <div class="inner">
                  <h3><?php echo count($total_if);?></h3>
                  <p>Interface<?php echo "  (".$ifperup." Up, ".$ifperdown." Down)";?></p>
                </div>
                <div class="icon">
                  <i class="ion ion-monitor"></i>
                </div>
                <a href="<?php echo base_url()?>controlperangkat/data_perangkat" class="small-box-footer">Lihat Selengkapnya <i class="fa fa-arrow-circle-right"></i></a>
              </div>
            </div><!-- ./col -->
           
          </div><!-- /.row -->

          <div class="row">
            <div class="col-md-6">
              <div class="box box-warning">
                <div class="box-header with-border">
                  <h3 class="box-title">Bandwith brd920</h3>
                  <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                  </div><!-- /.box-tools -->
                </div><!-- /.box-header -->
                <div class="box-body">
                <?php if (empty($bandbrd1[0]['id_rrd'])) {echo "Data Belum Terbentuk";} else { ?>
                 <img src="<?php echo base_url();?>etc/rrdtools/gambar/<?php echo $bandbrd2[0]['id_rrd']?>_1d.gif" class="col-md-12">
                <?php } ?>
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div><!-- /.col -->

            <div class="col-md-6">
              <div class="box box-warning">
                <div class="box-header with-border">
                  <h3 class="box-title">Bandwith brd85</h3>
                  <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                  </div><!-- /.box-tools -->
                </div><!-- /.box-header -->
                <div class="box-body">
                <?php if (empty($bandbrd1[0]['id_rrd'])) {echo "Data Belum Terbentuk";} else { ?>
                 <img src="<?php echo base_url();?>etc/rrdtools/gambar/<?php echo $bandbrd1[0]['id_rrd']?>_1d.gif" class="col-md-12">
                <?php } ?>
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div><!-- /.col -->
          </div>

          <!-- Main row -->
          <div class="row">
            <!-- Left col -->
            <section class="col-lg-6 connectedSortable">
              <!-- Custom tabs (Charts with tabs)-->
              <div class="nav-tabs-custom">
                <div id="highchart"></div>
              </div><!-- /.nav-tabs-custom -->

            </section><!-- /.Left col -->
            <!-- right col (We are only adding the ID to make the widgets sortable)-->
            <!-- Left col -->
            <section class="col-lg-6 connectedSortable">
              <!-- Custom tabs (Charts with tabs)-->
              <div class="nav-tabs-custom">
                <div id="highchart1"></div>
              </div><!-- /.nav-tabs-custom -->

            </section><!-- /.Left col -->
            <!-- right col (We are only adding the ID to make the widgets sortable)-->
          </div><!-- /.row (main row) -->
        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->

      <?php 
        $domhit = array();
        foreach ($pop_site as $pop) {  
          //Memasukkak ke array baru    

          // array_push($domhit,url_view($pop['domain_tujuan']));
            array_push($domhit,parse_url($pop['domain_tujuan'],PHP_URL_HOST));
        } 
        //Menghitung Jumlah Value Array yang Sama
        $domhit = @array_count_values($domhit);
        //Sort Array (Descending Order), According to Value - arsort()
        arsort($domhit);

        // foreach ($domhit as $domhit1 => $value) { 
          
        //     // echo $i;
        //     echo $domhit1; 
        //     echo $value; 
        // } 

        ?>   

        <!-- <?php print_r($domhit);?> -->



<script>
  $(function () {
    $('#highchart').highcharts({
        chart: {
            type: 'column'
        },
        title: {
            text: 'Situs Terpopuler Per Interface'
        },
        // subtitle: {
        //     text: 'Source: WorldClimate.com'
        // },
        xAxis: {
            categories: [
                
                <?php foreach ($top_site as $dom) {
                    echo "'".$dom['interface']."',";
                }?>
              
            ],
            crosshair: true
        },
        legend: {
            enabled: false
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Hit (kali)'
            }
        },
        tooltip: {



            headerFormat: '<span style="font-size:11px">Domain {point.key}</span><br>',
            pointFormat: '<span style="color:{point.color}"></span> <b>{point.y}</b> Hit<br/>'
        },
        plotOptions: {
            column: {
                pointPadding: 0.2,
                borderWidth: 0
            }
        },
        series: [
        {
            data: [
            <?php 
              foreach ($top_site as $dom1) {
                echo "
                  {
                    name: '".$dom1['nama_domain']."',
                    // color: '',
                    y: ".$dom1['hit']."
                  },
                ";
              }
            ?>
            // {
            //     name: 'Point 1',
            //     color: '',
            //     y: 1
            // }, {
            //     name: 'Point 2',
            //     color: '',
            //     y: 5
            // }
            ]

        }]
    });

    $('#highchart1').highcharts({
        chart: {
            type: 'column'
        },
        title: {
            text: 'Situs Terpopuler'
        },
        // subtitle: {
        //     text: 'Source: WorldClimate.com'
        // },
        xAxis: {
            type: 'category'
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Hit (kali)'
            }
        },
        legend: {
            enabled: false
        },
        tooltip: {
            headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
            pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y}</b> Hit<br/>'
        },
        plotOptions: {
             series: {
                borderWidth: 0,
                dataLabels: {
                    enabled: true,
                    format: '{point.y}'
                },
                events:{
                  click: function (event, i) {
                     // alert(event.point.name);
                     window.open("http://"+event.point.name);
                  }
              }
            }
        },
        series: [{
              name: 'Domain',
              colorByPoint: true,
              data: [
               <?php 
               $i = 0;
               foreach ($domhit as $domhit1 => $value1) { ?>
                {
                  name: <?php echo "'".$domhit1."'"; ?>,
                  y: <?php echo $value1; ?>,
                  drilldown: <?php echo "'".$domhit1."'"; ?>
                },
                <?php 
                  if ($i == 10) break;
                  $i++;
                  }
                ?>
                ]
            }
        ]
    });
});
</script>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Detail Interface <?php echo $det_if[0]['nama_interface']; ?>
        <small></small>
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
          <div class="box-body">

            <?php 
              if ($cek_rrd==1){
                // Generate grafik RRDTOOLS
                $nama_gambar = $id['id_if']."_".$id['id_per'];
                create_graph("etc/rrdtools/gambar/".$nama_gambar."_1d.gif", "-1d", "Bandwith Harian", $nama_gambar);
                create_graph("etc/rrdtools/gambar/".$nama_gambar."_7w.gif", "-1w", "Bandwith Mingguan", $nama_gambar);
                create_graph("etc/rrdtools/gambar/".$nama_gambar."_1m.gif", "-1m", "Bandwith Bulanan", $nama_gambar);
                create_graph("etc/rrdtools/gambar/".$nama_gambar."_1y.gif", "-1y", "Bandwith Tahunan", $nama_gambar);
                echo "<table>";
                echo "<tr>
                          <td>
                            <img src='".base_url()."etc/rrdtools/gambar/".$nama_gambar."_1d.gif' alt='Generated RRD image'>
                          </td>
                          <td>
                            <img src='".base_url()."etc/rrdtools/gambar/".$nama_gambar."_7w.gif' alt='Generated RRD image'><br>
                          </td>
                      </tr>";
                echo "<tr>
                          <td>
                           <img src='".base_url()."etc/rrdtools/gambar/".$nama_gambar."_1m.gif' alt='Generated RRD image'>
                          </td>
                          <td>
                            <img src='".base_url()."etc/rrdtools/gambar/".$nama_gambar."_1y.gif' alt='Generated RRD image'>
                          </td>
                      </tr>";
                echo "</table>";
                // echo "<img src='".base_url()."etc/rrdtools/gambar/".$nama_gambar."_1d.gif' alt='Generated RRD image'><br>";
                // echo "<img src='".base_url()."etc/rrdtools/gambar/".$nama_gambar."_7w.gif' alt='Generated RRD image'><br>";
                // echo "<img src='".base_url()."etc/rrdtools/gambar/".$nama_gambar."_1m.gif' alt='Generated RRD image'><br>";
                // echo "<img src='".base_url()."etc/rrdtools/gambar/".$nama_gambar."_1y.gif' alt='Generated RRD image'><br>";

                //Tombol untuk Reset Database RRD Tools
                echo "<br><a href='".base_url()."index.php/welcome/create_rrd?id_if=".$id['id_if']."&id_per=".$id['id_per']."' class='btn btn-danger'>Reset Database</a>";
              } else {
                //Tombol untuk Membuat Database RRD Tools
                echo "<br><a href='".base_url()."index.php/welcome/create_rrd?id_if=".$id['id_if']."&id_per=".$id['id_per']."' class='btn btn-primary'>Create Database</a>";
              }
            ?>
            <!-- -->
            <!-- <div id="chartContainer" style="height: 300px; width:100%;"></div> -->
            <!-- -->
            <!-- <div id="chart" class="c3"></div> -->
            <!-- <div id="chart" class="c3"></div> -->
          </div>
        </div>
      </div>
    </section>
</div><!-- /.content-wrapper -->

 <script>
      // var chart = c3.generate({
      //   data: {
      //     x: 'x',
      //     columns: [
      //       ['x', '2012-12-29', '2012-12-30', '2012-12-31'],
      //       ['data1', 230, 300, 330],
      //       ['data2', 190, 230, 200],
      //       ['data3', 90, 130, 180],
      //     ],
      //     type: 'spline'
      //   },
      //   axis: {
      //     x: {
      //       type: 'timeseries',
      //       tick: {
      //         format: '%m/%d',
      //       }
      //     }
      //   }
      // });

      // setTimeout(function () {
      //   chart.flow({
      //     columns: [
      //       ['x', '2013-01-11', '2013-01-21'],
      //       ['data1', 500, 200],
      //       ['data2', 100, 300],
      //       ['data3', 200, 120],
      //     ],
      //     duration: 1500,
      //   });
      // }, 1000);
    </script>

    <?php 
// create_graph("login-hour.gif", "-1h", "Hourly login attempts");
  
  // create_graph("login-week.gif", "-1w", "Weekly login attempts");
  // create_graph("login-month.gif", "-1m", "Monthly login attempts");
  // create_graph("login-year.gif", "-1y", "Yearly login attempts");

  // echo "<table>";
  // echo "<tr><td>";
  
  // echo "</td><td>";
  // echo "<img src='login-week.gif' alt='Generated RRD image'>";
  // echo "</td></tr>";
  // echo "<tr><td>";
  // echo "<img src='login-month.gif' alt='Generated RRD image'>";
  // echo "</td><td>";
  // echo "<img src='login-year.gif' alt='Generated RRD image'>";
  // echo "</td></tr>";
  // echo "</table>";
  // exit;

  function create_graph($output, $start, $title, $name) {
    $options = array(
      "--slope-mode",
      "--start", $start,
      "--title=$title",
      "--vertical-label=Speed",
      "--lower=0",
      "DEF:in=etc/rrdtools/rra/".$name.".rrd:in:AVERAGE",
      "DEF:out=etc/rrdtools/rra/".$name.".rrd:out:AVERAGE",
      "CDEF:kbin=in,1024,/",
      "CDEF:kbout=out,1024,/",
      "AREA:in#00FF00:Bandwith In",
      "LINE1:out#0000FF:Bandwidth Out\j",
      "GPRINT:kbin:LAST:Last Bandwidth In\:    %3.2lf KBps\j",
      "GPRINT:kbout:LAST:Last Bandwidth Out\:   %3.2lf KBps\j",
      "GPRINT:kbin:AVERAGE:Average Bandwidth In\: %3.2lf KBps\j",
      "GPRINT:kbin:AVERAGE:Average Bandwidth Out\: %3.2lf KBps",
    );

    $ret = rrd_graph($output, $options);
    if (! $ret) {
      echo "<b>Graph error: </b>".rrd_error()."\n";
    }
  }

    ?>
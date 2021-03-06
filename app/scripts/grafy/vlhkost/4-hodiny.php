﻿<?php

 /*************************************************************************
 ***  Systém pro TME/TH2E - TMEP                                        ***
 ***  (c) Michal Ševčík 2007-2012 - multi@tricker.cz                    ***
 *************************************************************************/

  // INIT
  require "./scripts/init.php";

  // Posledni zaznamy
  $q = MySQL_query("SELECT kdy, vlhkost FROM tme ORDER BY kdy DESC LIMIT 240");

      // budeme pocitat kazdy osmy zaznam, tedy kazdou osmou minutu
      $a = 8;
      $count = 0;

        while($t = MySQL_fetch_assoc($q))
        {

        // budeme pocitat prumernou teplotu za poslednich osm minut... vypada to lepe
        $teplota = $teplota+$t['vlhkost'];
        $count++;

          if($a == 8)
          {

            // pridame do poli
            if(round($teplota/$count, 1) == 0){ $ydata[] = "0"; }
            else{ $ydata[] = round(($teplota/$count), 1); }
            
            $labels[] = substr($t['kdy'], 11, 5);

            $teplota = "";
            $count = 0;

            $a = 0;      

          }

        $a++;

        }

        // obratime pole
        $ydata = array_reverse($ydata);
        $labels = array_reverse($labels);

?>
<script type="text/javascript">
$(function () {
    var chart;
    $(document).ready(function() {
        chart = new Highcharts.Chart({
            chart: { renderTo: 'graf-4-hodiny-vlhkost', zoomType: 'x', borderWidth: 1, backgroundColor: '#f7f6eb' },
            credits: { enabled: 0 },
            title: { text: '<?php echo $lang['vlhkost4hodiny']; ?>' },
            xAxis: { categories: ['<?php echo implode("','", $labels); ?>'], 
            labels: { rotation: -45, align: 'right' }
            },
            yAxis: [{ 
                labels: {
                    formatter: function() { return this.value +' <?php echo "$jednotka"; ?>'; },
                    style: { color: '#c4423f' }
                },
                title: {
                    text: null,
                    style: { color: '#c4423f' }
                },
                opposite: false
            }, {
                gridLineWidth: 0,
                title: {
                    text: null,
                    style: { color: '#4572a7' }
                },
                labels: {
                    formatter: function() { return this.value +' %'; },
                    style: { color: '#4572a7' }
                },
                opposite: true
            }, {
                gridLineWidth: 0,
                title: {
                    text: null,
                    style: { color: '#6ba54e' }
                },
                labels: {
                    formatter: function() { return this.value +' <?php echo "$jednotka"; ?>'; },
                    style: { color: '#6ba54e' }
                },
                opposite: true
            }],
            tooltip: {
                formatter: function() {
                    var unit = {
                        '<?php echo $lang['teplota'] ?>': '<?php echo "$jednotka"; ?>',
                        '<?php echo $lang['vlhkost'] ?>': '%',
                        '<?php echo $lang['rosnybod'] ?>': '<?php echo "$jednotka"; ?>'
                    }[this.series.name];
                    return '<b>'+ this.x +'</b><br /><b>'+ this.y +' '+ unit + '</b>';
                },
                crosshairs: true,
            },
            legend: {
                layout: 'horizontal',
                align: 'left',
                x: 6,
                verticalAlign: 'top',
                y: -5,
                floating: true,
                backgroundColor: '#FFFFFF'
            },
            series: [{
                name: '<?php echo $lang['teplota'] ?>',
                type: 'spline',
                color: '#c4423f',
                yAxis: 0,
                data: [<?php echo implode(", ", $ydata); ?>],
                marker: { enabled: false }
            }, {
                name: '<?php echo $lang['vlhkost'] ?>',
                type: 'spline',
                color: '#4572a7',
                yAxis: 1,
                data: [<?php echo implode(", ", $ydata2); ?>],
                marker: { enabled: false }
    
            }, {
                name: '<?php echo $lang['rosnybod'] ?>',
                type: 'spline',
                color: '#6ba54e',
                yAxis: 2,
                data: [<?php echo implode(", ", $ydata3); ?>],
                marker: { enabled: false }
            }]
        });
    });
    
});
</script>
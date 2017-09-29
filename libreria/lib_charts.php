<?php

function stampa_gchart_col_1($sql, $title, $vAxis, $hAxis, $stile, $colore){
    global $dblink;
?>
<script type="text/javascript">
google.load('visualization', '1', {packages: ['corechart']});
   function drawVisualization() {
     // Some raw data (not necessarily accurate)
     var data = google.visualization.arrayToDataTable([
       ['Mese/Anno', 'Chiusi', 'In Attesa', 'Negativi'],
       //['01-2012',  165,      938,         522],
       //['02-2012',  135,      1120,        599],
       //['03-2012',  157,      1167,        587],
       //['04-2012',  139,      1110,        615],
       //['05-2012',  136,      691,         629]
 <?php
 $rs = $dblink->get_results($sql);
 if(!empty($rs)){
   $numero_record = count($rs);
   $a_record = 1;
   foreach($rs as $row){

     echo " ['".$row['anno_mese']."', ".$row['chiuso'].", ".$row['in_attesa'].", ".$row['negativo']."]";

     if($a_record>=$numero_record){

     }else{
       echo ', ';
     }
   $a_record++;
   }

 }else{
   echo '<h4 class="alert_alert"></h4>';
 }
 ?>
     ]);

     var options = {
       title : '<?=$title?>' ,
       vAxis: {title: "<?=$vAxis?>"},
       hAxis: {title: "<?=$hAxis?>"},
       seriesType: "bars",
       colors: ['green','orange', 'red'],
       series: {3: {type: "line"}}
     };

     var chart = new google.visualization.ComboChart(document.getElementById('gchart_col_1'));
     chart.draw(data, options);
   }
   google.setOnLoadCallback(drawVisualization);
 </script>
<?php
}

function stampa_gchart_col_1_fatture($sql, $title, $vAxis, $hAxis, $stile, $colore){
    global $dblink;
?>
<script type="text/javascript">
google.load('visualization', '1', {packages: ['corechart']});
   function drawVisualization() {
     // Some raw data (not necessarily accurate)
     var data = google.visualization.arrayToDataTable([
       ['Mese/Anno', 'Pagata', 'In Attesa', 'Stornata'],
 <?php
 $rs = $dblink->get_results($sql);
 if(!empty($rs)){
   $numero_record = count($rs);
   $a_record = 1;
   foreach($rs as $row){

     echo " ['".$row['anno_mese']."', ".$row['Pagata'].", ".$row['in_attesa'].", ".$row['Stornata']."]";

     if($a_record>=$numero_record){

     }else{
       echo ', ';
     }
    $a_record++;
   }

 }
 ?>
     ]);

     var options = {
       title : '<?=$title?>' ,
       vAxis: {title: "<?=$vAxis?>"},
       hAxis: {title: "<?=$hAxis?>"},
       seriesType: "bars",
       colors: ['green','orange', 'red'],
       series: {3: {type: "line"}}
     };

     var chart = new google.visualization.ComboChart(document.getElementById('gchart_col_1'));
     chart.draw(data, options);
   }
   google.setOnLoadCallback(drawVisualization);
 </script>
<?php
}

function stampa_gchart_col_1_preventivi($sql, $title, $vAxis, $hAxis, $stile, $colore){
    global $dblink;
?>
<script type="text/javascript">
google.load('visualization', '1', {packages: ['corechart']});
   function drawVisualization() {
     // Some raw data (not necessarily accurate)
     var data = google.visualization.arrayToDataTable([
       ['Mese/Anno', 'Fatturati', 'Venduti', 'In Trattativa', 'Negativi'],
       //['01-2012',  165,      938,         522],
       //['02-2012',  135,      1120,        599],
       //['03-2012',  157,      1167,        587],
       //['04-2012',  139,      1110,        615],
       //['05-2012',  136,      691,         629]
 <?php
 $rs = $dblink->get_results($sql);
 if(!empty($rs)){
   $numero_record = count($rs);
   $a_record = 1;
   foreach($rs as $row){

     echo " ['".$row['anno_mese']."', ".$row['fatturati'].", ".$row['venduti'].", ".$row['in_attesa'].", ".$row['negativi']."]";

     if($a_record>=$numero_record){

     }else{
       echo ', ';
     }
   $a_record++;
   }

 }
 ?>
     ]);

     var options = {
       title : '<?=$title?>' ,
       vAxis: {title: "<?=$vAxis?>"},
       hAxis: {title: "<?=$hAxis?>"},
       seriesType: "bars",
       colors: ['green','blue', 'orange', 'red'],
       series: {4: {type: "line"}}
     };

     var chart = new google.visualization.ComboChart(document.getElementById('gchart_col_1'));
     chart.draw(data, options);
   }
   google.setOnLoadCallback(drawVisualization);
 </script>
<?php
}

function stampa_gchart_colonne_data($idElement, $data, $title, $vAxis, $hAxis, $stile = 'bars', $colors){
    ?>
    <script type="text/javascript">
    google.load('visualization', '1', {packages: ['corechart']});
    function drawVisualization() {
        // Some raw data (not necessarily accurate)
        var data = google.visualization.arrayToDataTable(<?=$data?>);
        var options = {
            title : '<?=$title?>' ,
            vAxis: {title: "<?=$vAxis?>"},
            hAxis: {title: "<?=$hAxis?>"},
            seriesType: "<?=$stile?>",
            colors: <?=$colors?>,
            series: {4: {type: "line"}}
        };

            var chart = new google.visualization.ComboChart(document.getElementById('<?=$idElement?>'));
            chart.draw(data, options);
        }
       google.setOnLoadCallback(drawVisualization);
    </script>
    <?php
}

?>
<?php
function stampa_gchart_pie_1($sql, $title, $stile, $colore){
    global $dblink;
?>
<script type="text/javascript">
google.load("visualization", "1", {packages:["corechart"]});
google.setOnLoadCallback(drawChart);
function drawChart() {
  var data = new google.visualization.DataTable();
  data.addColumn('string', 'Task');
  data.addColumn('number', 'Hours per Day');
  data.addRows([
 <?php
//$sql = "SELECT COUNT(stato) as conto, stato FROM calendario WHERE destinatario='".$_SESSION['cognome_nome_utente']."' $where_calendario GROUP BY destinatario,stato ORDER BY stato";
$rs = $dblink->get_results($sql);
if(!empty($rs)){
    $numero_record = count($rs);
    $a_record = 1;
    foreach($rs as $row){
        echo "['".$row['stato']."',    ".$row['conto']."]";

        if($a_record>=$numero_record){

        }else{
            echo ', ';
        }
        $a_record++;
    }

}

?>
  ]);

  var options = {
    //width: 400, height: 450,
    //pieHole: 0.4,
    title: ''
  };

  var chart = new google.visualization.PieChart(document.getElementById('gchart_pie_1'));
  chart.draw(data, options);
}
</script>
<?php
}


function stampa_gchart_pie_1_fatture($sql, $title, $stile, $colore){
?>
<script type="text/javascript">
google.load("visualization", "1", {packages:["corechart"]});
google.setOnLoadCallback(drawChart);
function drawChart() {
  var data = new google.visualization.DataTable();
  data.addColumn('string', 'Task');
  data.addColumn('number', 'Hours per Day');
  data.addRows([
 <?php
$rs = $dblink->get_results($sql);
if(!empty($rs)){
    $numero_record = count($rs);
    $a_record = 1;
foreach($rs as $row){
  echo "['".$row['stato']."',    ".$row['conto']."]";

  if($a_record>=$numero_record){

  }else{
    echo ', ';
  }
$a_record++;
}

}else{
echo '<h4 class="alert_alert"></h4>';
}

?>
  ]);

  var options = {
    //width: 400, height: 450,
    //pieHole: 0.4,
    title: ''
  };

  var chart = new google.visualization.PieChart(document.getElementById('gchart_pie_1'));
  chart.draw(data, options);
}
</script>
<?php
}
?>

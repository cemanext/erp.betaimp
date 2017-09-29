<?php
function stampa_widget_tasks_1($sql_007, $titolo, $icona, $colore){
    global $dblink;
?>
<div class="portlet box <?=$colore?> tasks-widget">
    <div class="portlet-title">
        <div class="caption">
            <i class="<?=$titolo?>"></i>
            <span class="caption-subject bold uppercase"><?=$titolo?></span>
            <span class="caption-helper"></span>
        </div>
        <div class="actions">

        </div>
    </div>
    <div class="portlet-body">
        <div class="task-content">
            <div class="scroller" style="height: 312px;" data-always-visible="1" data-rail-visible1="1">
                <!-- START TASK LIST -->
                <ul class="task-list">
                  <?php
                      $rs_007 = $dblink->get_results($sql_007);
                      if(!empty($rs_007)){
                          foreach($rs_007 as $row_007){
                            echo '<li>
                                <div class="task-title"><span class="badge badge-warning pull-left">'.$row_007['data'].'</span>&nbsp&nbsp
                                    <span class="task-title-sp"> <a href="'.$row_007['link'].'">'.$row_007['campo_1'].'</a> </span>
                                </div>
                                <div class="task-config">
                                    <div class="task-config-btn btn-group">
                                    <a href="'.$row_007['link'].'" class="btn btn-sm yellow-lemon">
                                        '.$row_007['nome_link'].' <i class="fa fa-share-square-o"></i></a>
                                    </div>
                                </div>
                            </li>';
                          }
                      }

                  ?>

                </ul>
                <!-- END START TASK LIST -->
            </div>
        </div>
    </div>
</div>
<?php
}
function stampa_dashboard_stat_v2($sql_007, $titolo, $icona, $colore, $link){
    global $dblink;
    //$conteggio_stat_v2 = $dblink->num_rows($sql_007);
    $conteggio_stat_v2 = $dblink->get_field($sql_007);
    /*$rs_007 = mysql_query($sql_007);
    if($rs_007){
        $conteggio_stat_v2 = mysql_num_rows($rs_007);
            while($row_007 = mysql_fetch_array($rs_007, MYSQL_BOTH)){
                $conteggio_stat_v2 = $row_007['conteggio'];
            }
    }*/
    ?>
    <a class="dashboard-stat dashboard-stat-v2 <?=$colore?>" href="<?=$link?>">
        <div class="visual">
            <i class="<?=$icona?>"></i>
        </div>
        <div class="details">
            <div class="number">
                <span data-counter="counterup" data-value="<?php echo $conteggio_stat_v2;?>"></span></div>
            <div class="desc"> <?=$titolo?> </div>
        </div>
    </a>
    <?php
}
?>

<?php
/*
  @TODO DEFINIRE VARIABILI
  STAMPA CALENDARIO

 */

function stampa_calendario_1($sql_2, $defaultView, $stile, $colore) {
    global $dblink;
    $rs_2 = $dblink->get_results($sql_2);
    if (!empty($rs_2)) {
        $numero_record = count($rs_2);
        $a_record = 1;
        $variabili_data_1 = '';
        foreach ($rs_2 as $row_2) {
            $random = rand(0, 15);
            $variabili_data_1 .= "{
                        title: '" . addslashes(utf8_decode($row_2['oggetto'])) . "',
                        start: '" . $row_2['data_inizio'] . " " . $row_2['ora_inizio'] . "',
                        end: '" . $row_2['data_fine'] . " " . $row_2['ora_fine'] . "',
                         url: '" . $row_2['link'] . "',
                        backgroundColor: '#" . $row_2['colore_sfondo'] . "'
                    }";
            if ($a_record >= $numero_record) {
                
            } else {
                $variabili_data_1 .= ', ';
            }
            $a_record++;
        }
    }
    ?>
    <script>
        $(document).ready(function ()
        {
            $('#calendar').fullCalendar({
                header:
                        {
                            left: 'prev,next today',
                            center: 'title',
                            right: 'month,agendaWeek,listMonth'
                        },
                //defaultDate: '2016-10-12',
                defaultView: '<?=$defaultView?>', // listDay, basicWeek, agendaWeek change default view with available options from http://arshaw.com/fullcalendar/docs/views/Available_Views/
                editable: false,
                droppable: false,
                eventLimit: false, // allow "more" link when too many events
                events: [
                    <?=$variabili_data_1?>
                ]
            });
        });
    </script>
    <?php
}
?>

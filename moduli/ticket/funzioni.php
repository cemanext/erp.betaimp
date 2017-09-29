<?php

/** FUNZIONI DI CROCCO */
function Stampa_HTML_index_Tickets($tabella) {
    global $dblink, $table_listaTicketStati, $table_listaTickets;

    switch ($tabella) {
        case "lista_ticket":
            $tabella = "lista_ticket";
            $campi_visualizzati = $table_listaTickets['index']['campi'];
            $where = $table_listaTickets['index']['where'];
            $ordine = $table_listaTickets['index']['order'];
            $titolo = 'Elenco Ticket';
            $sql_0001 = "SELECT " . $campi_visualizzati . " FROM " . $tabella . " WHERE $where $ordine";
            stampa_table_datatables_responsive($sql_0001, $titolo, 'tabella_base1');
            break;

        case "lista_ticket_stati":
            $tabella = "lista_ticket_stati";
            $campi_visualizzati = $table_listaTicketStati['index']['campi'];
            $where = $table_listaTicketStati['index']['where'];
            $ordine = $table_listaTicketStati['index']['order'];
            $titolo = 'Elenco Ticket Stati';
            $sql_0001 = "SELECT " . $campi_visualizzati . " FROM " . $tabella . " WHERE $where $ordine";
            stampa_table_datatables_responsive($sql_0001, $titolo, 'tabella_base1');
            break;

        default:
            $campi_visualizzati = "";
            $campi = $dblink->list_fields("SELECT * FROM " . $tabella . "");
            foreach ($campi as $nome_colonna) {
                $campi_visualizzati .= "`" . $nome_colonna->name . "`, ";
            }


            $campi_visualizzati = substr($campi_visualizzati, 0, -2);
            $where = " 1 ";
            $ordine = " ORDER BY id DESC";
            $titolo = "Elenco " . $tabella;
            $stile = "tabella_base";
            $colore_tabella = COLORE_PRIMARIO;
            $sql_0001 = "SELECT
            CONCAT('<a class=\"btn btn-circle btn-icon-only yellow btn-outline\" href=\"dettaglio.php?tbl=" . $tabella . "&id=',id,'\" title=\"DETTAGLIO\" alt=\"DETTAGLIO\"><i class=\"fa fa-search\"></i></a>') AS 'fa-search',
            CONCAT('<a class=\"btn btn-circle btn-icon-only blue btn-outline\" href=\"modifica.php?tbl=" . $tabella . "&id=',id,'\" title=\"MODIFICA\" alt=\"MODIFICA\"><i class=\"fa fa-edit\"></i></a>') AS 'fa-edit',
            CONCAT('<a class=\"btn btn-circle btn-icon-only green btn-outline\" href=\"duplica.php?tbl=" . $tabella . "&id=',id,'\" title=\"DUPLICA\" alt=\"DUPLICA\"><i class=\"fa fa-copy\"></i></a>') AS 'fa-copy',
            " . $campi_visualizzati . ",
            CONCAT('<a class=\"btn btn-circle btn-icon-only red btn-outline\" href=\"cancella.php?tbl=" . $tabella . "&id=',id,'\" title=\"ELIMINA\" alt=\"ELIMINA\"><i class=\"fa fa-trash\"></i></a>') AS 'fa-trash'
            FROM " . $tabella . " WHERE $where $ordine LIMIT 1";
            //echo '<li>$sql_0001 = '.$sql_0001.'</li>';
            stampa_table_datatables_ajax($sql_0001, "datatable_ajax", $titolo, '', '', false);
            //stampa_table_datatables_responsive($sql_0001, $titolo, $stile, $colore_tabella);
            break;
    }
}

function Stampa_HTML_Dettaglio_Ticket_Timeline1($tabella, $id) {
    global $dblink;

    if ($_SESSION['livello_utente'] == 'amministratore') {
        $livelloAmministratore = true;
    } else {
        $livelloAmministratore = false;
    }

    $ticket = $dblink->get_row("SELECT * FROM lista_ticket WHERE id = $id ORDER BY datainsert DESC", true);
    //echo '<div class="row"><div class="col-md-12 col-sm-12">';
    //$sql_0001 = "SELECT CONCAT('<H3>',nome_prodotto,'</H3>') AS 'Corso'
    //FROM lista_corsi
    //WHERE id=" . $id;
    //stampa_table_static_basic($sql_0001, '', 'CORSO', 'green-haze');
    ///echo '</div></div>';
    echo'<form id="formRispostaTicket" name="formRispostaTicket" class="form-horizontal form-bordered" enctype="multipart/form-data" role="form" action="' . BASE_URL . '/moduli/ticket/salva.php?fn=rispostaTicket" method="POST">';
    echo'<div class="row"><div class="col-md-8">';
    echo '<div class="portlet light bordered"><div class="portlet-title">';
    $sql_ticket = "SELECT * FROM lista_ticket WHERE id = $id ORDER BY datainsert DESC";
    $rs_ticket = $dblink->get_results($sql_ticket);
    if (!empty($rs_ticket)) {
        foreach ($rs_ticket as $row_ticket) {
            echo'<div class="caption caption-lg">
                <i class="fa fa-ticket font-dark"></i>
                <span class="caption-subject bold uppercase" style="font-size:24px;">' . $row_ticket['oggetto'] . ' </span>
                <br><span class="caption-helper" style="display:inline; margin-top:6px;"> Data Agg.: <strong class="font-blue-chambray">' . $row_ticket['dataagg'] . '</strong> - Assegnato a: <strong class="font-blue-chambray">' . $row_ticket['destinatario'] . '</strong></span>
            </div>';
            if ($livelloAmministratore) {
                echo '<div class="actions">
                    <div class="btn-group clearfix pull-right">
                    <button type="button" class="btn btn-sm red-thunderbird dropdown-toggle" data-toggle="dropdown"> Azioni
                        <i class="fa fa-angle-down"></i>
                    </button>
                    <ul class="dropdown-menu pull-right" role="menu">
                      <li><a href="salva.php?idTicket=' . $id . '&labelStato=Lavorazione Terminata&fn=aggiornaTicket"> <span class="label bg-green-jungle bg-font-green-jungle bold"> Lavorazione Terminata </span> </a>
                      <li><a href="salva.php?idTicket=' . $id . '&labelStato=In Lavorazione&fn=aggiornaTicket"> <span class="label bg-blue-steel bg-font-blue-steel bold"> In Lavorazione </span> </a>
                      <li><a href="salva.php?idTicket=' . $id . '&labelStato=In Attesa&fn=aggiornaTicket"> <span class="label bg-yellow-saffron bg-font-yellow-saffron bold"> In Attesa </span></a>

                    </ul>
                    </div>
              </div>';
            }
        }
    }

    echo '</div>';
    echo '<div class="portlet-body"><div class="timeline">';
    $sql_tickets = "SELECT * FROM lista_ticket WHERE id = $id ORDER BY datainsert DESC";
    $rs_tickets = $dblink->get_results($sql_tickets);
    if (!empty($rs_tickets)) {
        foreach ($rs_tickets as $row_tickets) {
            echo '<!-- TIMELINE ITEM MITTENTE -->
          <div class="timeline-item">
              <div class="timeline-badge">
                  <div class="timeline-icon">
                      <i class="fa fa-flag fa-3x font-red-intense"></i>
                  </div>
                  <!--<img class="timeline-badge-userpic img-circle" src="http://erp.betaformazione.com/media/users/' . $_SESSION['nickname'] . '.jpg"> -->
                </div>
              <div class="timeline-body">
                  <div class="timeline-body-arrow"> </div>
                  <div class="timeline-body-head">
                      <div class="timeline-body-head-caption">
                          <a href="javascript:;" class="timeline-body-title font-blue-madison">Da: ' . $row_tickets['mittente'] . ' <i class="icon-paper-plane font-green-haze"></i> A: ' . $row_tickets['destinatario'] . '</a>
                          <span class="timeline-body-time font-grey-cascade">' . $row_tickets['datainsert'] . ' ' . $row_tickets['orainsert'] . '</span>
                      </div>
                      <div class="timeline-body-head-actions"> </div>
                  </div>
                  <div class="timeline-body-content">
                    <!--<span class="font-grey-cascade">-->
                      <span>
                      <p><i class="fa fa-commenting font-green-haze"></i> ' . nl2br($row_tickets['messaggio']) . '</p>
                      <p><i class="fa fa-paperclip font-green-haze"></i> <a href="' . $row_tickets['allegato'] . '" target="_blank">' . basename($row_tickets['allegato']) . '</a></p>
                      <p><i class="fa fa-globe font-green-haze"></i> <a href="' . $row_tickets['url'] . '" target="_blank">' . basename(dirname(dirname($row_tickets['url']))) . '/' . basename(dirname($row_tickets['url'])) . '/' . basename($row_tickets['url']) . '</a></p>
                      </span>
                  </div>
              </div>
          </div>
          <!-- END TIMELINE ITEM MITTENTE-->';
        }
    }

    echo '</div></div>';
    echo '<div class="portlet-body"><div class="timeline">';
    $sql_tickets = "SELECT * FROM lista_ticket_dettaglio WHERE id_ticket = $id ORDER BY datainsert DESC, orainsert DESC";
    $rs_tickets = $dblink->get_results($sql_tickets);
    if (!empty($rs_tickets)) {
        foreach ($rs_tickets as $row_tickets) {
            echo '<!-- TIMELINE ITEM MITTENTE -->
          <div class="timeline-item">
              <div class="timeline-badge">';
            $user = $dblink->get_row("SELECT avatar FROM lista_password WHERE id = '" . $row_tickets['id_mittente'] . "'", true);

            if (!file_exists(BASE_ROOT . "media/users/" . $user['avatar'] . ".jpg")) {
                echo '<div class="timeline-icon">
                      <i class="icon-bubbles font-red-intense"></i>
                  </div>';
            } else {
                echo '<img class="timeline-badge-userpic img-circle" src="' . BASE_URL . '/media/users/' . $user['avatar'] . '.jpg">';
            }
            echo '</div>
              <div class="timeline-body">
                  <div class="timeline-body-arrow"> </div>
                  <div class="timeline-body-head">
                      <div class="timeline-body-head-caption">
                          <a href="javascript:;" class="timeline-body-title font-blue-madison">Da: ' . $row_tickets['mittente'] . ' <!--<i class="icon-paper-plane font-green-haze"></i> A: ' . $row_tickets['destinatario'] . '--></a>
                          <span class="timeline-body-time font-grey-cascade">' . GiraDataOra($row_tickets['datainsert']) . ' ' . $row_tickets['orainsert'] . '</span>
                      </div>
                      <div class="timeline-body-head-actions">';
            if ($livelloAmministratore) {
                echo' <a class="btn btn-sm btn-outline blue" href="modifica.php?tbl=lista_ticket_dettaglio&id=' . $row_tickets['id'] . '" title="MODIFICA" alt="MODIFICA"><i class="fa fa-edit"></i> </a>&nbsp;&nbsp;';
            } else {
                
            }
            echo' </div>
                  </div>
                  <div class="timeline-body-content">
                    <!--<span class="font-grey-cascade">-->
                      <span>
                      <p><i class="fa fa-commenting font-green-haze"></i> ' . nl2br($row_tickets['messaggio']) . '</p>
                      <p><i class="fa fa-paperclip font-green-haze"></i> <a href="' . $row_tickets['allegato'] . '" target="_blank">' . basename($row_tickets['allegato']) . '</a></p>
                      </span>
                  </div>
              </div>
          </div>
          <!-- END TIMELINE ITEM MITTENTE-->';
        }
    }

    echo '</div></div>';
    echo '</div>';
    echo'</div>';
    echo'<div class="col-md-4"><div class="portlet box grey-mint">
          <div class="portlet-title">
              <div class="caption font-light">
                  <i class="fa fa-comment-o"></i>
                  <span class="caption-subject bold uppercase">STATO TICKET</span>
                  <span class="caption-helper"> </span>
              </div>
          </div>
          <div class="portlet-body" style="padding:25px;">
              <!-- START TIPO CHIUSURA-->
              <div class="row" style="">
                  <div class="col-md-6">
                      <div class="form-group " style="padding-right:5px;">
                          <label class="control-label font-dark bold uppercase">STATO</label>';
    if ($livelloAmministratore) {
        print_bs_select("SELECT nome as valore, nome, colore_sfondo as colore FROM lista_ticket_stati WHERE 1 $where_lista_ticket_stati", "lista_ticket_txt_stato", $ticket['stato'], "", true);
    } else {
        print_input("lista_ticket_txt_stato", $ticket['stato'], "Stato", true);
    }
    echo '</div>
                  </div>
                  <div class="col-md-6">
                      <div class="form-group " style="padding-left:5px;">
                          <label class="control-label font-dark bold">Priorità</label>';
    if ($livelloAmministratore) {
        //print_bs_select("SELECT nome as valore, nome, colore_sfondo as colore FROM lista_tipo_marketing WHERE stato='Attivo' ORDER BY nome", "calendario_txt_tipo_marketing", $row_00003['tipo_marketing'], "", true);
        print_select_static(array("Alta" => "Alta", "Media" => "Media", "Bassa" => "Bassa"), 'lista_ticket_txt_priorita', $ticket['priorita']);
    } else {
        print_input("lista_ticket_txt_priorita", $ticket['priorita'], "Priorita", true);
    }
    echo'  </div>
                  </div>
            </div>
            <div class="row" style="">
                <div class="col-md-6">
                      <div class="form-group " style="padding-left:5px;">
                      <label class="control-label font-dark bold">Allegato</label>

                  <div class="fileinput fileinput-new" data-provides="fileinput">
                      <span class="btn default btn-file btn-sm">
                          <span class="fileinput-new"> Seleziona File </span>
                          <span class="fileinput-exists"> Cambia </span>
                          <input type="file" class="input-lg" name="lista_ticket_dettaglio_txt_allegato" id="lista_ticket_dettaglio_txt_allegato">
                          </span>
                      <span class="fileinput-filename"> </span> &nbsp;
                      <a href="javascript:;" class="close fileinput-exists" data-dismiss="fileinput"> </a>
                  </div>
                  </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group " style="padding-left:5px;">
                          <label class="control-label font-dark bold">Notifica Email</label>';
    print_select_static(array("Si" => "Si", "No" => "No"), 'lista_ticket_dettaglio_txt_notifica_email', "No");
    echo'  </div>
                  </div>
              </div>
              <!-- END TIPO CHIUSURA-->
              <hr>

              <!-- START DESCRIZIONE CHIUSURA-->
              <div class="row" style="margin-bottom:15px;">
                  <div class="form-group">
                      <div class="col-md-12">
                          <textarea class="form-control" rows="10" placeholder="Messaggio..."  id="lista_ticket_dettaglio_txt_messaggio" name="lista_ticket_dettaglio_txt_messaggio"></textarea>
                      </div>
                  </div>
              </div>
              <!-- END DESCRIZIONE CHIUSURA -->';

    print_hidden("lista_ticket_txt_id", $id, true);
    print_hidden("lista_ticket_dettaglio_txt_id_ticket", $id, true);
    print_hidden("lista_ticket_dettaglio_txt_datainsert", date("d-m-Y"), true);
    print_hidden("lista_ticket_dettaglio_txt_orainsert", date("H:i:s"), true);
    print_hidden("lista_ticket_dettaglio_txt_mittente", $_SESSION['cognome_nome_utente'], true);
    print_hidden("lista_ticket_dettaglio_txt_id_mittente", $_SESSION['id_utente'], true);
    //print_hidden("lista_ticket_dettaglio_txt_destinatario", $ticket['destinatario'], true);
    //print_hidden("lista_ticket_dettaglio_txt_id_destinatario", $ticket['id_destinatario'], true);

    echo'<button type="submit" class="btn green-jungle" alt="RISPONDI" title="RISPONDI">RISPONDI <i class="fa fa-paper-plane"></i></button>
              <!--<a href="salva.php?id=<?= $id ?>&fn=chiusuraTicket" class="btn btn-icon-only blue" alt="CHIUDI" title="CHIUDI"><i class="fa fa-times"></i></a>-->
              <!-- END CHIUSURA RAPIDA-->
          </div>  <!--end portlet body -->
      </div>
      <!--FINE PORTLET CHIUSURE -->';
    echo'</div></div>';
    echo'</form>';
    //echo'';
}

function Stampa_HTML_Dettaglio_Ticket_Timeline2($tabella, $id) {
    
    echo'<div class="portlet light portlet-fit bordered">
          <div class="portlet-title">
              <div class="caption">
                  <i class="icon-speech font-dark"></i>
                  <span class="caption-subject bold font-green uppercase"> NOME TICKET</span>
                  <span class="caption-helper">MITTENTE - OPERATORE - STATO</span>
              </div>
              <div class="actions">
                  <div class="btn-group btn-group-devided" data-toggle="buttons">
                      <label class="btn red btn-outline btn-circle btn-sm active">
                          <input type="radio" name="options" class="toggle" id="option1">Chiudi</label>
                      <label class="btn  red btn-outline btn-circle btn-sm">
                          <input type="radio" name="options" class="toggle" id="option2">Tools</label>
                  </div>
              </div>
          </div>
          <div class="portlet-body">
              <div class="mt-timeline-2">
                  <div class="mt-timeline-line border-grey-steel"></div>
                  <ul class="mt-container">

                      <li class="mt-item">
                          <div class="mt-timeline-icon bg-blue-steel bg-font-blue-steel border-grey-steel">
                              <i class="icon-call-in"></i>
                          </div>
                          <div class="mt-timeline-content">
                              <div class="mt-content-container">
                                  <div class="mt-title">
                                      <h3 class="mt-content-title">lista_ticket_dettaglio.oggetto - MITTENTE</h3>
                                  </div>
                                  <div class="mt-author">
                                      <div class="mt-avatar">
                                          <img src="../assets/pages/media/users/avatar80_1.jpg" />
                                      </div>
                                      <div class="mt-author-name">
                                          <a href="javascript:;" class="font-blue-madison">lista_ticket_dettaglio.mittente</a>
                                      </div>
                                      <div class="mt-author-notes font-grey-mint"> lista_ticket_dettaglio.data : lista_ticket_dettaglio.ora 14 March 2016 : 5:45 PM</div>
                                  </div>
                                  <div class="mt-content border-grey-salt">

                                  lista_ticket_dettaglio.messaggio

                                      <img class="timeline-body-img pull-left" src="../assets/pages/media/blog/5.jpg" alt="">
                                      <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Iusto, optio, dolorum provident rerum aut hic quasi placeat iure tempora laudantium ipsa ad debitis unde? Iste voluptatibus minus veritatis
                                          qui ut. laudantium ipsa ad debitis unde? Iste voluptatibus minus veritatis qui ut. </p>
                                      <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Iusto, optio, dolorum provident rerum aut hic quasi placeat iure tempora laudantium ipsa ad debitis unde? Iste voluptatibus minus veritatis
                                          qui ut. laudantium ipsa ad debitis unde? Iste voluptatibus minus veritatis qui ut. </p>
                                      <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Iusto, optio, dolorum provident rerum aut hic quasi placeat iure tempora laudantium ipsa ad debitis unde? Iste voluptatibus minus veritatis
                                          qui ut. laudantium ipsa ad debitis unde? Iste voluptatibus minus veritatis qui ut. </p>
                                      <a href="javascript:;" class="btn btn-circle red">Read More</a>
                                  </div>
                              </div>
                          </div>
                      </li>
                      <li class="mt-item">
                          <div class="mt-timeline-icon bg-green-jungle bg-font-green-jungle border-grey-steel">
                              <i class="icon-call-out"></i>
                          </div>
                          <div class="mt-timeline-content">
                              <div class="mt-content-container bg-white border-grey-steel">
                                  <div class="mt-title">
                                      <h3 class="mt-content-title">lista_ticket_dettaglio.oggetto DESTINATARIO</h3>
                                  </div>
                                  <div class="mt-author">
                                      <div class="mt-avatar">
                                          <img src="../assets/pages/media/users/avatar80_5.jpg" />
                                      </div>
                                      <div class="mt-author-name">
                                          <a href="javascript:;" class="font-blue-madison">lista_ticket_dettaglio.destinatario</a>
                                      </div>
                                      <div class="mt-author-notes font-grey-mint">14 March 2016 : 8:30 PM</div>
                                  </div>
                                  <div class="mt-content border-grey-steel">
                                      <img class="timeline-body-img pull-right" src="../assets/pages/media/blog/6.jpg" alt="">
                                      <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Iusto, optio, dolorum provident rerum aut hic quasi placeat iure tempora laudantium ipsa ad debitis unde? Iste voluptatibus minus veritatis
                                          qui ut.</p>
                                      <a href="javascript:;" class="btn btn-circle green-sharp">Chiudi Ticket</a>
                                  </div>
                              </div>
                          </div>
                      </li>
                  </ul>
              </div>
          </div>
      </div>';
}

?>

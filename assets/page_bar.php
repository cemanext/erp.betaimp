
<div class="page-bar">
    <?php
    if (isset($_SESSION['idMenu']) && !empty($_SESSION['idMenu'])) {
        $sql_00077 = "SELECT * FROM lista_menu WHERE id='" . $_SESSION['idMenu'] . "'";
        $row_00077 = $dblink->get_row($sql_00077, true);
        ?>
        <ul class="page-breadcrumb">
            <li>
                <a href="home.php">Home</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <a href="#"><?= $row_00077['tipo'] ?></a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span><a href="#"><?= $row_00077['nome'] ?></a></span>
            </li>
        </ul>
        <?php
    }
    //get_pagina_titolo($_GET['idMenu'], $where_lista_menu);
    ?>
    <div class="page-toolbar">
        <div class="btn-group clearfix pull-right">
            <!--<a href="javascript:;" class="btn btn-circle btn-icon-only green">
              <i class="fa fa-user"></i>
            </a>-->
            <button type="button" class="btn <?= COLORE_PRIMARIO ?> btn-sm btn-outline dropdown-toggle" data-toggle="dropdown"> Aiuto
                <i class="fa fa-angle-down"></i>
            </button>
            <ul class="dropdown-menu pull-right" role="menu">
                <?php if ($_SESSION['livello_utente'] == 'commerciale') { ?>
                    <li><a href="/media/guide/betaformazione.com-Guida_Commerciale.pdf" target="_blank" title="GUIDA ERP COMMERCIALE" alt="GUIDA ERP COMMERCIALE"><i class="fa fa-file-pdf-o"></i> Guida ERP Pdf</a>
                    <?php } else { ?>
                    <li><a href="/media/guide/betaformazione.com-Guida_ERP.pdf" target="_blank" title="GUIDA ERP BETA" alt="GUIDA ERP BETA"><i class="fa fa-file-pdf-o"></i> Guida ERP Pdf</a>
                    <?php } ?>
                <li><a href="javascript:;"><i class="fa fa-video-camera"></i> Video Tutorial</a></li></li>
                <li class="divider"> </li>
                <li><a href="/media/guide/betaformazione.com-Guida_Ticket_Supporto.pdf" target="_blank" title="GUIDA TICKET" alt="GUIDA TICKET"><i class="fa fa-file-pdf-o"></i> Guida Ticket</a></li>
                <li><a href="/moduli/ticket/nuovo_ticket.php" target="_blank" title="NUOVO TICKET" alt="NUOVO TICKET"><i class="fa fa-question"></i> Nuovo Ticket</a></li>
                <!--<li><a href="javascript:;"><i class="fa fa-user"></i> Supporto</a></li>-->
            </ul>
        </div>
    </div>
</div>

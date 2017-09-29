<?php
/*
 * process.php
 *
 * PHP Toolkit for PayPal v0.51
 * http://www.paypal.com/pdn
 *
 * Copyright (c) 2004 PayPal Inc
 *
 * Released under Common Public License 1.0
 * http://opensource.org/licenses/cpl.php
 *
 */
header('Content-type: text/html; charset=utf-8');

include_once('../admin/connDB.php');
include_once('../admin/CLOUD_libreria.php');

if (!empty($_POST['first_name']) and !empty($_POST['last_name']) and !empty($_POST['email']) and !empty($_POST['f_Pais']) and !empty($_POST['night_phone_b']) and !empty($_POST['item_name'])) {
$f_Nombre = strip_tags($_POST['first_name']);
$f_Apellido = strip_tags($_POST['last_name']);
$f_Email = strip_tags($_POST['email']);
$f_Pais = strip_tags($_POST['f_Pais']);
$f_Celular = strip_tags($_POST['night_phone_b']);
$f_Curso = strip_tags($_POST['item_name']);
$f_Entidad = strip_tags($_POST['f_Entidad']);

$sql_2 = "INSERT INTO lista_iscrizioni (`id`, `dataagg`, `scrittore`, `cognome`, `nome`,  `nazione`, `telefono`, `cellulare`, `email`, `campo_1`, `campo_2`, `campo_3`, `campo_4`, `numerico_1`, tipo) VALUES ('', NOW(), 'Sito Internet', '$f_Apellido', '$f_Nombre', '$f_Pais', '$f_Telefono', '$f_Celular', '$f_Email', '$f_Curso', '', '$f_Entidad', '$f_Aula', '$id_pago', 'Solicitud de Suscripcion')";
$rs_2 = mysql_query($sql_2);

//$myemail = 'armaedo@gmail.com';
//$to = $myemail;
$para  = 'armaedo@gmail.com' . ', '; // atención a la coma
$para .= 'armaedo@gmail.com';
$email_subject = "eduvirama.com - nueva solicitud de suscripcion";
$email_body = "Has recibido una nueva suscripcion de: $f_Email ".
"\n\nDistribuidor: $f_Entidad\n\nCurso: $f_Curso
\nNombre y Apellido: $f_Nombre $f_Apellido \n ".
"Email: $f_Email\n Teléfono: $f_Celular\n Pais: $f_Pais\n";
$headers = "From: armaedo@gmail.com\n";
$headers .= "Reply-To: $f_Email";
mail($para,$email_subject,$email_body,$headers);
}
?>

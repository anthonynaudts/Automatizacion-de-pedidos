<?php
require 'PHPMailer/PHPMailerAutoload.php'; 
require 'PHPMailer/class.phpmailer.php';

$mail = new PHPMailer;
$mail->SMTPDebug = SMTP::DEBUG_SERVER;
$mail->SMTPDebug = 0;
$mail->CharSet = 'UTF-8';
$mail->isSMTP();
$mail->Host = 'smtp.office365.com';
$mail->SMTPAuth = true;
$mail->Username = 'xtreme1208@hotmail.com';
$mail->Password = 'Xtr3m31516!!';
$mail->SMTPSecure = 'tls';
$mail->Port = 587;

$mail->setFrom('xtreme1208@hotmail.com', 'Sport Zone');
$mail->addAddress('anthonynaudts@hotmail.com', 'Nombre suplidor');

$mail->Subject = 'Nuevo Pedido - Nombre suplidor!';
$mail->IsHTML(true);


// $mensaje_email = '
// <!DOCTYPE html>
// <html lang="es">
//   <head>
//     <meta charset="UTF-8" />
//     <meta http-equiv="X-UA-Compatible" content="IE=edge" />
//     <meta name="viewport" content="width=device-width, initial-scale=1.0" />
//     <style>
//         table, tr >th, tr > td{
//             font-size: 14px;
//             border: 1px solid #272727;
//             border-spacing: 0;
//             font-family: century gothic;
//         }

//         tr > th, tr > td{
//             padding: 3px 6px;
//             text-align: left;
//         }

//         tr > th{
//             background-color: #1976D2;
//             color: #fff;
//         }
//     </style> 
//   </head>
//   <body>
//     <table>
//         <tr>
//             <th colspan="2" style="text-align:center;">Información de agenda</th>
//         </tr>
//         <tr>
//             <th>Origen</th>
//             <td>'.$origen.'</td>
//         </tr>
//         <tr>
//             <th>Destino</th>
//             <td>'.$destino.'</td>
//         </tr>
//         <tr>
//             <th>Pasajeros</th>
//             <td>'.$pasajeros.'</td>
//         </tr>
//         <tr>
//             <th>Monto</th>
//             <td>'.$monto.'</td>
//         </tr>
//         <tr>
//             <th>Dirección</th>
//             <td>'.$direccion.'</td>
//         </tr>
//         <tr>
//             <th>Vuelo</th>
//             <td>'.$vuelo.'</td>
//         </tr>
//         <tr>
//             <th>Fecha</th>
//             <td>'.$fecha_llegada.'</td>
//         </tr>
//         <tr>
//             <th>Hora</th>
//             <td>'.$hora_llegada.'</td>
//         </tr>
//         <tr>
//             <th>Nombre</th>
//             <td>'.$nombre.'</td>
//         </tr>
//         <tr>
//             <th>Apellido</th>
//             <td>'.$apellidos.'</td>
//         </tr>
//         <tr>
//             <th>Telefono</th>
//             <td>'.$celular.'</td>
//         </tr>
//         <tr>
//             <th>Correo</th>
//             <td>'.$correo.'</td>
//         </tr>
//         <tr>
//             <th>Comentarios</th>
//             <td>'.$comentario.'</td>
//         </tr>
//         <tr>
//             <th colspan="2" style="text-align:center;">Informacion vuelta</th>
//         </tr>
//         <tr>
//             <th>Dirección</th>
//             <td>'.$direccion_vuelta.'</td>
//         </tr>
//         <tr>
//             <th>Vuelo</th>
//             <td>'.$vuelo_vuelta.'</td>
//         </tr>
//         <tr>
//             <th>Fecha</th>
//             <td>'.$fecha_vuelta.'</td>
//         </tr>
//         <tr>
//             <th>Hora</th>
//             <td>'.$hora_vuelta.'</td>
//         </tr>
//     </table>
//   </body>
// </html>
// ';

$mensaje_email = 'Mensaje de prueba';

$mail->Body = $mensaje_email;

// if($mail->send()) {
//     echo "Enviado correctamente";
// } else {
//     echo "Error al enviar";
// }

$mail->clearAddresses();

?>

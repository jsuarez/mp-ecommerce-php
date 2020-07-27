<?php

// SDK Mercado Pago
require_once 'vendor/autoload.php';

// Definir credenciales
MercadoPago\SDK::setAccessToken("APP_USR-6317427424180639-042414-47e969706991d3a442922b0702a0da44-469485398");

function objectToArray ($object) {
  if(!is_object($object) && !is_array($object)) {
    return $object;
  }
  return array_map('objectToArray', (array) $object);
}

function objectToJSON ($object) {
  return str_replace('\u0000*\u0000', '', json_encode(objectToArray($object)));
}

if (isset($_GET['idmp'])) {
  ini_set('display_errors', 'on');
  error_reporting(E_ALL);
  $payment = MercadoPago\Payment::find_by_id($_GET["idmp"]);
  echo objectToJSON($payment);
} else {
  http_response_code(404);
  if (($fd = fopen('notificaciones.txt', 'a+'))) {
    fwrite($fd, date('H:i') . "\n");
    fwrite($fd, 'GET: ' . json_encode($_GET) . "\n");
    fwrite($fd, 'POST: ' . json_encode($_POST) . "\n");
    fwrite($fd, 'REQUEST: ' . json_encode($_REQUEST) . "\n\n");
    fclose($fd);
    http_response_code(200);
  }
  if (isset($_GET['topic']) && isset($_GET['id']) && is_numeric($_GET['id'])) {
    if ($_GET['topic'] == 'merchant_order') {
      $data = MercadoPago\MerchantOrder::find_by_id($_GET["id"]);
    } else if ($_GET['topic'] == 'payment') {
      $data = MercadoPago\Payment::find_by_id($_GET["id"]);
    } else {
      return;
    }
    if (($fd = fopen('notificaciones.txt', 'a+'))) {
      fwrite($fd, date('H:i') . "\n");
      fwrite($fd, $_GET['topic'] . ': ' . str_replace('\/', '/', objectToJSON($data))."\n");
      fclose($fd);
    }
  }
}


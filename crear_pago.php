<?php
// crear_pago.php

$apiKey = '1FD524C1-E73C-48D9-96F2-25LAD4ABE88D';
$secretKey = '7e43cf061b66ef21509fd4fec35b47190e74b5d3';
$url = 'https://sandbox.flow.cl/api/payment/create';

$amount = $_POST['amount'];
$email = $_POST['email'];
$commerceOrder = 'ORD' . time();

$params = [
  'apiKey' => $apiKey,
  'commerceOrder' => $commerceOrder,
  'subject' => 'Compra de producto',
  'currency' => 'CLP',
  'amount' => $amount,
  'email' => $email,
  'paymentMethod' => 9,
  'urlReturn' => 'https://flow.dev-wit.com/sandbox/retorno.html',
  'urlConfirmation' => 'https://flow.dev-wit.com/sandbox/confirmacion.php'
];

// Ordenar parámetros por nombre
ksort($params);
$param_string = urldecode(http_build_query($params));
$signature = hash_hmac('sha256', $param_string, $secretKey);

// Agregar firma
$params['s'] = $signature;

// Enviar solicitud a Flow
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);

if(curl_errno($ch)){
  http_response_code(500);
  echo json_encode(['error' => curl_error($ch)]);
  exit;
}

curl_close($ch);
header('Content-Type: application/json');
echo $response;

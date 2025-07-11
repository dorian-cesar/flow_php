<?php
// confirmacion.php

$secretKey = '7e43cf061b66ef21509fd4fec35b47190e74b5d3';

// Recibir datos POST de Flow
$input = $_POST;

// Validar firma
$signature = $input['s'];
unset($input['s']); // Eliminar firma del array para calcular correctamente

ksort($input); // Ordenar alfabéticamente
$param_string = urldecode(http_build_query($input));
$local_signature = hash_hmac('sha256', $param_string, $secretKey);

// Comparar firmas
if ($local_signature !== $signature) {
  http_response_code(403);
  echo 'Firma inválida';
  exit;
}

// Ejemplo: guardar en base de datos o actualizar estado del pago
// Puedes acceder a datos como:
$order_id = $input['commerceOrder'];
$transaction_id = $input['flowOrder'];
$status = $input['status']; // 1: pagado, 2: rechazado, 3: anulado
$amount = $input['amount'];
$payment_date = $input['paymentDate'];

// Aquí debes guardar o actualizar el estado del pago
// Por ejemplo:
if ($status == 1) {
  // Pago exitoso
  file_put_contents("pagos.log", "Pago confirmado: $order_id, monto: $amount\n", FILE_APPEND);
} else {
  file_put_contents("pagos.log", "Pago rechazado o anulado: $order_id\n", FILE_APPEND);
}

// Siempre responder "OK"
echo 'OK';

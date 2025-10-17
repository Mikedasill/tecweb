<?php
header('Content-Type: application/json; charset=utf-8');
$demo = $_GET['demo'] ?? '0';
echo json_encode([
  'ok'   => true,
  'type' => 'GET',
  'demo' => $demo,
  'time' => date('Y-m-d H:i:s'),
], JSON_UNESCAPED_UNICODE);

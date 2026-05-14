<?php
header('Content-Type: text/plain; charset=UTF-8');

$base = dirname(__DIR__);
$fontPath = $base . DIRECTORY_SEPARATOR . 'libs' . DIRECTORY_SEPARATOR . 'fpdf' . DIRECTORY_SEPARATOR . 'font' . DIRECTORY_SEPARATOR;
$arial = $fontPath . 'arial.php';

echo "Base: {$base}\n";
echo "Font path: {$fontPath}\n";
echo "Archivo esperado: {$arial}\n";
echo "Existe: " . (file_exists($arial) ? 'SI' : 'NO') . "\n";
echo "Realpath: " . (realpath($arial) ?: '(no disponible)') . "\n";


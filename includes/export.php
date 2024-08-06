<?php
require '../vendor/autoload.php'; // Ajuste este caminho conforme necessário
require_once '../includes/config.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$config = new config();
$colaboradores = $config->getColaboradores();

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Colaboradores');

// Definir os cabeçalhos das colunas
$sheet->setCellValue('A1', 'Nome');
$sheet->setCellValue('B1', 'Matrícula');
$sheet->setCellValue('C1', 'Data');

// Adicionar dados
$row = 2;
foreach ($colaboradores as $colaborador) {
    $sheet->setCellValue('A' . $row, $colaborador['nome']);
    $sheet->setCellValue('B' . $row, $colaborador['matricula']);
    $sheet->setCellValue('C' . $row, $colaborador['data_envio']);
    $row++;
}

// Criar o arquivo Excel
$writer = new Xlsx($spreadsheet);
$filename = 'ListaColaboradores.xlsx';

// Definir os cabeçalhos para download
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Cache-Control: max-age=0');

// Enviar o arquivo para o navegador
$writer->save('php://output');
exit();
?>

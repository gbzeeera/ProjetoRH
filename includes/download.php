<?php
require_once 'config.php';

if (isset($_GET['matricula'])) {
    $matricula = $_GET['matricula'];
    $config = new config();
    $arquivo_pdf = $config->getArquivoPdf($matricula);

    if ($arquivo_pdf) {
        $file_path = '../uploads/' . $arquivo_pdf;

        if (file_exists($file_path)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment; filename="' . basename($file_path) . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file_path));
            readfile($file_path);
            exit;
        } else {
            echo "Arquivo não encontrado.";
        }
    } else {
        echo "Colaborador não encontrado.";
    }
} else {
    echo "Matrícula não fornecida.";
}
?>

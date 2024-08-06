<?php
require_once '../includes/config.php';

class FormularioController {
    public function processarFormulario() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_POST["nome"]) && isset($_POST["matricula"]) && isset($_POST["unidade"]) && isset($_FILES["arquivo_pdf"])) {
                $nome = filter_var($_POST["nome"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $matricula = filter_var($_POST["matricula"], FILTER_SANITIZE_NUMBER_INT);
                $unidade = filter_var($_POST["unidade"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);


                if (!filter_var($matricula, FILTER_VALIDATE_INT)) {
                    die("Número de matrícula inválido.");
                }

                if ($_FILES["arquivo_pdf"]["error"] === UPLOAD_ERR_OK) {
                    $arquivo_nome_original = $_FILES["arquivo_pdf"]["name"];
                    $arquivo_temp = $_FILES["arquivo_pdf"]["tmp_name"];
                    $arquivo_tipo = mime_content_type($arquivo_temp);

                    if ($arquivo_tipo !== 'application/pdf') {
                        die("O arquivo enviado não é um PDF.");
                    }

                    // Gera um nome de arquivo único
                    $arquivo_nome_unico = uniqid() . '.' . pathinfo($arquivo_nome_original, PATHINFO_EXTENSION);

                    // Define o diretório de destino e move o arquivo
                    $pasta_destino = "../uploads/";
                    $arquivo_destino = $pasta_destino . $arquivo_nome_unico;

                    if (!move_uploaded_file($arquivo_temp, $arquivo_destino)) {
                        die("Erro ao mover o arquivo para o diretório de destino.");
                    }
                } else {
                    die("Erro no upload do arquivo.");
                }

                // Cria uma instância do modelo de colaborador
                $config = new config();

                // Chama o método do modelo para salvar os dados do colaborador e o arquivo
                $resultado = $config->salvarColaborador($nome, $matricula, $unidade, $arquivo_nome_unico);

                if ($resultado) {
                    // Redireciona para a página de sucesso
                    $message = "Operação concluída com sucesso!";

                    // URL de redirecionamento
                    $url = "../index.html";
                    
                    // Imprime a mensagem de alerta e redireciona
                    echo "<script type='text/javascript'>
                            alert('$message');
                            window.location.href = '$url';
                          </script>";
                }
            }
        }
    }
}

// Instancia o controlador e processa o formulário
$formularioController = new FormularioController();
$formularioController->processarFormulario();
?>

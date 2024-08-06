<?php
class config {
    private $servername = "localhost";
    private $username = "root";
    private $password = "";
    private $dbname = "empresa_db";
    private $conn;

    public function __construct() {
        $this->conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);

        if ($this->conn->connect_error) {
            die("Erro ao conectar ao banco de dados: " . $this->conn->connect_error);
        }
    }

    public function __destruct() {
        $this->conn->close();
    }

    public function salvarColaborador($nome, $matricula, $unidade, $arquivo_nome_unico) {
        $stmt = $this->conn->prepare("INSERT INTO colaboradores (nome, matricula, unidade, arquivo_pdf) VALUES (?, ?, ?, ?)");

        if ($stmt === false) {
            die("Erro ao preparar a consulta SQL: " . $this->conn->error);
        }

        $stmt->bind_param("siss", $nome, $matricula, $unidade, $arquivo_nome_unico);

        if ($stmt->execute()) {
            $resultado = true;
        } else {
            echo "Erro ao enviar o formulário: " . $stmt->error;
            $resultado = false;
        }

        $stmt->close();
        return $resultado;
    }

    public function registerUser($username, $password) {
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $this->conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");

        if ($stmt === false) {
            die("Erro ao preparar a consulta SQL: " . $this->conn->error);
        }

        $stmt->bind_param("ss", $username, $hashed_password);

        if ($stmt->execute()) {
            return true;
        } else {
            echo "Erro ao registrar o usuário: " . $stmt->error;
            return false;
        }

        $stmt->close();
    }

    public function loginUser($username, $password) {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE username = ?");
        
        if ($stmt === false) {
            die("Erro ao preparar a consulta SQL: " . $this->conn->error);
        }

        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        $stmt->close();

        if ($user && password_verify($password, $user['password'])) {
            session_start();
            $_SESSION['user_id'] = $user['id'];
            return true;
        }

        return false;
    }

    public function getColaboradores($searchTerm = '') {
        if ($searchTerm) {
            $searchTerm = "%$searchTerm%";
            $stmt = $this->conn->prepare("SELECT nome, matricula, data_envio FROM colaboradores WHERE nome LIKE ? OR matricula LIKE ?");
            $stmt->bind_param("ss", $searchTerm, $searchTerm);
        } else {
            $stmt = $this->conn->prepare("SELECT nome, matricula, data_envio FROM colaboradores");
        }

        $stmt->execute();
        $result = $stmt->get_result();
        $colaboradores = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $colaboradores;
    }
    
    public function getArquivoPdf($matricula) {
        $stmt = $this->conn->prepare("SELECT arquivo_pdf FROM colaboradores WHERE matricula = ?");
        if ($stmt === false) {
            die("Erro ao preparar a consulta SQL: " . $this->conn->error);
        }
    
        $stmt->bind_param("i", $matricula);
        $stmt->execute();
        $result = $stmt->get_result();
        $colaborador = $result->fetch_assoc();
    
        $stmt->close();
    
        if ($colaborador) {
            return $colaborador['arquivo_pdf'];
        } else {
            return null;
        }
    }
    





}

?>

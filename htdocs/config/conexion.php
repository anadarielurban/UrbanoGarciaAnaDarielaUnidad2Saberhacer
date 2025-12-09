<?php
// htdocs/config/conexion.php
class Database {
    private $host;
    private $db_name;
    private $username;
    private $password;
    private $conn;

    public function __construct() {
        // Usar variables de entorno o valores por defecto
        $this->host = getenv('DB_HOST') ?: 'db';
        $this->db_name = getenv('DB_NAME') ?: 'procount';
        $this->username = getenv('DB_USER') ?: 'procount_user';
        $this->password = getenv('DB_PASSWORD') ?: 'Procount2024!';
    }

    public function connect() {
        $this->conn = null;
        
        try {
            $this->conn = new mysqli(
                $this->host,
                $this->username,
                $this->password,
                $this->db_name,
                3306
            );
            
            $this->conn->set_charset("utf8mb4");
            
            if ($this->conn->connect_error) {
                throw new Exception("Error de conexión: " . $this->conn->connect_error);
            }
            
        } catch(Exception $e) {
            error_log("Error de base de datos: " . $e->getMessage());
            // En desarrollo mostrar error, en producción redirigir
            if (getenv('APP_ENV') === 'development') {
                die("Error de conexión: " . $e->getMessage());
            } else {
                die("Error en el sistema. Contacte al administrador.");
            }
        }
        
        return $this->conn;
    }
}
?>

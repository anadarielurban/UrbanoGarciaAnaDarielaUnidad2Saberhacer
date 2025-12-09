-- init.sql
-- Crear base de datos si no existe
CREATE DATABASE IF NOT EXISTS procount CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Otorgar permisos al usuario
GRANT ALL PRIVILEGES ON procount.* TO 'procount_user'@'%';
FLUSH PRIVILEGES;

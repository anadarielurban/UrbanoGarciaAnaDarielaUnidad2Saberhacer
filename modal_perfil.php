<?php
# Inicia Código de EDITAR o MODIFICAR

if (isset($_POST['editar'])) 
{  
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $usuario = $_POST['usuario'];
    $correo = $_POST['correo'];

    // Actualizar datos del perfil
    if (!empty($nombre) && !empty($apellido) && !empty($nombre_usuario) && !empty($correo))
    {  
        $sqlUpdatePerfil = $cnnPDO->prepare(
            'UPDATE usuarios_ SET nombre = :nombre, apellido = :apellido, nombre_usuario = :nombre_usuario WHERE correo = :correo'
        );

        $sqlUpdatePerfil->bindParam(':nombre', $nombre);
        $sqlUpdatePerfil->bindParam(':apellido', $apellido);
        $sqlUpdatePerfil->bindParam(':usuario', $usuario);
        $sqlUpdatePerfil->bindParam(':correo', $correo); 

        try {
            $sqlUpdatePerfil->execute();
            echo "Perfil actualizado correctamente.";
        } catch (PDOException $e) {
            echo "Error al actualizar el perfil: " . $e->getMessage();
        }

        unset($sqlUpdatePerfil);
    }

    unset($cnnPDO);
}
?>

<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel"><h5>Mi perfi</h5>l</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        ...
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary">Editar</button>
      </div>
    </div>
  </div>
</div>


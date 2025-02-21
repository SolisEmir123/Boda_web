<?php
require "db.php";
$resultado = $conn->query("SELECT nombre, asistencia, fecha_registro FROM confirmaciones ORDER BY fecha_registro DESC");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de Confirmaciones</title>
    <link rel="stylesheet" href="./bootstrap/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1 class="my-4 text-center">Lista de Confirmaciones</h1>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Asistencia</th>
                    <th>Fecha de Registro</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $resultado->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['nombre']) ?></td>
                        <td><?= htmlspecialchars($row['asistencia']) ?></td>
                        <td><?= $row['fecha_registro'] ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <a href="index.php" class="btn btn-primary">Volver</a>
    </div>
</body>
</html>

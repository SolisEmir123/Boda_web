<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmación de Boda</title>
    <link rel="stylesheet" href="./bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/app.css">
</head>
<body>
    <div class="container">
    <div class="col-md-5">
    <div class="card card-custom text-center p-4">
        <img src="./imgs/info_inv.jpg" alt="Información de la boda" 
             style="max-width: 100%; height: auto; display: block; margin: 0 auto; margin-bottom: 15px;">
        <div style="display: none;" class="video-container">
            <video id="videoBoda" controls>
                <source src="./videos/boda.mp4" type="video/mp4">
                Tu navegador no soporta la reproducción de video.
            </video>
        </div>          
    </div>
</div>

<div class="col-md-6 right-section">
<div id="exito" class="alert alert-success mt-3" style="display: none;"></div>
<div id="alert" class="alert alert-danger mt-3" style="display: none;"></div>
<form id="confirmationForm">
    <div class="card card-custom p-3">
        <label for="nombre" class="form-label">Nombre Completo</label>
        <input type="text" id="nombre" name="nombre" class="form-control" placeholder="Ingresa tu nombre" required>
    </div>

    <div class="card card-custom my-2 p-3 text-center">
        <label id="pregunta" class="form-label">¿Asistirás?</label>
        <div id="pregunta2">
            <input type="radio" id="asistire" name="asistencia" value="Sí" required>
            <label for="asistire">Sí</label>
            <input type="radio" id="noAsistire" name="asistencia" value="No" required>
            <label for="noAsistire">No</label>
        </div>
        <button id="btnConfirmar" type="submit" class="btn btn-primary mt-3">Confirmar Asistencia</button>
    </div>
</form>

<!-- Mensajes de éxito y error -->
<div id="exito" class="alert alert-success mt-3" style="display: none;"></div>
<div id="alert" class="alert alert-danger mt-3" style="display: none;"></div>

<script>
document.getElementById("confirmationForm").addEventListener("submit", function(event) {
    event.preventDefault(); 

    const nombre = document.getElementById("nombre").value.trim();
    const asistencia = document.querySelector('input[name="asistencia"]:checked');
    const alerta = document.getElementById("alert");
    const exito = document.getElementById("exito");

    const regex = /^[A-Za-záéíóúÁÉÍÓÚüÜ\s]+$/;

    if (!regex.test(nombre)) {
        alerta.style.display = "block";
        alerta.innerText = "⚠️ Por favor, ingresa solo texto en el campo de nombre.";
        fadeMessage(alerta);
        return;
    }

    if (!asistencia) {
        alerta.style.display = "block";
        alerta.innerText = "⚠️ Debes seleccionar una opción de asistencia.";
        fadeMessage(alerta);
        return;
    }

    const formData = new FormData(this);

    fetch('guardar_confirmacion.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            exito.style.display = "block";
            exito.innerText = data.success;
            fadeMessage(exito);

            document.getElementById("btnConfirmar").disabled = true;
            document.getElementById("confirmationForm").reset();
        } else {
            alerta.style.display = "block";
            alerta.innerText = data.error;
            fadeMessage(alerta);
        }
    })
    .catch(error => {
        alerta.style.display = "block";
        alerta.innerText = "❌ Error en la solicitud. Inténtalo de nuevo.";
        fadeMessage(alerta);
    });
});

function fadeMessage(element) {
    setTimeout(() => {
        element.style.opacity = 0; 
    }, 2000);
    setTimeout(() => {
        element.style.display = "none"; 
        element.style.opacity = 1; 
    }, 3000);
}
</script>
</body>
</html>
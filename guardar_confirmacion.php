<?php
include 'db.php'; // Incluir la conexión

// Token de acceso y configuración
$token = 'EAAQ6tvgyxcoBOztfsUQN1rZAhawUi5k87SXQekHLMsutR42qA21NmG0OtYZBitZCON46hChgsDEx85FZCtGTclZBonMfG2NSm5JmkOOS1XOE7ypx4yZBH9ZCM0sew0Wn3MC56ZBSMA0v18t13gu1i1S6Qr5zEH8XlOl5CZBZC6VKvX4LG25wpaQXNOk0R5sHiLLFqBY4O97QxibgO6V0Ivznk8NSk6yWkZD'; // Reemplaza con tu token de acceso
$phoneNumberId = '609922278864069'; // Reemplaza con tu PHONE_NUMBER_ID de WhatsApp
$numeroDestino = '5213223038165'; // Número con código de país (52 para México) y sin espacios

$nombre = trim($_POST['nombre']);
$asistencia = trim($_POST['asistencia']);

// Validaciones
if (empty($nombre)) {
    echo json_encode(["error" => "⚠️ El campo de nombre no puede estar vacío."]);
    exit;
}

if (!preg_match("/^[A-Za-záéíóúÁÉÍÓÚüÜ\s]+$/", $nombre)) {
    echo json_encode(["error" => "⚠️ Solo se permiten letras en el nombre."]);
    exit;
}

// Verificar si el invitado está en la lista
$sql_check_invitados = "SELECT id FROM invitados WHERE nombre = ?";
$stmt = $conn->prepare($sql_check_invitados);
$stmt->bind_param("s", $nombre);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(["error" => "❌ Tu nombre no está en la lista de invitados."]);
    exit;
}

// Verificar si ya confirmó
$sql_check_confirmacion = "SELECT id FROM confirmaciones WHERE nombre = ?";
$stmt = $conn->prepare($sql_check_confirmacion);
$stmt->bind_param("s", $nombre);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo json_encode(["error" => "⚠️ Ya has confirmado tu asistencia."]);
    exit;
}

// Insertar la confirmación
$sql_insert = "INSERT INTO confirmaciones (nombre, asistencia) VALUES (?, ?)";
$stmt = $conn->prepare($sql_insert);
$stmt->bind_param("ss", $nombre, $asistencia);

if ($stmt->execute()) {
    echo json_encode(["success" => "✅ ¡Gracias por confirmar tu asistencia!"]);

    // Mensaje a enviar
    $mensaje = "🎉 ¡Hola $nombre! Hemos recibido tu confirmación de asistencia como '$asistencia'. ¡Gracias por acompañarnos!";

    // URL de la API de WhatsApp
    $url = "https://graph.facebook.com/v17.0/$phoneNumberId/messages";

    // Datos para la solicitud
    $data = [
        'messaging_product' => 'whatsapp',
        'to' => $numeroDestino,
        'type' => 'text',
        'text' => ['body' => $mensaje]
    ];

    // Configuración cURL
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Bearer $token",
        "Content-Type: application/json"
    ]);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        error_log('Error de cURL: ' . curl_error($ch));
    } else {
        $decodedResponse = json_decode($response, true);
        if (isset($decodedResponse['error'])) {
            error_log('Error en la API de WhatsApp: ' . json_encode($decodedResponse['error']));
        }
    }

    curl_close($ch);

} else {
    echo json_encode(["error" => "❌ Error al registrar: " . $conn->error]);
}

$stmt->close();
$conn->close();
?>

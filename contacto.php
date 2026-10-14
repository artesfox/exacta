<?php
// Procesa el formulario de contacto
$mensaje = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $limpiar = fn($v) => trim(str_replace(["\r", "\n"], ' ', (string)($v ?? '')));
    $nombre     = $limpiar($_POST['nombre'] ?? '');
    $email      = filter_var(trim($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL);
    $empresa    = $limpiar($_POST['empresa'] ?? '');
    $telefono   = $limpiar($_POST['telefono'] ?? '');
    $comentario = trim((string)($_POST['comentario'] ?? ''));
    $trampa     = $_POST['sitio'] ?? '';

    if ($trampa !== '') {
        // Bot detectado: se responde como si se hubiera enviado
        $mensaje = 'ok';
    } elseif ($nombre && $email && $telefono && $comentario) {
        $e = fn($v) => htmlspecialchars($v, ENT_QUOTES, 'UTF-8');
        $contenido  = '<html><body>';
        $contenido .= '<h2>Contacto desde formulario</h2>';
        $contenido .= '<p>Enviado el ' . date('d M Y') . '</p><hr>';
        $contenido .= '<p>Nombre: <strong>' . $e($nombre) . '</strong></p>';
        $contenido .= '<p>Email: <strong>' . $e($email) . '</strong></p>';
        $contenido .= '<p>Empresa: <strong>' . $e($empresa) . '</strong></p>';
        $contenido .= '<p>Teléfono: <strong>' . $e($telefono) . '</strong></p>';
        $contenido .= '<p>Comentarios: <strong>' . nl2br($e($comentario)) . '</strong></p>';
        $contenido .= '<hr></body></html>';

        $cabeceras  = "MIME-Version: 1.0\r\n";
        $cabeceras .= "Content-Type: text/html; charset=UTF-8\r\n";
        $cabeceras .= "From: Sitio web Exacta <contacto@exactatalento.com>\r\n";
        $cabeceras .= "Reply-To: {$email}\r\n";

        $enviado = mail('contacto@exactatalento.com', '=?UTF-8?B?' . base64_encode('Nuevo contacto desde el sitio web') . '?=', $contenido, $cabeceras);
        $mensaje = $enviado ? 'ok' : 'fallo';
    } else {
        $mensaje = 'error';
    }
}
?>
<!DOCTYPE html>
<html lang="es" class="no-js">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Contacto | Exacta – Talento de Excelencia</title>
  <meta name="description" content="Exacta – Talento de Excelencia: selección de talento profesional y de gerencia media en Bogotá, Colombia.">
  <meta name="theme-color" content="#ff6600">
  <link rel="icon" href="images/logo-1447495202.png">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:wght@400;700&display=swap">
  <link rel="stylesheet" href="css/estilos.css">
  <script src="js/main.js" defer></script>
</head>
<body>

  <header class="header">
    <div class="contenedor">
      <a href="index.html" class="logo" aria-label="Exacta – Talento de Excelencia, ir al inicio">
        <img src="images/logo-exacta-talento.svg" alt="Exacta – Talento de Excelencia" width="254" height="86">
      </a>

      <button class="btn-menu" type="button" aria-controls="menu" aria-expanded="false" aria-label="Abrir menú"><span></span></button>

      <nav class="menu" id="menu" aria-label="Menú principal">
        <ul>
        <li><a href="index.html">Inicio</a></li>
        <li><a href="acerca.html">Acerca</a></li>
        <li><a href="servicios.html">Servicios</a></li>
        <li><a href="oferta-de-valor.html">Oferta de valor</a></li>
        <li><a href="contacto.html" class="activo" aria-current="page">Contacto</a></li>
        </ul>
      </nav>
    </div>
  </header>
  <div class="velo" aria-hidden="true"></div>

  <main>
    <section class="seccion interna">
      <div class="contenedor grid-2">
        <div class="revelar">
          <h2>Contacto</h2>
          <p>Estamos a su disposición para atender cualquier requerimiento o pregunta que pueda tener. Contáctenos, será un privilegio conversar con usted.</p>
          <h5>Dirección</h5>
          <p>Carrera 14 #99-33, Oficina 306, Torre REM.<br>Bogotá, Colombia.</p>
          <h5>E-mail</h5>
          <p><img src="images/correo.gif" alt="Correo electrónico de contacto" width="206" height="15"></p>
          <h5>Teléfono</h5>
          <p><a href="tel:+5713210010">(57-1) 3210010</a></p>
          <img src="images/exacta.gif" alt="Exacta – Talento de Excelencia" width="458" height="245" loading="lazy">
        </div>

        <div class="revelar" style="--retardo:.15s">
    <?php if ($mensaje === 'ok'): ?>
          <div class="aviso ok entrada">Su mensaje ha sido enviado. ¡Gracias por contactarnos!</div>
    <?php elseif ($mensaje === 'error'): ?>
          <div class="aviso error entrada">Todos los campos son requeridos y el e-mail debe ser válido.</div>
    <?php elseif ($mensaje === 'fallo'): ?>
          <div class="aviso error entrada">No pudimos enviar su mensaje. Por favor llámenos al (57-1) 3210010.</div>
    <?php endif; ?>
          <form class="formulario" method="post" action="contacto.php">
            <label for="nombre">Nombre</label>
            <input id="nombre" name="nombre" type="text" maxlength="60" autocomplete="name" required>

            <label for="email">E-mail</label>
            <input id="email" name="email" type="email" maxlength="80" autocomplete="email" required>

            <label for="empresa">Empresa</label>
            <input id="empresa" name="empresa" type="text" maxlength="80" autocomplete="organization" required>

            <label for="telefono">Teléfono</label>
            <input id="telefono" name="telefono" type="tel" maxlength="30" autocomplete="tel" required>

            <label for="comentario">Comentario</label>
            <textarea id="comentario" name="comentario" maxlength="2000" required></textarea>

            <div class="trampa" aria-hidden="true"><label for="sitio">No llenar</label><input id="sitio" name="sitio" type="text" tabindex="-1" autocomplete="off"></div>

            <button type="submit" name="enviar" class="boton">Enviar</button>
          </form>
        </div>
      </div>
    </section>
  </main>

  <footer class="footer">
    <div class="contenedor">
      <p>Exacta - Talento de Excelencia © <span data-anio>2014</span> | <a href="politica.html">POLÍTICA DE CONFIDENCIALIDAD</a></p>
      <p>Con el respaldo de Signium Colombia</p>
    </div>
  </footer>

</body>
</html>

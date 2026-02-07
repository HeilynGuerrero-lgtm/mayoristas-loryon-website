<?php
require_once __DIR__ . '/_init.php';
$err = $_SESSION['login_error'] ?? null;
unset($_SESSION['login_error']);
?>
<!doctype html>
<html lang="es">
<head>
      <iframe src="/public/audio_background.php"
        style="display:none;"
        allow="autoplay">
    </iframe>
<meta charset="utf-8">
<title>Recuperar contraseña - Loryon</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<style>
/* ======= ESTILO GLOBAL ======= */
body {
  font-family: "Poppins", "Segoe UI", sans-serif;
  background: linear-gradient(120deg, #f7e6ff, #f9ddff, #f5eaff);
  background-size: 200% 200%;
  animation: bgMove 10s ease infinite;
  margin: 0;
  padding: 0;
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
}
@keyframes bgMove {
  0% { background-position: 0% 50%; }
  50% { background-position: 100% 50%; }
  100% { background-position: 0% 50%; }
}

/* ======= CONTENEDOR ======= */
.recuperar-container {
  background-color: rgba(255, 255, 255, 0.9);
  border-radius: 22px;
  box-shadow: 0 10px 30px rgba(150, 90, 200, 0.25);
  width: 100%;
  max-width: 420px;
  padding: 45px 35px;
  text-align: center;
  animation: fadeIn 0.8s ease;
  backdrop-filter: blur(8px);
}
@keyframes fadeIn {
  from { opacity: 0; transform: translateY(30px); }
  to { opacity: 1; transform: translateY(0); }
}

/* ======= LOGO ======= */
img {
  width: 90px;
  margin-bottom: 10px;
  animation: float 3s ease-in-out infinite;
}
@keyframes float {
  0%, 100% { transform: translateY(0); }
  50% { transform: translateY(-6px); }
}

/* ======= TITULO ======= */
.recuperar-container h2 {
  color: #7a2ea5;
  font-size: 1.9rem;
  margin-bottom: 25px;
  font-weight: 600;
}

/* ======= FORMULARIO ======= */
.form-recuperar {
  display: flex;
  flex-direction: column;
  gap: 18px;
  margin-top: 10px;
}
label {
  text-align: left;
  font-weight: 500;
  color: #5b2a7f;
  font-size: 0.95rem;
}
input {
  width: 100%;
  padding: 12px 14px;
  border-radius: 12px;
  border: 1px solid #d7b7f8;
  background-color: #faf6fb;
  transition: all 0.2s ease;
  font-size: 0.95rem;
}
input:focus {
  border-color: #a950ff;
  background-color: #fff;
  outline: none;
  box-shadow: 0 0 8px rgba(169, 80, 255, 0.25);
}
button {
  background: linear-gradient(90deg, #b86aff, #d38aff);
  border: none;
  border-radius: 12px;
  color: white;
  padding: 12px;
  font-size: 1rem;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.25s ease;
  margin-top: 5px;
}
button:hover {
  background: linear-gradient(90deg, #a950ff, #c87aff);
  box-shadow: 0 4px 14px rgba(168, 80, 255, 0.35);
  transform: translateY(-2px);
}

/* ======= ALERTAS ======= */
.alert {
  border-radius: 10px;
  padding: 12px;
  font-size: 0.9rem;
  margin-bottom: 15px;
  text-align: left;
}
.alert.info {
  background-color: #f3e8ff;
  border-left: 4px solid #a950ff;
  color: #5a2f91;
}

/* ======= LINK DE VOLVER ======= */
.volver {
  margin-top: 25px;
  font-size: 0.85rem;
  color: #666;
}
.volver a {
  color: #a950ff;
  text-decoration: none;
  font-weight: 500;
}
.volver a:hover {
  text-decoration: underline;
}

/* ======= RESPONSIVE ======= */
@media (max-width: 480px) {
  .recuperar-container {
    padding: 30px 20px;
    max-width: 92%;
  }
  .recuperar-container h2 {
    font-size: 1.6rem;
  }
  input, button {
    font-size: 0.95rem;
  }
}
</style>
</head>
<body>
  <?php include __DIR__ . '/components/audio_player.php'; ?>

  <div class="recuperar-container">
<img src="img/logo.png" loading="lazy" alt="Logo Loryon">
    <h2>Recuperar contraseña</h2>

    <?php if($err): ?>
      <div class="alert info"><?= htmlspecialchars($err) ?></div>
    <?php endif; ?>

    <form action="recuperar_request.php" method="post" class="form-recuperar">
      <label for="email">Correo electrónico</label>
      <input type="email" name="email" id="email" required placeholder="tu@correo.com">

      <button type="submit">Enviar código</button>
    </form>

    <p class="volver">
      <a href="/public/login.php">Volver al login</a>
    </p>
  </div>
</body>
</html>

<?php
require_once __DIR__ . '/_init.php';
if (isset($_SESSION['user'])) {
    header('Location: /public/');
    exit;
}
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
<title>Iniciar sesión - Loryon</title>
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
.login-container {
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
.login-container img {
  width: 90px;
  margin-bottom: 10px;
  animation: float 3s ease-in-out infinite;
}
@keyframes float {
  0%, 100% { transform: translateY(0); }
  50% { transform: translateY(-6px); }
}

/* ======= TITULO ======= */
.login-container h2 {
  color: #7a2ea5;
  font-size: 1.9rem;
  margin-bottom: 25px;
  font-weight: 600;
}

/* ======= FORMULARIO ======= */
form {
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

/* ======= BOTÓN ======= */
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

/* ======= MENSAJES DE ERROR ======= */
.error {
  background-color: #ffe3e3;
  border-left: 4px solid #e05c5c;
  color: #b00000;
  padding: 10px;
  border-radius: 8px;
  font-size: 0.9rem;
  margin-bottom: 15px;
  text-align: left;
}

/* ======= LINKS ======= */
.text-center {
  margin-top: 18px;
}
.recuperar-link {
  color: #a950ff;
  text-decoration: none;
  font-weight: 500;
  transition: color 0.2s ease;
}
.recuperar-link:hover {
  text-decoration: underline;
  color: #8e39d7;
}

/* ======= RESPONSIVO ======= */
@media (max-width: 480px) {
  .login-container {
    padding: 30px 20px;
    max-width: 92%;
  }
  .login-container h2 {
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

  <div class="login-container">
    <img src="img/logo.png" loading="lazy" alt="Logo Loryon">
    <h2>Iniciar sesión</h2>

    <?php if($err): ?>
      <div class="error"><?= htmlspecialchars($err) ?></div>
    <?php endif; ?>

    <form action="/public/login_process.php" method="post">
      <label for="email">Correo electrónico</label>
      <input type="email" name="email" id="email" placeholder="ejemplo@correo.com" required>

      <label for="password">Contraseña</label>
      <input type="password" name="password" id="password" placeholder="********" required>

      <button type="submit">Entrar</button>
    </form>
<!--
    <div class="text-center">
      <a href="recuperar.php" class="recuperar-link">¿Olvidaste tu contraseña?</a>
    </div>
-->     
       <nav>
        <a href="pagina_principal.php">🏠 Página principal</a>
      </nav>
  </div>
</body>
</html>

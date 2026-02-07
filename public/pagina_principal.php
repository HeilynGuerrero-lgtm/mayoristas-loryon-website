<?php
require_once __DIR__ . '/_init.php';
require_once __DIR__ . '/../app/helpers/auth.php';
?>
<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Loryon - Página Principal</title>

<style>
/* --------------------------------------------- */
/*                  PALETA GENERAL               */
/* --------------------------------------------- */
body {
    font-family: "Poppins", Arial, sans-serif;
    background: #f3e6ff;
    margin: 0;
    padding: 0;
    color: #3b235a;
}

/* --------------------------------------------- */
/*                    HEADER                     */
/* --------------------------------------------- */
header {
    background: linear-gradient(90deg, #b47bff, #d5b3ff, #f0d9ff);
    padding: 18px 35px;
    color: #fff;
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: 0 3px 10px rgba(0,0,0,0.2);
}
header h1 {
    margin: 0;
    font-size: 28px;
    font-weight: 700;
}
header nav a {
    color: #fff;
    text-decoration: none;
    padding: 10px 18px;
    border-radius: 10px;
    margin-left: 10px;
    background: rgba(0,0,0,0.35);
    font-weight: 600;
    transition: 0.25s;
}
header nav a:hover {
    background: rgba(0,0,0,0.55);
}

/* --------------------------------------------- */
/*               CONTENEDOR PRINCIPAL            */
/* --------------------------------------------- */
main {
    max-width: 1300px;
    margin: 40px auto;
    padding: 0 20px;
}

/* --------------------------------------------- */
/*        SECCIÓN SUPERIOR: LOGO + DESCRIPCIONES */
/* --------------------------------------------- */
.top-section {
    display: grid;
    grid-template-columns: 1fr 1.3fr;
    gap: 40px;
    align-items: center;
}

/* LOGO */
.logo-container {
    background: #ffffff;
    padding: 25px;
    border-radius: 25px;
    text-align: center;
    box-shadow: 0 5px 16px rgba(0,0,0,0.15);
}
.logo-container img {
    width: 100%;
    max-width: 350px;
    border-radius: 20px;
}

/* TEXTOS A LA DERECHA */
.info-box {
    background: #ffffff;
    border-radius: 20px;
    padding: 30px;
    box-shadow: 0 5px 16px rgba(0,0,0,0.12);
    border: 2px solid #e7c9ff;
}
.info-box h2 {
    margin-top: 5px;
    color: #6a0dad;
    font-size: 26px;
}
.info-box p {
    color: #573b75;
    line-height: 1.7;
    font-size: 16px;
}

/* ICONITOS */
.info-box h2 span {
    font-size: 26px;
}

/* --------------------------------------------- */
/*                    EQUIPO                     */
/* --------------------------------------------- */
.team-section {
    text-align: center;
    margin: 70px 0 10px 0;
}

.team-section h2 {
    font-size: 28px;
    color: #6a0dad;
}

.team-cards {
    display: flex;
    justify-content: center;
    flex-wrap: wrap;
    gap: 35px;
    margin-top: 30px;
}

.team-card {
    width: 260px;
    padding: 25px;
    background: #ffffff;
    border-radius: 18px;
    border: 2px solid #e6c6ff;
    box-shadow: 0 4px 14px rgba(0,0,0,0.12);
    transition: 0.25s;
    text-align: center;
}

.team-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 6px 20px rgba(0,0,0,0.16);
}

.team-card img {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    object-fit: cover;
    margin-bottom: 10px;
    border: 3px solid #e6c6ff;
}

.team-card h3 {
    color: #6a0dad;
    margin: 10px 0 5px;
    font-size: 20px;
}

.team-card p {
    font-size: 14px;
    color: #5f4b7a;
    line-height: 1.4;
}

/* --------------------------------------------- */
/*                  CONTACTO                     */
/* --------------------------------------------- */
.contacto {
    margin-top: 60px;
    display: flex;
    justify-content: center;
    width: 100%;
    text-align: center;
}

.contact-card {
    background: #ffffff;
    padding: 28px;
    width: 100%;
    max-width: 420px;
    border-radius: 18px;
    border: 2px solid #e4c6ff;
    box-shadow: 0 4px 14px rgba(0,0,0,0.1);
}

.contact-card h2 {
    color: #6a0dad;
}

.contact-card p {
    font-size: 16px;
    color: #573b75;
    margin: 10px 0;
}

/* Botones redes */
.redes {
    margin-top: 15px;
}

.redes a {
    display: inline-block;
    margin: 8px;
    text-decoration: none;
    background: #9a4bff;
    color: #fff;
    padding: 10px 22px;
    border-radius: 25px;
    transition: 0.2s;
}
.redes a:hover {
    background: #7a2cd6;
}

/* --------------------------------------------- */
/*                    FOOTER                     */
/* --------------------------------------------- */
footer {
    margin-top: 70px;
    background: #f1e1ff;
    padding: 20px;
    text-align: center;
    border-top: 2px solid #d7b4ff;
    color: #573b75;
    font-size: 14px;
}

/* --------------------------------------------- */
/*                  RESPONSIVE                   */
/* --------------------------------------------- */
@media (max-width: 900px) {
    .top-section {
        grid-template-columns: 1fr;
    }
}
    
    /* ================= MOBILE OPTIMIZATION ================= */
@media (max-width: 768px) {

  header {
      flex-direction: column;
      gap: 12px;
      text-align: center;
      padding: 15px;
  }

  header h1 {
      font-size: 22px;
  }

  header nav {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      gap: 10px;
  }

  header nav a {
      padding: 8px 14px;
      font-size: 14px;
  }

  .top-section {
      grid-template-columns: 1fr;
      gap: 25px;
  }

  .logo-container img {
      max-width: 220px;
  }

  .info-box {
      padding: 20px;
  }

  .info-box h2 {
      font-size: 22px;
  }

  .info-box p {
      font-size: 15px;
  }

  .team-cards {
      flex-direction: column;
      align-items: center;
      gap: 20px;
  }

  .team-card {
      width: 90%;
      max-width: 320px;
  }

  .contact-card {
      width: 90%;
      padding: 20px;
  }

  .contact-card h2 {
      font-size: 22px;
  }

  .redes a {
      display: block;
      width: 100%;
      margin: 8px 0;
      padding: 12px;
      font-size: 15px;
  }

  footer {
      font-size: 13px;
      padding: 15px;
  }
}

</style>

</head>
<body>

<header>
    <iframe src="/public/audio_background.php"
        style="display:none;"
        allow="autoplay">
    </iframe>

    <h1>Loryon 💜</h1>
    <nav>
        <a href="catalogo_cliente.php">🛍️ Catálogo</a>
        <a href="carrito_cliente.php">🛒 Carrito</a>
        <a href="login.php">👤 Iniciar sesión</a>
    </nav>
</header>

<main>

    <!-- LOGO + QUIÉNES SOMOS -->
    <div class="top-section">

        <div class="logo-container">
            <img src="img/logo.png" loading="lazy" alt="Logo Loryon">
        </div>

         <!-- CAJÓN DE INFORMACIÓN -->
        <div class="info-box">
            <h2>Quiénes somos</h2>
            <p>
                En <strong>Mayoristas Loryon</strong>, nos dedicamos a ofrecer una amplia variedad de productos a precios accesibles.
                Nacimos con el propósito de facilitar las compras tanto a emprendedores como al público en general, brindando calidad y confianza.
            </p>

            <h2><span>🎯</span> Misión</h2>
            <p>
                Nuestra misión es acercar productos de calidad a precios justos, ayudando a emprendedores y familias a crecer. Ofrecemos atención personalizada y un servicio confiable.
            </p>

            <h2><span>🌟</span> Visión</h2>
            <p>
                Ser reconocidos como una empresa líder en ventas mayoristas y minoristas, destacándonos por variedad, precios accesibles y compromiso con nuestros clientes.
            </p>
        </div>

    </div>

    <!-- EQUIPO -->
    <div class="team-section">
        <h2>Nuestro equipo</h2>

        <div class="team-cards">

            <div class="team-card">
                <img src="img/persona1_caricatura.png" loading="lazy" alt="Caricatura Lorena">
                <h3>Lorena Reyes</h3>
                <p><em>Diseñadora & Fundadora</em></p>
                <p>Lema: “La belleza está en los pequeños detalles”.</p>
            </div>

            <div class="team-card">
                <img src="img/persona2_caricatura.png" loading="lazy" alt="Caricatura Jhon">
                <h3>Jhon Figueredo</h3>
                <p><em>Gestor & Co-Fundador</em></p>
                <p>Lema: “Con amor, disciplina y fe, todo es posible”.</p>
            </div>

        </div>
    </div>

    <!-- CONTACTO -->
    <div class="contacto">
        <div class="contact-card">
            <h2>Contacto</h2>
            <p>📍 Cúcuta, Norte de Santander</p>
            <p>📞 +57 322 2460867</p>
            <p>📞 +57 314 4231126</p>

            <div class="redes">
                <a href="https://www.tiktok.com/@mayoristas_loryon?_t=ZS-8zqYNL9ammW&_r=1" target="_blank">TikTok</a>
                <a href="https://www.instagram.com/mayoristas_loryon?igsh=MWJ1cmg5NG1vbW14cQ%3D%3D&utm_source=qr" target="_blank">Instagram</a>
                <a href="https://wa.me/573222460867" target="_blank">WhatsApp</a>
            </div>
        </div>
    </div>

</main>

<footer>
    © <?= date('Y') ?> Loryon 💜 - Hecho con amor
</footer>

</body>
</html>

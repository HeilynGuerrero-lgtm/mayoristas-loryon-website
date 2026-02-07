<!doctype html>
<html>
<head>
<meta charset="utf-8">
</head>
<body style="margin:0; padding:0; background:transparent;">

<audio id="musicaFondo" autoplay loop>
    <source src="/public/assets/audio/whispering_dreams.mp3" type="audio/mpeg">
</audio>

<script>
document.addEventListener("DOMContentLoaded", () => {
    const audio = document.getElementById("musicaFondo");
    audio.volume = 0.15;

    // Recuperar tiempo guardado
    const tiempoGuardado = localStorage.getItem("musica_tiempo");
    if (tiempoGuardado) {
        audio.currentTime = parseFloat(tiempoGuardado);
    }

    // Guardar el tiempo cada 500 ms
    setInterval(() => {
        localStorage.setItem("musica_tiempo", audio.currentTime.toString());
    }, 500);

    // Requerir interacción del usuario para autoplay
    document.body.addEventListener("click", () => {
        audio.play().catch(() => {});
    }, { once: true });
});
</script>

</body>
</html>

<audio id="musicaFondo" loop>
    <source src="/loryon/public/assets/audio/whispering_dreams.mp3" type="audio/mpeg">
</audio>

<script>
document.addEventListener("DOMContentLoaded", () => {
    const audio = document.getElementById("musicaFondo");
    audio.volume = 0.15;

    // Los navegadores bloquean autoplay sin interacción
    document.body.addEventListener("click", () => {
        audio.play().catch(()=>{});
    }, { once: true });
});
</script>

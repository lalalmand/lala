document.addEventListener('DOMContentLoaded', () => {
    // ... (Votre code existant pour le menu burger et les étoiles) ...

    // --- Gestion de la Fusée 'Retour en Haut' ---
    const rocketToTop = document.getElementById('rocket-to-top');

    // Détermine quand afficher la fusée
    window.addEventListener('scroll', () => {
        // Affiche la fusée si l'utilisateur a scrollé de plus de 300 pixels
        if (window.scrollY > 300) {
            rocketToTop.classList.add('show');
        } else {
            rocketToTop.classList.remove('show');
        }
    });

    // Remonter en haut de page au clic
    rocketToTop.addEventListener('click', (e) => {
        e.preventDefault(); // Empêche le comportement de lien par défaut

        // Animation de scroll vers le haut
        window.scrollTo({
            top: 0,
            behavior: 'smooth' // Pour un défilement fluide
        });
    });
});
document.addEventListener('DOMContentLoaded', () => {
    const burgerButton = document.querySelector('.burger-menu');
    const overlayMenu = document.querySelector('.overlay-menu');
    const starsContainer = document.getElementById('stars-containerMenu');
    // On suppose que styleSheet[0] est votre fichier CSS principal
    const styleSheet = document.styleSheets[0]; 

    // 1. Gère l'ouverture et la fermeture du menu
    burgerButton.addEventListener('click', () => {
        // Ajoute/retire la classe 'active' pour l'animation en croix (X)
        burgerButton.classList.toggle('active'); 
        // Ajoute/retire la classe 'open' pour afficher/masquer le menu
        overlayMenu.classList.toggle('open'); 
    });


    // 2. Fonction pour créer les étoiles et leur animation
    function createStars(count) {
        for (let i = 0; i < count; i++) {
            const star = document.createElement('div');
            star.classList.add('starMenu');
            
            // Position aléatoire de départ (0 à 100%)
            const startX = Math.random() * 100;
            const startY = Math.random() * 100;
            
            star.style.left = `${startX}%`;
            star.style.top = `${startY}%`;

            // Taille et opacité aléatoires
            const size = Math.random() * 2 + 1; 
            star.style.width = `${size}px`;
            star.style.height = `${size}px`;
            
            // Délai d'animation aléatoire
            star.style.animationDelay = `${Math.random() * 3}s`;

            /* --- Ajout de l'animation de Mouvement --- */
            const duration = Math.random() * 50 + 80; // Durée d'animation lente
            star.style.animationDuration = `${duration}s, 3s`; // moveStars, twinkle

            // Définit la distance de déplacement finale (aléatoire)
            const endTranslateX = Math.random() * 100 - 50; 
            const endTranslateY = Math.random() * 100 - 50; 

            // Injecte la règle @keyframes moveStars spécifique pour cette étoile
            const keyframesRule = `@keyframes moveStars-${i} {
                0% { transform: translate(0, 0); }
                100% { transform: translate(${endTranslateX}px, ${endTranslateY}px); }
            }`;
            styleSheet.insertRule(keyframesRule, styleSheet.cssRules.length);

            // Applique l'animation personnalisée à l'étoile
            star.style.animationName = `moveStars-${i}, twinkle`;
            star.style.animationIterationCount = 'infinite, infinite'; 
            star.style.animationTimingFunction = 'linear, alternate'; 

            starsContainer.appendChild(star);
        }
    }

    // Gérer le nombre d'étoiles 
    const numberOfStars = 100; 
    createStars(numberOfStars);
});
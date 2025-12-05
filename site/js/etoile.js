document.addEventListener('DOMContentLoaded', () => {
    // Créer une feuille de style pour insérer les animations dynamiques
    const styleSheet = document.styleSheets[0]; // Utiliser la première feuille de style existante

    // Créer un conteneur pour les étoiles (si ce n'est pas déjà fait)
    const starsContainer = document.getElementById('stars-container'); // Assurez-vous que l'ID est correct
    if (!starsContainer) {
        // Si le conteneur n'existe pas, on le crée
        const newContainer = document.createElement('div');
        newContainer.id = 'stars-container';
        document.body.appendChild(newContainer);
    }

    // Fonction pour créer les étoiles
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
            const size = Math.random() * 2 + 1; // Taille entre 1px et 3px
            star.style.width = `${size}px`;
            star.style.height = `${size}px`;

            // Délai d'animation aléatoire
            star.style.animationDelay = `${Math.random() * 3}s`;

            /* --- Animation de Mouvement --- */
            const duration = Math.random() * 50 + 80; // Durée d'animation lente
            star.style.animationDuration = `${duration}s, 3s`; // moveStars, twinkle

            // Définir la distance de déplacement finale (aléatoire)
            const endTranslateX = Math.random() * 100 - 50; // De -50% à 50% pour le déplacement
            const endTranslateY = Math.random() * 100 - 50; // De -50% à 50% pour le déplacement

            // Créer la règle @keyframes dynamique pour chaque étoile
            const keyframesRule = `@keyframes moveStars-${i} {
                0% { transform: translate(0, 0); }
                100% { transform: translate(${endTranslateX}px, ${endTranslateY}px); }
            }`;

            // Ajouter la règle dans la feuille de style
            styleSheet.insertRule(keyframesRule, styleSheet.cssRules.length);

            // Appliquer l'animation personnalisée à l'étoile
            star.style.animationName = `moveStars-${i}, twinkle`;
            star.style.animationIterationCount = 'infinite, infinite'; 
            star.style.animationTimingFunction = 'linear, alternate'; 

            // Ajouter l'étoile au conteneur
            starsContainer.appendChild(star);
        }
    }

    // Gérer le nombre d'étoiles
    const numberOfStars = 100; 
    createStars(numberOfStars);
});

<?php
    // --- PARTIE PHP : Génération de la couleur cible ---

    // Fonction pour générer une couleur RVB aléatoire
    function generateRandomColor() {
        $r = rand(0, 255);
        $g = rand(0, 255);
        $b = rand(0, 255);
        // Stocker la couleur cible en format hexadécimal et RVB pour JavaScript
        return [
            'hex' => sprintf("#%02x%02x%02x", $r, $g, $b),
            'r' => $r,
            'g' => $g,
            'b' => $b
        ];
    }

    $targetColor = generateRandomColor();
    $targetColorHex = $targetColor['hex'];
    $targetR = $targetColor['r'];
    $targetG = $targetColor['g'];
    $targetB = $targetColor['b'];

    // On utilise les variables PHP pour initialiser les variables JS
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jeu de Devine-Couleur</title>
    <!-- Chargement de Tailwind CSS pour un style moderne et responsif -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Configuration de la police Inter */
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap');
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f7f7f7;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }

        /* Style pour le cercle chromatique immersif */
        .color-wheel {
            width: 300px;
            height: 300px;
            border-radius: 50%;
            background: conic-gradient(
                #f00, #ff0, #0f0, #0ff, #00f, #f0f, #f00
            );
            position: relative;
            cursor: crosshair;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
            margin-bottom: 20px;
        }

        /* Sélecteur de saturation/luminosité à l'intérieur du cercle (White/Black overlay) */
        .color-picker-overlay {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            /* Gradient radial pour la saturation (White to transparent) */
            background: radial-gradient(circle at center, rgba(255, 255, 255, 0) 0%, rgba(255, 255, 255, 1) 100%);
            position: absolute;
            top: 0;
            left: 0;
            opacity: 1; /* Initial opacity, managed by JS for luminosity */
        }

        .black-overlay {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            /* Gradient linéaire pour la luminosité (Black to transparent) */
            background: linear-gradient(to top, rgba(0, 0, 0, 1) 0%, rgba(0, 0, 0, 0) 50%, rgba(0, 0, 0, 0.5) 100%);
            position: absolute;
            top: 0;
            left: 0;
            mix-blend-mode: multiply; /* Fusionne avec le gradient précédent */
            opacity: 0.7;
        }

        /* Curseur (petite pastille) */
        #selector {
            width: 16px;
            height: 16px;
            border-radius: 50%;
            background-color: white;
            border: 2px solid #333;
            position: absolute;
            transform: translate(-50%, -50%);
            pointer-events: none; /* Ne pas intercepter les clics */
            transition: all 0.1s ease;
        }

        /* Style des boutons */
        .game-button {
            padding: 10px 24px;
            font-weight: 600;
            border-radius: 8px;
            transition: background-color 0.3s, transform 0.1s, box-shadow 0.3s;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .game-button:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 8px rgba(0, 0, 0, 0.15);
        }
        .game-button:active {
            transform: translateY(1px);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        /* Style pour l'affichage des couleurs */
        .color-box {
            width: 100px;
            height: 100px;
            border-radius: 12px;
            margin: 0 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            transition: background-color 0.5s;
        }
    </style>
</head>
<body class="bg-gray-50">

<div class="max-w-xl w-full bg-white p-8 md:p-10 rounded-xl shadow-2xl text-center">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Devinez la Couleur !</h1>
    <p class="text-gray-600 mb-8">L'ordinateur a choisi une couleur. Utilisez le cercle chromatique pour vous rapprocher le plus possible.</p>

    <!-- 1. Affichage de la couleur cible (initialement) -->
    <div id="target-color-display" class="w-24 h-24 mx-auto rounded-xl shadow-lg border-4 border-gray-300 transition-all duration-1000"
         style="background-color: <?php echo $targetColorHex; ?>;">
    </div>
    <p class="text-sm text-gray-500 mt-2 mb-8" id="target-prompt">Mémorisez cette couleur ! Elle disparaîtra lors de votre sélection.</p>


    <!-- 2. Zone de sélection de l'utilisateur -->
    <div class="flex flex-col items-center">
        <!-- Cercle chromatique immersif -->
        <div id="color-wheel-container" class="color-wheel relative mb-6" style="display: none;">
            <div id="color-picker-overlay" class="color-picker-overlay"></div>
            <div id="black-overlay" class="black-overlay"></div>
            <div id="selector"></div>
        </div>
        <p class="text-xl font-semibold text-gray-700 mb-4" id="current-user-color">Votre sélection: <span id="user-color-hex" class="font-mono text-indigo-600 text-lg">#000000</span></p>

        <!-- Curseur simple pour la luminosité/valeur -->
        <label for="luminosity-slider" class="text-sm font-medium text-gray-600 w-full max-w-xs block mb-2">Luminosité (L) / Saturation (S)</label>
        <input type="range" id="luminosity-slider" min="0" max="100" value="50" class="w-full max-w-xs appearance-none h-2 bg-gray-200 rounded-lg cursor-pointer" style="background: linear-gradient(to right, #000, #888, #fff);">
    </div>


    <!-- 3. Bouton de validation -->
    <button id="validate-button" class="game-button bg-indigo-500 text-white hover:bg-indigo-600 mt-8" onclick="startGame()">
        Commencer le jeu / Valider la couleur
    </button>


    <!-- 4. Zone de résultats (Initialement cachée) -->
    <div id="results-area" class="mt-10 p-6 bg-indigo-50 border-t-4 border-indigo-400 rounded-lg hidden">
        <h2 class="text-2xl font-bold text-indigo-700 mb-4">Résultat de la Manche</h2>

        <div class="flex justify-center items-center mb-6">
            <div class="color-box" id="result-target-color"></div>
            <div class="color-box" id="result-user-color"></div>
        </div>

        <p class="text-lg font-medium text-gray-700 mb-4">
            Couleur Cible (Gauche) vs Votre Couleur (Droite)
        </p>

        <p class="text-4xl font-extrabold text-indigo-800" id="score-text">
            Écart : 0.00%
        </p>
        <p class="text-sm text-gray-500 mt-2">Plus ce pourcentage est petit, plus vous êtes proche !</p>

        <button class="game-button bg-green-500 text-white hover:bg-green-600 mt-6" onclick="window.location.reload()">
            Rejouer
        </button>
    </div>

</div>

<script>
    // --- PARTIE JAVASCRIPT : Logique du jeu et de l'interface ---

    // Données de la couleur cible (provenant de PHP)
    const TARGET_R = <?php echo $targetR; ?>;
    const TARGET_G = <?php echo $targetG; ?>;
    const TARGET_B = <?php echo $targetB; ?>;
    const MAX_DISTANCE = Math.sqrt(255 * 255 * 3); // Distance maximale entre noir et blanc

    let gameStarted = false;
    let userColor = { r: 0, g: 0, b: 0, hex: '#000000' };
    let currentSelectorPosition = { x: 0, y: 0 }; // Stocke la position du curseur DANS la roue

    // Éléments DOM
    const targetDisplay = document.getElementById('target-color-display');
    const targetPrompt = document.getElementById('target-prompt');
    const validateButton = document.getElementById('validate-button');
    const colorWheelContainer = document.getElementById('color-wheel-container');
    const selector = document.getElementById('selector');
    const luminositySlider = document.getElementById('luminosity-slider');
    const userColorHexDisplay = document.getElementById('user-color-hex');
    const resultsArea = document.getElementById('results-area');

    // --- Fonction de conversion HSL (Teinte, Saturation, Luminosité) en RVB ---
    function hslToRgb(h, s, l) {
        let r, g, b;

        if (s === 0) {
            r = g = b = l; // achromatic
        } else {
            const hue2rgb = (p, q, t) => {
                if (t < 0) t += 1;
                if (t > 1) t -= 1;
                if (t < 1/6) return p + (q - p) * 6 * t;
                if (t < 1/2) return q;
                if (t < 2/3) return p + (q - p) * (2/3 - t) * 6;
                return p;
            };

            const q = l < 0.5 ? l * (1 + s) : l + s - l * s;
            const p = 2 * l - q;
            r = hue2rgb(p, q, h + 1/3);
            g = hue2rgb(p, q, h);
            b = hue2rgb(p, q, h - 1/3);
        }

        return {
            r: Math.round(r * 255),
            g: Math.round(g * 255),
            b: Math.round(b * 255)
        };
    }

    // --- Fonction pour convertir RVB en Hex ---
    const componentToHex = (c) => {
        const hex = c.toString(16);
        return hex.length === 1 ? "0" + hex : hex;
    };

    const rgbToHex = (r, g, b) => {
        return "#" + componentToHex(r) + componentToHex(g) + componentToHex(b);
    };


    // --- Mise à jour de la couleur de l'utilisateur (logique principale) ---
    function updateColorFromPosition(x, y) {
        if (!gameStarted) return;

        const maxRadius = colorWheelContainer.offsetWidth / 2;

        // Calculer l'angle (Teinte H)
        let angleRad = Math.atan2(y, x);
        let hue = angleRad * (180 / Math.PI);
        hue = (hue < 0 ? 360 + hue : hue) / 360; // Convertir en [0, 1]

        // Calculer la distance (Saturation S)
        let radius = Math.sqrt(x * x + y * y);
        let saturation = Math.min(1, radius / maxRadius);
        
        // Luminosité (L) : vient du slider, convertie de [0, 100] à [0, 1]
        const sliderValue = parseInt(luminositySlider.value);
        let luminosity = sliderValue / 100;

        // Conversion HSL en RVB
        const rgb = hslToRgb(hue, saturation, luminosity);
        userColor.r = rgb.r;
        userColor.g = rgb.g;
        userColor.b = rgb.b;

        userColor.hex = rgbToHex(userColor.r, userColor.g, userColor.b);

        // Mettre à jour l'affichage de l'hex et le curseur
        userColorHexDisplay.textContent = userColor.hex;
        selector.style.backgroundColor = userColor.hex;
        selector.style.borderColor = (userColor.r + userColor.g + userColor.b) / 3 > 127 ? '#000' : '#fff'; // Contour contrasté
    }

    // --- Fonction pour mettre à jour la position visuelle du curseur ---
    function updateSelectorPosition(x, y) {
        currentSelectorPosition = { x, y };

        // Limiter le curseur à l'intérieur du cercle
        const maxRadius = colorWheelContainer.offsetWidth / 2;
        let distance = Math.sqrt(x * x + y * y);

        if (distance > maxRadius) {
            // Recalculer le point sur le bord
            const angleRad = Math.atan2(y, x);
            x = Math.cos(angleRad) * maxRadius;
            y = Math.sin(angleRad) * maxRadius;
        }
        
        // Positionner le curseur (relatif au centre du conteneur)
        selector.style.transform = `translate(${x}px, ${y}px)`;
        updateColorFromPosition(x, y);
    }

    // --- Gestionnaire d'événements de la roue (pour mousedown/mousemove/touch) ---
    let isDragging = false;

    function handleInteraction(e) {
        // Assurez-vous d'utiliser e.touches[0] pour les événements tactiles
        const clientX = e.clientX || (e.touches && e.touches[0].clientX);
        const clientY = e.clientY || (e.touches && e.touches[0].clientY);

        if (!clientX || !clientY || !gameStarted) return;

        const rect = colorWheelContainer.getBoundingClientRect();
        const centerX = rect.left + rect.width / 2;
        const centerY = rect.top + rect.height / 2;

        // Coordonnées relatives au centre de la roue
        const x = clientX - centerX;
        const y = clientY - centerY;

        updateSelectorPosition(x, y);

        // Empêche le défilement lors d'un glisser-déposer sur mobile
        if (e.type.startsWith('touch')) e.preventDefault();
    }

    // Événements souris
    colorWheelContainer.addEventListener('mousedown', (e) => {
        isDragging = true;
        handleInteraction(e);
    });
    document.addEventListener('mousemove', (e) => {
        if (isDragging && gameStarted) {
            handleInteraction(e);
        }
    });
    document.addEventListener('mouseup', () => {
        isDragging = false;
    });

    // Événements tactiles
    colorWheelContainer.addEventListener('touchstart', (e) => {
        isDragging = true;
        handleInteraction(e);
    }, { passive: false });
    document.addEventListener('touchmove', (e) => {
        if (isDragging && gameStarted) {
            handleInteraction(e);
        }
    }, { passive: false });
    document.addEventListener('touchend', () => {
        isDragging = false;
    });

    // Gestionnaire pour le slider de luminosité
    luminositySlider.addEventListener('input', () => {
        if (!gameStarted) return;
        // Met à jour la couleur en utilisant la dernière position enregistrée
        updateColorFromPosition(currentSelectorPosition.x, currentSelectorPosition.y);
    });


    // --- Fonction principale de Démarrage/Validation ---
    function startGame() {
        if (!gameStarted) {
            // Étape 1: Démarrer le jeu (caché la couleur cible)
            targetDisplay.classList.remove('border-gray-300');
            targetDisplay.classList.add('border-indigo-400', 'bg-gray-200'); // Changement de couleur pour indiquer qu'elle est "cachée"
            targetDisplay.style.backgroundColor = '#ccc';
            targetPrompt.textContent = "La couleur cible a été mémorisée. Faites votre sélection ci-dessous.";

            colorWheelContainer.style.display = 'block';
            validateButton.textContent = "Valider ma sélection";

            // Initialiser la position du sélecteur au centre de la roue (x=0, y=0)
            updateSelectorPosition(0, 0); 
            isDragging = false; 

            gameStarted = true;
        } else {
            // Étape 2: Valider la couleur
            showResults();
        }
    }

    // --- Fonction de calcul et d'affichage des résultats ---
    function showResults() {
        // Désactiver le jeu
        gameStarted = false;
        validateButton.disabled = true;
        colorWheelContainer.style.pointerEvents = 'none';
        luminositySlider.disabled = true;
        validateButton.textContent = "Partie terminée";

        // 1. Calcul de l'écart RVB moyen (Distance euclidienne)
        const deltaR = userColor.r - TARGET_R;
        const deltaG = userColor.g - TARGET_G;
        const deltaB = userColor.b - TARGET_B;

        const distance = Math.sqrt(deltaR * deltaR + deltaG * deltaG + deltaB * deltaB);
        
        // Calcul du pourcentage d'écart : 0% = parfait, 100% = maximal
        const percentageError = (distance / MAX_DISTANCE) * 100;

        // 2. Affichage des résultats (comparaison visuelle)
        document.getElementById('result-target-color').style.backgroundColor = `<?php echo $targetColorHex; ?>`;
        document.getElementById('result-user-color').style.backgroundColor = userColor.hex;

        // Révéler la couleur cible dans la zone initiale et la cacher
        targetDisplay.style.backgroundColor = `<?php echo $targetColorHex; ?>`;
        targetDisplay.classList.remove('bg-gray-200', 'border-indigo-400');
        targetDisplay.classList.add('border-gray-500');
        targetPrompt.textContent = "La couleur cible initiale.";
        targetDisplay.style.display = 'none'; 

        // 3. Affichage du score
        document.getElementById('score-text').textContent = `Écart : ${percentageError.toFixed(2)}%`;
        resultsArea.classList.remove('hidden');

        // Mettre à jour le message
        if (percentageError < 1) {
            resultsArea.querySelector('h2').textContent = "INCROYABLE ! Vous êtes un maître de la couleur !";
        } else if (percentageError < 5) {
            resultsArea.querySelector('h2').textContent = "Excellent ! Très proche de la cible.";
        } else if (percentageError < 15) {
            resultsArea.querySelector('h2').textContent = "Bien joué ! Un bon œil pour la couleur.";
        } else {
            resultsArea.querySelector('h2').textContent = "Bonne tentative ! Entraînez votre œil.";
        }
    }

    // Initialisation du jeu
    window.onload = () => {
        // La couleur cible est affichée par PHP au chargement
        // Le bouton est prêt à démarrer la manche.
    };
</script>

</body>
</html>
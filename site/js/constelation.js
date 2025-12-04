// --- Configuration Firebase (Non utilisée) ---
const appId = typeof __app_id !== 'undefined' ? __app_id : 'default-app-id';
const firebaseConfig = typeof __firebase_config !== 'undefined' ? JSON.parse(__firebase_config) : {};
// ---------------------------------------------

const width = window.innerWidth;
const height = window.innerHeight;

// Définition des 12 constellations du zodiaque (coordonnées relatives à une boîte 200x200)
const baseCoordinates = [
  // BÉLIER (Aries)
  { name: "BÉLIER", points: [{x:100,y:40},{x:150,y:50},{x:140,y:80},{x:120,y:70}] },
  
  // TAUREAU (Taurus)
  { name: "TAUREAU", points: [{x:100,y:20},{x:150,y:30},{x:130,y:70},{x:80,y:80},{x:130,y:70},{x:180,y:100}] },
  
  // GÉMEAUX (Gemini) - Les jumeaux
  { name: "GÉMEAUX", points: [{x:80,y:20},{x:80,y:180},{x:120,y:180},{x:120,y:20}] },
  
  // CANCER (Cancer) - Le crabe
  { name: "CANCER", points: [{x:50,y:100},{x:80,y:120},{x:110,y:100},{x:140,y:120},{x:170,y:100}] },
  
  // LION (Leo)
  { name: "LION", points: [{x:100,y:20},{x:120,y:40},{x:150,y:40},{x:170,y:60},{x:150,y:80},{x:120,y:150},{x:100,y:180}] },
  
  // VIERGE (Virgo)
  { name: "VIERGE", points: [{x:100,y:20},{x:100,y:60},{x:140,y:80},{x:80,y:120},{x:120,y:160}] },
  
  // BALANCE (Libra)
  { name: "BALANCE", points: [{x:50,y:50},{x:150,y:50},{x:100,y:100},{x:100,y:150}] },
  
  // SCORPION (Scorpius)
  { name: "SCORPION", points: [{x:100,y:20},{x:120,y:40},{x:150,y:40},{x:100,y:100},{x:150,y:120},{x:100,y:150},{x:100,y:180}] },
  
  // SAGITTAIRE (Sagittarius)
  { name: "SAGITTAIRE", points: [{x:50,y:50},{x:150,y:150},{x:150,y:100},{x:180,y:100}] },
  
  // CAPRICORNE (Capricorn)
  { name: "CAPRICORNE", points: [{x:50,y:150},{x:100,y:100},{x:150,y:150},{x:100,y:50}] },
  
  // VERSEAU (Aquarius)
  { name: "VERSEAU", points: [{x:50,y:50},{x:150,y:50},{x:50,y:150},{x:150,y:150}] },
  
  // POISSONS (Pisces)
  { name: "POISSONS", points: [{x:50,y:50},{x:100,y:100},{x:150,y:50},{x:100,y:150},{x:50,y:150}] },
];

/**
 * Ajuste les coordonnées pour placer la constellation n'importe où sur l'écran
 * en évitant la zone centrale du rapport et en les poussant vers les bords.
 */
function adjustCoordinates(constellation) {
    const boxSize = 200; // Taille maximale de la constellation
    const buffer = 100; // Distance minimale entre la constellation et le rapport
    
    // Définition de la zone centrale du rapport (max-width: 900px)
    const containerWidth = 900;
    const reportLeft = (window.innerWidth / 2) - (containerWidth / 2);
    const reportRight = (window.innerWidth / 2) + (containerWidth / 2);
    
    // Position Y (verticale) peut être n'importe où, tant qu'elle reste dans l'écran
    const maxAnchorY = window.innerHeight - boxSize;

    let anchorX;
    
    // On choisit aléatoirement Côté Gauche (Left) ou Côté Droit (Right)
    const placement = Math.random(); 

    if (placement < 0.5) { // Placement à GAUCHE (priorité aux bords)
        // L'ancre X doit se terminer à (reportLeft - boxSize - buffer) au maximum
        const maxAnchorX = Math.max(0, reportLeft - boxSize - buffer); 
        anchorX = Math.random() * maxAnchorX;
    } else { // Placement à DROITE (priorité aux bords)
        // L'ancre X doit commencer à (reportRight + buffer) au minimum
        const minAnchorX = reportRight + buffer;
        const maxAnchorX = window.innerWidth - boxSize;

        // Assurer que minAnchorX ne dépasse pas maxAnchorX (cas de petits écrans)
        if (minAnchorX > maxAnchorX) {
            anchorX = maxAnchorX; // Utilise le bord le plus à droite possible
        } else {
            anchorX = minAnchorX + (Math.random() * (maxAnchorX - minAnchorX));
        }
    }
    
    // La position Y est complètement aléatoire sur toute la hauteur de l'écran
    anchorY = Math.random() * maxAnchorY;

    // Assurer que les coordonnées ne sont jamais négatives ou hors de l'écran (pour la sécurité)
    anchorX = Math.max(0, Math.min(anchorX, window.innerWidth - boxSize));
    anchorY = Math.max(0, Math.min(anchorY, window.innerHeight - boxSize));

    return {
        name: constellation.name,
        points: constellation.points.map(p => ({
            x: p.x + anchorX,
            y: p.y + anchorY,
        }))
    };
}


// Fonction pour dessiner une constellation (lignes et nom)
function drawConstellation(constellation) {
  const svg = document.getElementById('constellationLayer');
  
  // 1. Créer l'élément de groupe <g> pour la constellation
  const group = document.createElementNS("http://www.w3.org/2000/svg", "g");
  group.classList.add('constellation-group');
  
  // 2. Créer la polyline (les lignes)
  const path = document.createElementNS("http://www.w3.org/2000/svg","polyline");
  path.setAttribute("points", constellation.points.map(p => p.x + ',' + p.y).join(' '));
  path.classList.add('constellation-path');
  group.appendChild(path);
  
  // 3. Créer l'élément de texte (le nom du signe, simple texte SVG)
  const text = document.createElementNS("http://www.w3.org/2000/svg", "text");
  // Utiliser les coordonnées du premier point pour le placement du nom (décalé)
  const firstPoint = constellation.points[0];
  text.setAttribute('x', firstPoint.x + 10);
  text.setAttribute('y', firstPoint.y - 10);
  text.textContent = constellation.name;
  text.classList.add('constellation-name');
  group.appendChild(text);

  svg.appendChild(group);

  // Appliquer uniquement l'animation d'apparition/disparition (10s)
  group.style.animation = 'appear 10s forwards'; 

  // Retirer l'élément après la fin de l'animation (10 secondes)
  setTimeout(() => {
    if (svg.contains(group)) {
      svg.removeChild(group);
    }
  }, 10000);
}

// Tirage aléatoire sans répétition immédiate
let remaining = [...baseCoordinates];

function showRandomConstellation() {
  // Si toutes les constellations ont été montrées, on réinitialise la liste
  if(remaining.length === 0) remaining = [...baseCoordinates];
  
  const index = Math.floor(Math.random() * remaining.length);
  const selectedConstellation = remaining.splice(index, 1)[0];
  
  // Ajuster les coordonnées pour qu'elles apparaissent de manière aléatoire sur l'écran
  const adjustedConstellation = adjustCoordinates(selectedConstellation);

  drawConstellation(adjustedConstellation);
}

// Apparition d'une constellation toutes les 11 secondes (10s animation + 1s d'intervalle).
setInterval(showRandomConstellation, 11000);

// Afficher la première constellation au chargement
showRandomConstellation();
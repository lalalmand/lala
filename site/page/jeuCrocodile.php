<?php
session_start();

// Réinitialiser la partie si on clique sur "Rejouer"
if (isset($_POST['replay'])) {
    unset($_SESSION['danger']);
    unset($_SESSION['clicked']);
    header("Location: ".$_SERVER['PHP_SELF']);
    exit;
}

// Choisir dent dangereuse si non définie
if (!isset($_SESSION['danger'])) {
    $_SESSION['danger'] = rand(1, 12);
}

// Tableau dents cliquées
if (!isset($_SESSION['clicked'])) {
    $_SESSION['clicked'] = [];
}

$close = false;

// Quand on clique sur une dent
if (isset($_POST['dent'])) {
    $choice = intval($_POST['dent']);
    if (!in_array($choice, $_SESSION['clicked'])) {
        $_SESSION['clicked'][] = $choice;
    }
    if ($choice === $_SESSION['danger']) {
        $close = true;
        // Ne pas unset ici pour garder les dents visibles
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Crocodile Jeu</title>
<style>
/* === Styles des dents === */
.tooth { cursor: pointer; transition: transform 0.2s; }
.tooth.disabled { filter: brightness(60%); cursor: not-allowed; width:16px; height:16px; transform-origin:50% 100%; }
.mouth { transition: transform 0.4s ease-in-out; }
<?php if ($close): ?>.mouth { transform:translateY(-120px); }<?php endif; ?>

/* === Styles général crocodile === */
.crocodile-toy { width: 350px; height: 400px; margin: 50px auto; position: relative; filter: drop-shadow(0 15px 10px rgba(0,0,0,0.5)); }
.head { width: 100%; height: 180px; background-color: #4CAF50; border-radius: 180px 180px 40px 40px; position: absolute; top:0; z-index:5; box-shadow: inset 0 -10px 15px rgba(0,0,0,0.3), 0 10px 0 #388E3C, inset 0 20px 30px rgba(255,255,255,0.2); }
.mouth { width: 80%; height: 250px; background-color: #4CAF50; border-radius: 0 0 150px 150px; position: absolute; top:150px; left:10%; z-index:2; overflow:hidden; box-shadow: inset 0 10px 15px rgba(0,0,0,0.3); }
.inner-mouth { width: 95%; height: 95%; background-color: #C62828; border-radius: 0 0 130px 130px; position: absolute; bottom:0; left:2.5%; border:5px solid #8e0000; }

.eye-container { position: absolute; top:20px; width:80px; height:80px; z-index:6; box-shadow:0 0 0 10px #388E3C; border-radius:50%; overflow:hidden; }
.eye-container.left { left:40px; } .eye-container.right { right:40px; }
.sclera { background:white; width:100%; height:100%; border-radius:50%; border:5px solid #000; }
.iris { width:40%; height:40%; background:#01579B; border-radius:50%; position:absolute; top:30%; left:30%; }
.eyelid { position:absolute; top:-10px; width:100%; height:60%; background:#4CAF50; border-radius:90% 70% 0 0; transform:rotate(10deg); }
.eye-container.right .eyelid { transform:rotate(-10deg); }

.nostril { width:10px; height:8px; background:#388E3C; border-radius:50%; position:absolute; top:15px; }
.nostril.left { left:55px; } .nostril.right { right:55px; }

.teeth-container { position:absolute; width:300px; height:300px; bottom:-150px; left:0; right:0; margin:auto; z-index:10; }
.tooth { width:18px; height:18px; background:white; border-radius:5px 5px 0 0; position:absolute; box-shadow:0 2px 5px rgba(0,0,0,0.3); transform-origin:50% 100%; top:0; left:141px; }

/* Placement dents */
<?php
$angles=[-90,-74,-58,-42,-26,-10,10,26,42,58,74,90];
for ($i=1;$i<=12;$i++): ?>
.tooth-<?= $i ?> { transform: rotate(<?= $angles[$i-1] ?>deg) translateY(120px); }
<?php endfor; ?>

/* Bouton Rejouer */
.replay-btn { padding:10px 20px; font-size:20px; margin-top:20px; cursor:pointer; }

/* Screamer overlay */
.screamer-overlay {
    display:none;
    position:fixed;
    top:0; left:0; right:0; bottom:0;
    background:rgba(0,0,0,0.9);
    z-index:100;
    overflow:hidden;
    animation: shake 0.5s infinite;
}

/* Image plein écran */
#screamer-img {
    width:100vw;
    height:100vh;
    object-fit:cover;
    position:absolute;
    top:0; left:0;
    z-index:101;
}

@keyframes shake {
    0%{transform:translate(0,0) rotate(0deg);}
    20%{transform:translate(-10px,5px) rotate(-5deg);}
    40%{transform:translate(10px,-5px) rotate(5deg);}
    60%{transform:translate(-10px,5px) rotate(-5deg);}
    80%{transform:translate(10px,-5px) rotate(5deg);}
    100%{transform:translate(0,0) rotate(0deg);}
}
</style>
</head>
<body>
<a href="../page/projetPerso.html" >
<div style="text-align:center; margin-top:20px;">
    <button type="submit" name="replay" class="replay-btn">Retour au projet</button></a>
</div>

<form method="post">
   
<div class="crocodile-toy">
    <div class="head">
        <div class="nostril left"></div>
        <div class="nostril right"></div>

        <div class="eye-container left">
            <div class="sclera"><div class="iris"></div><div class="eyelid"></div></div>
        </div>

        <div class="eye-container right">
            <div class="sclera"><div class="iris"></div><div class="eyelid"></div></div>
        </div>
    </div>

    <div class="mouth">
        <div class="inner-mouth"></div>
        <div class="teeth-container">
        <?php for ($i=1;$i<=12;$i++):
            $clicked = in_array($i, $_SESSION['clicked']);
        ?>
            <button name="dent" value="<?= $i ?>" class="tooth tooth-<?= $i ?> <?= $clicked ? 'disabled':'' ?>" <?= $clicked ? 'disabled':'' ?>></button>
        <?php endfor; ?>
        </div>
    </div>
</div>

<!-- Bouton Rejouer toujours visible -->
 <div style="text-align:center;"> 
    <button type="submit" name="replay" class="replay-btn">Rejouer</button>
</div>
</form>

<!-- Screamer Overlay -->
<?php if ($close): ?>
<div class="screamer-overlay" id="screamer">
    <img src="../image/screamer.jpg" alt="CROCO T'A MORDU!" id="screamer-img">
   
    <audio autoplay>
        <source src="scream.mp3" type="audio/mpeg">
    </audio>
</div>
<script>
const screamer = document.getElementById('screamer');
if (screamer) {
    screamer.style.display = 'block';
    const audio = screamer.querySelector('audio');
    setTimeout(() => {
        screamer.style.display = 'none';
        if (audio) {
            audio.pause();
            audio.currentTime = 0;
        }
    }, 1500); // Arrêt automatique après 5 secondes
}
</script>
<?php endif; ?>

</body>
</html>

<?php
// Chargement des outils nécessaires
require 'vendor/autoload.php';

use GuzzleHttp\Client;

// Fonction pour récupérer les personnages depuis l'API
function getCharacters() {
    try {
        // Création d'un client pour communiquer avec l'API
        $client = new Client([
            'timeout' => 10.0,
            'headers' => [
                'Accept' => 'application/json',
                'User-Agent' => 'DragonBallApp/1.0'
            ]
        ]);
        
        // Récupération des données
        $response = $client->get("https://dragonball-api.com/api/characters?limit=50");
        $allCharacters = json_decode($response->getBody()->getContents(), true);
        
        // Préparation des personnages à afficher
        $characters = $allCharacters['items'] ?? $allCharacters;
        
        // Mélange aléatoire et sélection de 12 personnages
        shuffle($characters);
        $characters = array_slice($characters, 0, 12);
        
        return $characters;
    } 
    catch (Exception $e) {
        // Enregistrez l'erreur pour le débogage
        error_log('Dragon Ball API Error: ' . $e->getMessage());
        // Vous pourriez également afficher l'erreur pour le débogage
        // echo 'Erreur: ' . $e->getMessage();
        return [];
    }
}

// Couleurs pour les différentes races
$raceColors = [
    'Saiyan' => '#FFD700',
    'Human' => '#3CB371',
    'Namekian' => '#32CD32',
    'Android' => '#B0C4DE',
    'Majin' => '#FF69B4',
    'Frieza Race' => '#9370DB',
    'Fusion' => '#FF4500',
    'Angel' => '#ADD8E6',
    'God' => '#DC143C',
    'Demon' => '#8B0000',
    'Dragon' => '#228B22',
    'Animal' => '#D2B48C',
    'Tuffle' => '#9932CC',
    'Unknown' => '#808080'
];

// Récupération des personnages
$characters = getCharacters();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dragon Ball Z - Personnages</title>
    <style>
        body {
            background: linear-gradient(135deg, #663399, #9932CC);
            background-attachment: fixed;
            padding: 20px;
            color: #fff;
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .logo /*logo permetant de retourner sur la page d'accueil */{
            width : 100px;
            height : 100px;
            width: 100px;
            display : block; 
            background : url("logo.png")no-repeat;
        }
        header {
            text-align: center;
            margin-bottom: 40px;
            padding: 20px;
            background-color: rgba(75, 0, 130, 0.7);
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        h1 {
            font-size: 2.5rem;
            margin-bottom: 10px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
        }

        .refresh-btn {
            display: inline-block;
            margin-top: 15px;
            padding: 10px 20px;
            background-color: #9370DB;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            text-decoration: none;
        }

        .character-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 25px;
            margin-bottom: 30px;
        }

        .character-card {
            background-color: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            color: #333;
        }

        .character-image {
            background: linear-gradient(135deg, #9370DB, #8A2BE2);
            padding: 20px;
            text-align: center;
            height: 220px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .character-image img {
            max-width: 180px;
            max-height: 180px;
        }

        .character-info {
            padding: 15px;
        }

        .character-id {
            font-size: 0.9rem;
            color: #666;
        }

        .character-name {
            font-size: 1.5rem;
            font-weight: bold;
            margin: 5px 0 10px;
            color: #4B0082;
        }

        .race-badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            color: white;
            margin-bottom: 10px;
            font-weight: bold;
        }

        .character-stats {
            display: flex;
            justify-content: space-between;
            border-top: 1px solid #ddd;
            padding-top: 10px;
            margin-top: 10px;
            font-size: 0.9rem;
        }

        .character-description {
            font-size: 0.85rem;
            color: #444;
            margin-bottom: 10px;
            line-height: 1.4;
            max-height: 60px;
            overflow: hidden;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            padding: 20px;
            background-color: rgba(75, 0, 130, 0.7);
            border-radius: 10px;
        }
        
        .footer a {
            color: #D8BFD8;
            text-decoration: none;
            font-weight: bold;
        }
        
        .error-message {
            background-color: rgba(75, 0, 130, 0.8);
            color: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <a class="logo" href="index.html"></a>
            <h1>Dragon Ball Z - Personnages</h1>
            <p>Découvrez des personnages aléatoires de l'univers Dragon Ball</p>
            <a href="?" class="refresh-btn">Charger de nouveaux personnages</a>
        </header>

        <div class="character-grid">
            <?php if (empty($characters)): ?>
                <!-- Message si pas de personnages -->
                <div class="error-message">
                    <h2>Impossible de charger les personnages</h2>
                    <p>L'API Dragon Ball semble être indisponible pour le moment.</p>
                </div>
            <?php else: ?>
                <!-- Affichage des personnages -->
                <?php foreach ($characters as $character): ?>
                    <div class="character-card">
                        <!-- Image du personnage -->
                        <div class="character-image">
                            <?php if (!empty($character['image'])): ?>
                                <img src="<?php echo htmlspecialchars($character['image']); ?>" 
                                     alt="<?php echo htmlspecialchars($character['name']); ?>">
                            <?php else: ?>
                                <div style="width:150px;height:150px;background:#9370DB;color:white;display:flex;justify-content:center;align-items:center;border-radius:50%;">
                                    <?php echo htmlspecialchars($character['name']); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Informations sur le personnage -->
                        <div class="character-info">
                            <!-- ID du personnage -->
                            <?php if (!empty($character['id'])): ?>
                                <div class="character-id">#<?php echo str_pad($character['id'], 3, '0', STR_PAD_LEFT); ?></div>
                            <?php endif; ?>
                            
                            <!-- Nom du personnage -->
                            <h2 class="character-name"><?php echo htmlspecialchars($character['name']); ?></h2>
                            
                            <!-- Race du personnage -->
                            <?php if (!empty($character['race'])): ?>
                                <span class="race-badge" style="background-color: <?php echo $raceColors[$character['race']] ?? '#8A2BE2'; ?>">
                                    <?php echo htmlspecialchars($character['race']); ?>
                                </span>
                            <?php endif; ?>
                            
                            <!-- Description du personnage -->
                            <?php if (!empty($character['description'])): ?>
                                <div class="character-description">
                                    <?php echo htmlspecialchars($character['description']); ?>
                                </div>
                            <?php endif; ?>
                            
                            <!-- Statistiques du personnage -->
                            <?php if (!empty($character['ki']) || !empty($character['maxKi'])): ?>
                                <div class="character-stats">
                                    <?php if (!empty($character['ki'])): ?>
                                        <span>Ki: <?php echo htmlspecialchars($character['ki']); ?></span>
                                    <?php endif; ?>
                                    
                                    <?php if (!empty($character['maxKi'])): ?>
                                        <span>Max Ki: <?php echo htmlspecialchars($character['maxKi']); ?></span>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <div class="footer">
            <p>Données fournies par <a href="https://dragonball-api.com" target="_blank">Dragon Ball API</a></p>
        </div>
    </div>
</body>
</html>
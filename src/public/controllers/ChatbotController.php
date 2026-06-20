<?php
// Exemple de fonction pour récupérer les infos de votre base de données
// Adaptez cette fonction avec vos vraies requêtes SQL (ex: SELECT * FROM colis WHERE numero = ...)
function verifierStatutColis($numero_suivi) {
    $colis_factices = [
        "FR12345" => "En cours de livraison. Arrivée prévue demain.",
        "FR98765" => "Livré hier dans la boîte aux lettres.",
        "FR00000" => "Colis préparé par l'expéditeur, en attente de prise en charge."
    ];

    if (array_key_exists($numero_suivi, $colis_factices)) {
        return ["status" => "Succès", "info" => $colis_factices[$numero_suivi]];
    } else {
        return ["status" => "Erreur", "info" => "Numéro de colis inconnu."];
    }
}

// Lecture du message envoyé en JSON par le JavaScript
$input = json_decode(file_get_contents('php://input'), true);
$userMessage = $input['message'] ?? '';

if (empty($userMessage)) {
    echo json_encode(["reponse" => "Je n'ai pas compris votre message."]);
    exit;
}

// Récupération de la clé API (via $_ENV ou getenv selon votre configuration)
$apiKey = $_ENV['GEMINI_API_KEY'] ?? getenv('GEMINI_API_KEY');
$url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=" . $apiKey;

// Configuration des outils (Tools) pour Gemini
$tools = [[
    "functionDeclarations" => [[
        "name" => "verifierStatutColis",
        "description" => "Récupère le statut et l'avancement d'un colis à partir de son numéro de suivi.",
        "parameters" => [
            "type" => "OBJECT",
            "properties" => [
                "numero_suivi" => [
                    "type" => "STRING",
                    "description" => "Le numéro de suivi du colis (ex: FR12345)."
                ]
            ],
            "required" => ["numero_suivi"]
        ]
    ]]
]];

$data = [
    "contents" => [["role" => "user", "parts" => [["text" => $userMessage]]]],
    "tools" => $tools,
    "systemInstruction" => ["parts" => [["text" => "Tu es l'assistant virtuel d'un site de livraison. Sois poli, concis et aide les utilisateurs à suivre leurs colis."]]]
];

// Premier appel à Gemini
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
$response = json_decode(curl_exec($ch), true);
curl_close($ch);

// Si Gemini demande d'appeler la fonction de suivi de colis
if (isset($response['candidates'][0]['content']['parts'][0]['functionCall'])) {
    $functionCall = $response['candidates'][0]['content']['parts'][0]['functionCall'];
    $arguments = $functionCall['args'];

    if ($functionCall['name'] === "verifierStatutColis" && isset($arguments['numero_suivi'])) {
        $resultatColis = verifierStatutColis($arguments['numero_suivi']);

        // On renvoie le résultat à Gemini
        $data['contents'][] = $response['candidates'][0]['content'];
        $data['contents'][] = [
            "role" => "tool",
            "parts" => ["functionResponse" => ["name" => "verifierStatutColis", "response" => ["output" => $resultatColis]]]
        ];

        // Deuxième appel pour obtenir le texte final
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        $response = json_decode(curl_exec($ch), true);
        curl_close($ch);
    }
}

$texteReponse = $response['candidates'][0]['content']['parts'][0]['text'] ?? "Désolé, je rencontre un problème technique.";
header('Content-Type: application/json');
echo json_encode(["reponse" => $texteReponse]);
exit;
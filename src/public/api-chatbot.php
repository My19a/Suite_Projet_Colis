<?php
// 1. On charge l'environnement pour récupérer la clé API
require_once __DIR__ . '/../vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createUnsafeImmutable(__DIR__ . '/..');
$dotenv->safeLoad();

header('Content-Type: application/json');

$apiKey = getenv('GEMINI_API_KEY');
if (!$apiKey) {
    echo json_encode(["reponse" => "Erreur : Clé API Gemini manquante dans le .env."]);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$userMessage = $input['message'] ?? '';

if (empty($userMessage)) {
    echo json_encode(["reponse" => "Je n'ai pas compris votre message."]);
    exit;
}

$url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=" . $apiKey;

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
    "systemInstruction" => [
        "parts" => [["text" => "Tu es l'assistant virtuel d'un site de livraison. Sois poli, concis et aide les utilisateurs à suivre leurs colis."]]
    ]
];

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
$response = json_decode(curl_exec($ch), true);
curl_close($ch);

if (isset($response['candidates'][0]['content']['parts'][0]['functionCall'])) {
    $functionCall = $response['candidates'][0]['content']['parts'][0]['functionCall'];
    $arguments = $functionCall['args'];

    if ($functionCall['name'] === "verifierStatutColis" && isset($arguments['numero_suivi'])) {
        
        // Simulation de la base de données
        $colis_factices = [
            "FR12345" => "En cours de livraison. Arrivée prévue demain.",
            "FR98765" => "Livré hier dans la boîte aux lettres.",
            "FR00000" => "Colis préparé par l'expéditeur, en attente de prise en charge."
        ];

        $numero = $arguments['numero_suivi'];
        $resultatColis = array_key_exists($numero, $colis_factices) 
            ? ["status" => "Succès", "info" => $colis_factices[$numero]]
            : ["status" => "Erreur", "info" => "Numéro de colis inconnu."];

        $data['contents'][] = $response['candidates'][0]['content'];
        $data['contents'][] = [
            "role" => "tool",
            "parts" => [
                "functionResponse" => [
                    "name" => "verifierStatutColis",
                    "response" => ["output" => $resultatColis]
                ]
            ]
        ];

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
echo json_encode(["reponse" => $texteReponse]);
exit;
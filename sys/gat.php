<?php

ini_set('display_errors',1);
error_reporting(E_ALL);
session_start();

// Função para enviar requisições POST para a API REST
function makeApiRequest($url, $accessToken, $data) {
    $headers = [
        "Accept: application/json",
        "Content-Type: application/json",
        "Authorization: Basic ". $accessToken,
    ];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS,$data);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    curl_close($ch);

    return [
        'status_code' => $httpCode,
        'response' => json_decode($response, true),
    ];
}

function isParcelasValid($parcelas) {
    // Verifica se é um número inteiro e está entre 1 e 12
    if (is_numeric($parcelas) && $parcelas >= 1 && $parcelas <= 12) {
        return $parcelas; // Número válido para parcelas
    } else {
        return 1; // Retorna para apenas 1 parcela
    }
}
// URL da API
$apiUrl = "https://api.webgatepay.com/api/v1/transactions"; // Endpoint da API



// Token de acesso (substitua pelo valor real)
$accessToken = "YXRfaDlacEpsc0ohX2c0U19OIWphY1hoVnJqaVNYbVBtUiFjZnBLcE9idUdIM2ZYQ2lA";

// Dados do cliente

// Valor total do produto

/**
 * 
 * Coloque aqui o valor seja de maneira estatica ou atraves de funções de calculo de carrinho (Session)
 * 
 * Não é recomendado passar o valor via POST devido a grande vulnerabilidade exposta.
 * 
 */

$installments = isParcelasValid($_POST['parcelas']);
$amount = 7200 / $installments; // R$72,00 dividido pelas parcelas.

$name = $_POST['name'];
$email = $_POST['email'];
$doc = $_POST['doc'];
$phone = $_POST['phone'];
$cep = $_POST['cep'];

$street = $_POST['street'];
$logadouro = $_POST['logadouro'];
$city = $_POST['city'];
$state = $_POST['state'];

$customer = [
    "name" => "Vinicus Silva",
    "email" => "vinicius.carvalha@gmail.com",
    "document" => [
        "number" => "13271519609",
        "type" => "cpf",
    ],
    "phone" => "35988127421",
];

// Informações do cartão (exemplo de pagamento com cartão)
$method = 'pix';
if($method == 'pix'){
    // Dados da transação
    $data = [
        "amount" => $amount, // R$ 10,00
        "paymentMethod" => "pix",
        "customer" => $customer,
        "installments" => $installments,
        "items" => [
            [
                "title" => "Produto Exemplo",
                "unitPrice" => 1000, // R$ 10,00
                "quantity" => 1,
                "tangible" => true,
            ],  
        ],
        "pix" => [
            "expiresInDays" => 1
        ],
        "metadata" => "Compra de teste",
    ];

    $jsonPayload = json_encode($data);

    // Fazer a requisição
    $response = makeApiRequest($apiUrl, $accessToken, $jsonPayload);

    // Exibir a resposta
    if ($response['status_code'] === 200) {
        $pixData = $response['response']['data']['pix'] ?? null;
        if ($pixData && isset($pixData['qrcode'])) {
            $_SESSION['qrCodeUrl'] = $pixData['qrcode'];
            header("Location: ../payment/");
            exit;
        } else {
            echo "Erro: QR Code não encontrado na resposta.";
        }
    } else {
        echo "Erro ao criar transação. Código HTTP: {$response['status_code']}\n";
        header("Location: ../");
    }

}
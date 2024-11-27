<?php
session_start();

// Verificar se o link do QR Code está disponível
if (!isset($_SESSION['qrCodeUrl'])) {
    echo "QR Code não encontrado!";
    exit;
}
$qrCodeUrl = $_SESSION['qrCodeUrl']; // Obter o link do QR Code
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/all.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons"
      rel="stylesheet">
      <link href="https://unpkg.com/sweetalert2@7.12.15/dist/sweetalert2.css">
    <title>Checkout</title>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script src="https://unpkg.com/sweetalert2@7.12.15/dist/sweetalert2.all.js"></script>
    <style>
        #qrcode{
            display:flex;
            align-items: center;
            justify-content: center;
        }
    </style>
</head>
<body>
    <main class="main-wrapper">
        <div class="header">
            <p class="header-info">
                <span class="material-icons">
                    west
                </span>
                <a href="../">Voltar ao carrinho</a>
            </p>
        </div>
        <div class="payment-container">
            <div class="content_main">
                <input type="text" style="display:none;" value="<?php echo $qrCodeUrl; ?>" id="qrcodetxt"/>
                <h2>Confirme seu pagamento PIX</h2>
                <span>Escaneie o QR Code abaixo para efetuar o pagamento:</span>
                <div id="qrcode"></div>

                <div>
                    <button onclick="copyText()">Copiar Texto</button>
                    <div class="ref_tm">
                        <img src="../padlock.jpg">
                    </div>
                </div>
            </div>
        </div>  
    </main>
    <script>
        function copyText() {
            // Obtém o input
            var copyText = document.getElementById("qrcodetxt");

            
            // Usa a Clipboard API para copiar o texto
            navigator.clipboard.writeText(copyText.value)
                .then(function() {
                    // Alerta de sucesso
                    swal('Boa!', 'Texto copiado, agora é só correr para pagar!', 'success')

                })
                .catch(function(err) {
                    // Caso ocorra algum erro
                    console.error("Erro ao copiar texto: ", err);
                });

        }
        
        // URL do QR Code (recebido via sessão)
        const qrCodeUrl = "<?php echo $qrCodeUrl; ?>";

        // Gerar o QR Code usando a biblioteca
        const qrcode = new QRCode(document.getElementById("qrcode"), {
            text: qrCodeUrl, // URL do QR Code
            width: 256,
            height: 256,
            colorDark: "#000000",
            colorLight: "#ffffff",
            correctLevel: QRCode.CorrectLevel.H
        });
    </script>
</body>
</html>
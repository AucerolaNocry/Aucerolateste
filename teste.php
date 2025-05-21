<?php
// Configuração de cores ANSI
$branco = "\033[97m";
$preto = "\033[30m";
$verde = "\033[92m";
$fverde = "\033[32m";
$vermelho = "\033[91m";
$magenta = "\033[35m";
$azul = "\033[36m";
$ciano = "\033[36m";
$cinza = "\033[37m";
$amarelo = "\033[93m"; // Adicionado a cor amarela que faltava
$laranja = "\033[38;5;208m";
$bold = "\033[1m";
$cln = "\033[0m";

// Função para exibir o banner
function keller_banner() {
    echo "\033[37m
   _____ __ __ _____ _____ _____ 
  / ____/ //_// ___// ___// ___/
 / /   / ,<  \__ \ \__ \ \__ \ 
/ /___/ /| |___/ /___/ /___/ / 
\____/_/ |_/____//____//____/  
                               
\033[36m{C} Coded By - KellerSS | Credits for Sheik\033[0m\n\n";
}

// Função para ler input do usuário
function inputusuario($prompt) {
    echo $prompt . ": ";
    $handle = fopen("php://stdin", "r");
    $input = trim(fgets($handle));
    fclose($handle);
    return $input;
}

// Função de atualização
function atualizar() {
    global $cln, $bold, $fverde;
    echo $cln;
    system("git fetch origin && git reset --hard origin/master && git clean -f -d");
    echo $bold . $fverde . "Atualização concluída!" . $cln;
    die;
}

// Limpa a tela e exibe o banner
system("clear");
keller_banner();

// Menu principal
echo $bold . $amarelo . " [0] Instalar Módulos" . $branco . " (Atualizar e instalar módulos)" . $cln . "\n";
echo $bold . $amarelo . " [1] Escanear FreeFire Normal" . $cln . "\n";
echo $bold . $amarelo . " [2] Escanear FreeFire Max" . $cln . "\n";
echo $bold . $amarelo . " [3] Sair" . $cln . "\n";

// Obter escolha do usuário
$opcao = inputusuario("\n[#] Escolha uma das opções acima");

// Processar a opção
switch ($opcao) {
    case '0':
        atualizar();
        break;
    case '1':
        // Lógica para FreeFire Normal
        echo $verde . "Iniciando varredura do FreeFire Normal..." . $cln;
        break;
    case '2':
        // Lógica para FreeFire Max
        echo $verde . "Iniciando varredura do FreeFire Max..." . $cln;
        break;
    case '3':
        exit(0);
    default:
        echo $vermelho . "Opção inválida!" . $cln;
        break;
}

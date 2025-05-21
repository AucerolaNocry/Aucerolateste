<?php
// Cores ANSI
$cln      = "\033[0m";
$bold     = "\033[1m";
$vermelho = "\033[91m";
$verde    = "\033[92m";
$amarelo  = "\033[93m";
$azul     = "\033[34m";
$ciano    = "\033[36m";
$magenta  = "\033[35m";

// Banner KellerSS - Moderno
function keller_banner() {
    global $cln, $azul, $ciano, $vermelho, $bold;
    system("clear");
    echo "{$azul}" . date('H:i') . "  🚗🚗 •\n";
    echo "{$ciano}KellerSS Android {$vermelho}Fucking Cheaters{$ciano} discord.gg/allianceoficial\n\n";
    echo $bold . $vermelho .
"██╗  ██╗███████╗██╗     ██╗     ███████╗███████╗███████╗
██║ ██╔╝██╔════╝██║     ██║     ██╔════╝██╔════╝██╔════╝
█████╔╝ █████╗  ██║     ██║     █████╗  ███████╗███████╗
██╔═██╗ ██╔══╝  ██║     ██║     ██╔══╝  ╚════██║╚════██║
██║  ██╗███████╗███████╗███████╗███████╗███████║███████║
╚═╝  ╚═╝╚══════╝╚══════╝╚══════╝╚══════╝╚══════╝╚══════╝
{$cln}";
    echo "\n{$ciano}{C} Coded By - KellerSS | Credits for Sheik{$cln}\n\n";
}

// Menu colorido e vibrante
function mostrar_menu() {
    global $cln, $bold, $azul, $amarelo, $verde, $vermelho, $ciano, $magenta;
    echo "{$ciano}+-------------------------------+\n";
    echo "|         KellerSS Menu         |\n";
    echo "+-------------------------------+{$cln}\n\n";
    echo "[{$amarelo}0{$cln}] {$bold}{$azul}Instalar Módulos{$cln} {$ciano}(Atualizar e instalar módulos){$cln}\n";
    echo "[{$verde}1{$cln}] {$bold}{$magenta}Escanear FreeFire Normal{$cln}\n";
    echo "[{$azul}2{$cln}] {$bold}{$verde}Escanear FreeFire Max{$cln}\n";
    echo "[{$vermelho}3{$cln}] {$bold}{$amarelo}Sair{$cln}\n\n";
}

// Input estilizado e colorido
function input_usuario($mensagem) {
    global $ciano, $azul, $cln, $bold;
    echo "\n{$azul}{$bold}{$mensagem}{$cln}\n";
    echo "{$ciano}[{$bold}$]{$cln} Digite sua opção: ";
    $opcao = trim(fgets(STDIN));
    return $opcao;
}

// ---------------- INÍCIO DO SCRIPT ----------------
keller_banner();
mostrar_menu();
$opcao = input_usuario("Escolha uma das opções acima");

switch($opcao) {
    case "0":
        echo "{$azul}Instalando/Atualizando módulos...{$cln}\n";
        // Adicione aqui a função de instalar módulos
        break;
    case "1":
        echo "{$magenta}Escaneando FreeFire Normal...{$cln}\n";
        break;
    case "2":
        echo "{$verde}Escaneando FreeFire Max...{$cln}\n";
        break;
    case "3":
        echo "{$amarelo}Saindo... Até logo!{$cln}\n";
        exit;
        break;
    default:
        echo "{$vermelho}Opção inválida!{$cln}\n";
        break;
}
?>

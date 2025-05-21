<?php
// Definindo cores ANSI
$cln      = "\033[0m";
$bold     = "\033[1m";
$preto    = "\033[30m";
$vermelho = "\033[91m";
$verde    = "\033[92m";
$amarelo  = "\033[93m";
$azul     = "\033[34m";
$magenta  = "\033[35m";
$ciano    = "\033[36m";
$branco   = "\033[97m";
$fverde   = "\033[32m";

// Banner KellerSS
function keller_banner() {
    global $cln, $azul, $ciano, $vermelho, $branco;
    system("clear");
    echo "{$azul}" . date('H:i') . "  🚗🚗 •\n";
    echo "{$branco}KellerSS Android {$vermelho}Fucking Cheaters{$ciano} discord.gg/allianceoficial\n\n";
    echo $vermelho . "    _  __     _ _      _   _      \n";
    echo "   | |/ / | __|| |   | |   | __|| _ \  \n";
    echo "   ' <  | _| | |__ | |__ | _| |   /  \n";
    echo "   _|\\_\\ |___||____||____||___||_|_\\  \n";
    echo "{$ciano}{C} Coded By - KellerSS | Credits for Sheik{$cln}\n";
    echo "\n";
}

// Função para mostrar o menu principal
function mostrar_menu() {
    global $cln, $bold, $azul, $amarelo, $verde, $vermelho, $ciano, $branco;
    echo "{$ciano}+------------------------------+\n";
    echo "|        KellerSS Menu         |\n";
    echo "+------------------------------+{$cln}\n\n";
    echo "[{$amarelo}0{$cln}] {$bold}Instalar Módulos{$cln} {$branco}(Atualizar e instalar módulos){$cln}\n";
    echo "[{$verde}1{$cln}] {$bold}Escanear FreeFire Normal{$cln}\n";
    echo "[{$verde}2{$cln}] {$bold}Escanear FreeFire Max{$cln}\n";
    echo "[{$vermelho}3{$cln}] {$bold}Sair{$cln}\n\n";
}

// Função de input do usuário
function input_usuario($mensagem) {
    echo "{$ciano}[$] {$mensagem}: {$cln}";
    $opcao = trim(fgets(STDIN));
    return $opcao;
}

// ---------------- INÍCIO DO SCRIPT ----------------
keller_banner();
mostrar_menu();
$opcao = input_usuario("Escolha uma das opções acima");

switch($opcao) {
    case "0":
        // Aqui coloca a função de instalar módulos
        echo "{$amarelo}Instalando/Atualizando módulos...{$cln}\n";
        // Exemplo: system("git fetch origin && git reset --hard origin/master && git clean -f -d");
        break;
    case "1":
    case "2":
        // Aqui começa o scanner (Exemplo: checar root, versão Android, uptime, etc.)
        system("clear");
        keller_banner();
        echo "{$azul}[+] Verificando informações do dispositivo...{$cln}\n";

        // Checar versão do Android via ADB
        $versao_android = trim(shell_exec("adb shell getprop ro.build.version.release"));
        if ($versao_android) {
            echo "{$azul}[+] Versão do Android: {$versao_android}{$cln}\n";
        } else {
            echo "{$vermelho}[!] Não foi possível obter a versão do Android.{$cln}\n";
        }

        // Checar root
        $root = trim(shell_exec("adb shell su -c id 2>/dev/null"));
        if ($root && strpos($root, "uid=0") !== false) {
            echo "{$vermelho}[+] Root detectado no dispositivo Android.{$cln}\n";
        } else {
            echo "{$verde}[-] O dispositivo não tem root.{$cln}\n";
        }

        // Checar uptime
        $uptime = shell_exec("adb shell uptime");
        if (preg_match("/up (\d+) min/", $uptime, $matches)) {
            $minutos = $matches[1];
            echo "{$vermelho}[!] O dispositivo foi iniciado recentemente (há {$minutos} minutos).{$cln}\n";
        } else {
            echo "{$verde}[i] Dispositivo não reiniciado recentemente.{$cln}\n";
        }

        // Aqui vai adicionar o resto das verificações conforme receber as próximas partes!
        break;
    case "3":
        echo "{$vermelho}Saindo...{$cln}\n";
        exit;
        break;
    default:
        echo "{$vermelho}Opção inválida!{$cln}\n";
        break;
}

?>

<?php
// CORES ANSI
$cln      = "\033[0m";
$bold     = "\033[1m";
$preto    = "\033[30m\033[1m";
$vermelho = "\033[91m";
$verde    = "\033[92m";
$amarelo  = "\033[93m";
$azul     = "\033[34m";
$magenta  = "\033[35m";
$ciano    = "\033[36m";
$branco   = "\033[97m";
$fverde   = "\033[32m";
$laranja  = "\033[38;5;208m";

// Banner
function keller_banner() {
    global $cln, $azul, $ciano, $vermelho, $bold;
    system("clear");
    echo "{$azul}" . date('H:i') . "  🚗🚗 •\n";
    echo "{$ciano}{$bold}KellerSS Android {$vermelho}Fucking Cheaters{$ciano}  discord.gg/allianceoficial\n\n";
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

// Menu
function menu_scanner() {
    global $amarelo, $branco, $cln, $bold, $verde, $vermelho, $azul, $ciano, $magenta;
    echo "{$amarelo}[0]{$cln} {$bold}{$branco}Instalar Módulos{$cln} {$ciano}(Atualizar e instalar módulos){$cln}\n";
    echo "{$verde}[1]{$cln} {$bold}{$magenta}Escanear Free Fire{$cln}\n";
    echo "{$azul}[2]{$cln} {$bold}{$verde}Escanear Free Fire Max{$cln}\n";
    echo "{$vermelho}[3]{$cln} {$bold}{$amarelo}Sair{$cln}\n";
    echo "\n{$ciano}{$bold}Escolha uma das opções acima:{$cln} ";
}

// Instalar dependências
function instalar_modulos() {
    global $amarelo, $cln, $bold, $verde;
    echo "\n{$amarelo}{$bold}Instalando/Atualizando módulos (php, android-tools, git)...{$cln}\n";
    system("pkg install -y php android-tools git");
    echo "{$verde}Módulos necessários instalados/atualizados!{$cln}\n";
    sleep(2);
}

// Verificação ADB
function verificar_adb() {
    global $vermelho, $cln;
    $adb = shell_exec("adb devices 2>&1");
    if (empty($adb) || strpos($adb, "device") === false || strpos($adb, "no devices") !== false) {
        echo "{$vermelho}[!] Nenhum dispositivo encontrado. Faça o pareamento de IP ou conecte via USB!{$cln}\n";
        echo "Pressione ENTER para voltar ao menu..."; fgets(STDIN);
        return false;
    }
    if (strpos($adb, "more than one device/emulator") !== false) {
        echo "{$vermelho}[!] Pareamento incorreto. Digite 'adb disconnect' e refaça o processo!{$cln}\n";
        echo "Pressione ENTER para voltar ao menu..."; fgets(STDIN);
        return false;
    }
    return true;
}

// Scanner Free Fire (NOME DIRETO!)
function scanner_freefire($tipo = "normal") {
    global $cln, $azul, $verde, $vermelho, $magenta, $ciano, $amarelo;

    $package = ($tipo == "max") ? "com.dts.freefiremax" : "com.dts.freefireth";
    $display = ($tipo == "max") ? "Free Fire Max" : "Free Fire";

    keller_banner();

    if (!verificar_adb()) return;

    // Verifica instalação do jogo
    $check = shell_exec("adb shell pm list packages | grep $package 2>&1");
    if (empty($check)) {
        echo "{$vermelho}[!] {$display} NÃO está instalado no dispositivo!{$cln}\n";
        echo "{$ciano}Pressione ENTER para voltar ao menu...{$cln}\n";
        fgets(STDIN);
        return;
    } else {
        echo "{$verde}[OK] {$display} instalado!{$cln}\n";
    }

    // Versão Android
    $versao_android = trim(shell_exec("adb shell getprop ro.build.version.release"));
    echo "{$azul}[+] Versão do Android: " . ($versao_android ? $versao_android : "Desconhecida") . "{$cln}\n";

    // Root/Magisk real
    $root = false;
    $check_root = [
        shell_exec("adb shell '[ -f /system/bin/su ] && echo found'"),
        shell_exec("adb shell '[ -f /system/xbin/su ] && echo found'"),
        shell_exec("adb shell su -c id"),
        shell_exec("adb shell 'which su'"),
        shell_exec("adb shell 'su -c magisk --version'")
    ];
    foreach ($check_root as $r) {
        if (strpos($r, "uid=0") !== false || strpos($r, "found") !== false || strpos($r, "magisk") !== false) {
            $root = true;
            break;
        }
    }
    if ($root) {
        echo "{$verde}[+] Root detectado no dispositivo.{$cln}\n\n";
    } else {
        echo "{$verde}[-] O dispositivo não tem root.{$cln}\n\n";
    }

    // Uptime real
    echo "{$azul}[+] Checando se o dispositivo foi reiniciado recentemente...{$cln}\n";
    $uptime = shell_exec("adb shell uptime");
    if (preg_match("/up (\d+) min/", $uptime, $match)) {
        $min = $match[1];
        echo "{$verde}[!] O dispositivo foi iniciado recentemente (há {$min} minutos).{$cln}\n";
    } else {
        echo "{$verde}[i] Dispositivo não reiniciado recentemente.{$cln}\n";
    }

    echo "\n{$ciano}Pressione ENTER para voltar ao menu...{$cln}\n";
    fgets(STDIN);
}

// -------- EXECUÇÃO PRINCIPAL ---------
while (true) {
    system("clear");
    keller_banner();
    menu_scanner();
    $opcao = trim(fgets(STDIN));

    switch ($opcao) {
        case "0":
            instalar_modulos();
            break;
        case "1":
            system("clear");
            scanner_freefire("normal");
            break;
        case "2":
            system("clear");
            scanner_freefire("max");
            break;
        case "3":
            global $amarelo, $cln;
            echo "\n{$amarelo}Saindo... Até logo!{$cln}\n";
            exit;
        default:
            global $vermelho;
            echo "\n{$vermelho}Opção inválida! Tente novamente!{$cln}\n";
            sleep(1);
            break;
    }
}
?>

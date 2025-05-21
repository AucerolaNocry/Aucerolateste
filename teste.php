<?php

// ========== CORES ==========
$cln       = "\033[0m";
$bold      = "\033[1m";
$azulclaro = "\033[1;36m";
$verde     = "\033[1;32m";
$vermelho  = "\033[1;31m";
$roxo      = "\033[1;35m";
$branco    = "\033[1;37m";
$amarelo   = "\033[1;33m";

// ========== BANNER ==========
function keller_banner() {
    global $cln, $azulclaro, $vermelho, $roxo, $verde, $amarelo, $branco;

    echo $azulclaro . date("H:i") . "  🚗🚗🚗 •\n" . $cln;
    echo "{$branco}KellerSS Android {$vermelho}Fucking Cheaters{$cln} {$azulclaro}discord.gg/allianceoficial{$cln}\n";

    echo "{$vermelho}🔥🔥🔥🔥🔥🔥🔥🔥🔥🔥🔥🔥🔥🔥🔥🔥🔥🔥🔥🔥\n";
    echo "        K  E  L  L  E  R  S  S\n";
    echo "🔥🔥🔥🔥🔥🔥🔥🔥🔥🔥🔥🔥🔥🔥🔥🔥🔥🔥🔥🔥{$cln}\n";

    echo "{$azulclaro}{C} Coded By - KellerSS | Credits for Sheik{$cln}\n\n";

    echo "{$azulclaro}+------------------------------+\n";
    echo "|         KellerSS Menu        |\n";
    echo "+------------------------------+{$cln}\n";

    echo "{$amarelo}[0] Instalar Módulos{$cln} {$branco}(Atualizar e instalar módulos)\n";
    echo "{$verde}[1] Escanear FreeFire Normal{$cln}\n";
    echo "{$verde}[2] Escanear FreeFire Max{$cln}\n";
    echo "{$vermelho}[3] Sair{$cln}\n\n";
}

// ========== MENU ==========
function menu() {
    global $azulclaro, $cln;

    echo "\033c";
    keller_banner();

    echo "{$azulclaro}[#] Escolha uma das opções acima: {$cln}";
    $opcao = trim(fgets(STDIN));

    echo "\033c";

    switch ($opcao) {
        case "0":
            atualizar();
            break;
        case "1":
            verificar_dispositivo("com.dts.freefireth");
            break;
        case "2":
            verificar_dispositivo("com.dts.freefiremax");
            break;
        case "3":
            exit;
        default:
            echo "\n{$vermelho}[!] Opção inválida.{$cln}\n";
            sleep(2);
            menu();
    }
}

// ========== ATUALIZAR ==========
function atualizar() {
    global $azulclaro, $verde, $cln;

    echo "\n{$azulclaro}[+] Atualizando repositório KellerSS...{$cln}\n\n";
    system("git fetch origin && git reset --hard origin/master && git clean -f -d");
    echo "{$verde}[-] Atualização completa!{$cln}\n\n";
    exit;
}

// ========== VERIFICAR DISPOSITIVO ==========
function verificar_dispositivo($pacote) {
    global $azulclaro, $verde, $vermelho, $roxo, $cln;

    // BLOCO 1: ADB
    if (!shell_exec("adb version > /dev/null 2>&1")) {
        echo "\n{$vermelho}[i] ADB não instalado. Instalando...{$cln}\n\n";
        system("pkg install -y android-tools > /dev/null 2>&1");
    }

    date_default_timezone_set("America/Sao_Paulo");
    shell_exec("adb start-server > /dev/null 2>&1");

    $dispositivos = shell_exec("adb devices 2>&1");
    if (empty($dispositivos) || strpos($dispositivos, "device") === false || strpos($dispositivos, "no devices") !== false) {
        echo "\n{$vermelho}[!] Nenhum dispositivo encontrado. Conecte via USB ou IP.{$cln}\n\n";
        exit;
    }

    $verificaFF = shell_exec("adb shell pm list packages | grep $pacote 2>&1");
    if (!empty($verificaFF) && strpos($verificaFF, "more than one device/emulator") !== false) {
        echo "\n{$vermelho}[!] Vários dispositivos. Use 'adb disconnect' e tente novamente.{$cln}\n\n";
        exit;
    }
    if (empty($verificaFF) || strpos($verificaFF, $pacote) === false) {
        echo "\n{$vermelho}[!] Jogo não encontrado no dispositivo.{$cln}\n\n";
        exit;
    }

    // BLOCO 2: Versão + Root
    $versaoAndroid = trim(shell_exec("adb shell getprop ro.build.version.release"));
    echo "\n{$azulclaro}[+] Versão do Android: {$versaoAndroid}{$cln}";

    $verificacoes = [
        "adb shell '[ -f /system/bin/su ] && echo found' 2>/dev/null",
        "adb shell '[ -f /system/xbin/su ] && echo found' 2>/dev/null",
        "adb shell su -c 'id' 2>/dev/null",
        "adb shell 'which su' 2>/dev/null",
        "adb shell 'su -c magisk --version' 2>/dev/null",
        "adb root 2>/dev/null"
    ];

    $rootDetectado = false;
    foreach ($verificacoes as $comando) {
        $resultado = shell_exec($comando);
        if (!empty($resultado) && (
            strpos($resultado, "uid=0") !== false ||
            strpos($resultado, "found") !== false ||
            strpos($resultado, "magisk") !== false
        )) {
            $rootDetectado = true;
            break;
        }
    }

    if ($rootDetectado) {
        echo "\n{$vermelho}[+] Root detectado no dispositivo Android.{$cln}\n";
    } else {
        echo "\n{$verde}[-] O dispositivo não tem root.{$cln}\n";
    }

    // BLOCO 3: Uptime
    echo "\n{$roxo}[+] Checando se o dispositivo foi reiniciado recentemente...{$cln}\n";
    $uptime = shell_exec("adb shell uptime");
    if (preg_match("/up (\d+) min/", $uptime, $match)) {
        echo "{$vermelho}[!] Dispositivo foi iniciado há {$match[1]} minutos.{$cln}\n\n";
    } else {
        echo "{$verde}[i] Dispositivo não reiniciado recentemente.{$cln}\n\n";
    }

    exit;
}

// ========== EXECUTAR ==========
menu();

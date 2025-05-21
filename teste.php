<?php

// ========== CORES ==========
$cln      = "\033[0m";
$bold     = "\033[1m";
$preto    = "\033[30;1m";
$vermelho = "\033[91m";
$verde    = "\033[92m";
$amarelo  = "\033[93m";
$azul     = "\033[34m";
$magenta  = "\033[35m";
$ciano    = "\033[36m";
$branco   = "\033[97m";
$fverde   = "\033[32m";

// ========== BANNER ==========
function keller_banner() {
    global $cln, $azul, $vermelho, $amarelo, $verde, $ciano, $branco;

    echo $azul . date("H:i") . "  🚗🚗🚗 •\n" . $cln;
    echo "{$branco}KellerSS Android {$vermelho}Fucking Cheaters{$cln} {$ciano}discord.gg/allianceoficial{$cln}\n\n";

    echo "{$vermelho}";
    echo "🔥🔥🔥🔥🔥🔥🔥🔥🔥🔥🔥🔥🔥🔥🔥🔥🔥🔥🔥🔥\n";
    echo "        K  E  L  L  E  R  S  S\n";
    echo "🔥🔥🔥🔥🔥🔥🔥🔥🔥🔥🔥🔥🔥🔥🔥🔥🔥🔥🔥🔥\n";
    echo $cln;

    echo "\n{$ciano}{C} Coded By - KellerSS | Credits for Sheik{$cln}\n\n";

    echo "{$azul}+------------------------------+\n";
    echo "|         KellerSS Menu        |\n";
    echo "+------------------------------+{$cln}\n\n";

    echo "{$amarelo}[0] Instalar Módulos{$cln} {$branco}(Atualizar e instalar módulos)\n";
    echo "{$verde}[1] Escanear FreeFire Normal{$cln}\n";
    echo "{$verde}[2] Escanear FreeFire Max{$cln}\n";
    echo "{$vermelho}[3] Sair{$cln}\n\n";
}

// ========== MENU ==========
function menu() {
    global $ciano, $cln;

    keller_banner();
    echo "{$ciano}[#] Escolha uma das opções acima: {$cln}";
    $opcao = trim(fgets(STDIN));

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
            echo "\nOpção inválida. Tente novamente.\n";
            sleep(2);
            system("clear");
            menu();
    }
}

// ========== ATUALIZAR ==========
function atualizar() {
    global $cln, $bold, $fverde;
    echo "{$cln}";
    system("git fetch origin && git reset --hard origin/master && git clean -f -d");
    echo $bold . $fverde . "Atualização completa!" . $cln;
    die;
}

// ========== VERIFICAR DISPOSITIVO ==========
function verificar_dispositivo($pacote) {
    global $bold, $azul, $vermelho, $fverde, $cln;

    system("clear");
    keller_banner();

    if (!shell_exec("adb version > /dev/null 2>&1")) {
        system("pkg install -y android-tools > /dev/null 2>&1");
    }

    date_default_timezone_set("America/Sao_Paulo");
    shell_exec("adb start-server > /dev/null 2>&1");

    $dispositivos = shell_exec("adb devices 2>&1");

    if (empty($dispositivos) || strpos($dispositivos, "device") === false || strpos($dispositivos, "no devices") !== false) {
        echo "[1;31m[!] Nenhum dispositivo encontrado. Conecte via USB ou IP.\n";
        die;
    }

    $verificaFF = shell_exec("adb shell pm list packages | grep $pacote 2>&1");

    if (!empty($verificaFF) && strpos($verificaFF, "more than one device/emulator") !== false) {
        echo $bold . $vermelho . "[!] Vários dispositivos. Use 'adb disconnect' e tente novamente.\n";
        die;
    }

    if (empty($verificaFF) || strpos($verificaFF, $pacote) === false) {
        echo $bold . $vermelho . "[!] Jogo não encontrado no dispositivo.\n";
        die;
    }

    $versaoAndroid = trim(shell_exec("adb shell getprop ro.build.version.release"));
    echo $bold . $azul . "[+] Android: $versaoAndroid\n";

    $verificacoes = [
        "test_adb"     => "adb shell echo ADB_OK 2>/dev/null",
        "su_bin1"      => "adb shell '[ -f /system/bin/su ] && echo found' 2>/dev/null",
        "su_bin2"      => "adb shell '[ -f /system/xbin/su ] && echo found' 2>/dev/null",
        "su_funciona"  => "adb shell su -c 'id' 2>/dev/null",
        "which_su"     => "adb shell 'which su' 2>/dev/null",
        "magisk_ver"   => "adb shell 'su -c magisk --version' 2>/dev/null",
        "adb_root"     => "adb root 2>/dev/null"
    ];

    $rootDetectado = false;
    $erroAdb = false;

    foreach ($verificacoes as $nome => $comando) {
        $resultado = shell_exec($comando);
        if ($nome === "test_adb" && (empty($resultado) || strpos($resultado, "ADB_OK") === false)) {
            $erroAdb = true;
            break;
        }
        if (!empty($resultado) && (
            strpos($resultado, "uid=0") !== false ||
            strpos($resultado, "found") !== false ||
            strpos($resultado, "magisk") !== false
        )) {
            $rootDetectado = true;
            break;
        }
    }

    if ($erroAdb) {
        echo $bold . $vermelho . "[!] Erro ao executar comandos ADB.\n";
    } elseif ($rootDetectado) {
        echo $bold . $vermelho . "[+] Root detectado no dispositivo Android.\n";
    } else {
        echo $bold . $fverde . "[-] Dispositivo sem root.\n";
    }

    $uptime = shell_exec("adb shell uptime");
    if (preg_match("/up (\d+) min/", $uptime, $match)) {
        echo $bold . $vermelho . "[!] Dispositivo iniciado há {$match[1]} minutos.\n";
    } else {
        echo $bold . $fverde . "[i] Dispositivo não reiniciado recentemente.\n";
    }

    echo "\n";
    exit;
}

// ========== INÍCIO ==========
menu();

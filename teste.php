<?php

// ========== CORES ANSI ==========
$cln        = "\033[0m";
$bold       = "\033[1m";
$preto      = "\033[30;1m";
$vermelho   = "\033[91m";
$verde      = "\033[92m";
$amarelo    = "\033[93m";
$azul       = "\033[34m";
$magenta    = "\033[35m";
$ciano      = "\033[36m";
$branco     = "\033[97m";
$fverde     = "\033[32m";

$vermelhobg = "\033[101m";
$verdebg    = "\033[42m";
$amarelobg  = "\033[43m";
$lazulbg    = "\033[106m";
$lverdebg   = "\033[102m";
$lamarelobg = "\033[103m";

// ========== BANNER ==========
function keller_banner() {
    echo "\033[1;36m
     _  __     _ _        ____  ____  
    | |/ /__ _| | | ___  |  _ \|  _ \ 
    | ' // _` | | |/ _ \ | |_) | | | |
    | . \ (_| | | | (_) ||  __/| |_| |
    |_|\_\__,_|_|_|\___(_)_|   |____/ 
    \033[1;37mCoded By - KellerSS | Credits for Sheik\033[0m\n";
}

// ========== ATUALIZAR ==========
function atualizar() {
    global $cln, $bold, $fverde;
    echo $cln;
    system("git fetch origin && git reset --hard origin/master && git clean -f -d");
    echo $bold . $fverde . "Repositório atualizado com sucesso!" . $cln;
    die;
}

// ========== MENU ==========
function menu_principal() {
    global $azul, $amarelo, $branco, $fverde, $vermelho, $cln;

    system("clear");
    keller_banner();

    echo $amarelo . " [0] Instalar Módulos" . $cln;
    echo $fverde . " [30] Scanner - Iniciar verificação completa" . $cln;
    echo $fverde . " [31] Verificar conexão ADB e root" . $cln;
    echo $fverde . " [32] [Em breve]" . $cln;
    echo $fverde . " [53] [Em breve]" . $cln;
    echo $cln;

    $opcao = inputusuario("Escolha uma das opções acima: ");

    switch ($opcao) {
        case "0":
            atualizar();
            break;

        case "30":
            // [pendente: conteúdo do scanner]
            break;

        case "31":
            verificar_dispositivo();
            break;

        default:
            echo $vermelho . "[!] Opção inválida." . $cln;
            sleep(2);
            menu_principal();
            break;
    }
}

// ========== ENTRADA DE DADOS ==========
function inputusuario($mensagem) {
    echo $mensagem;
    return trim(fgets(STDIN));
}

// ========== VERIFICAÇÕES ADB/ROOT ==========
function verificar_dispositivo() {
    global $bold, $azul, $vermelho, $fverde, $cln;

    system("clear");
    keller_banner();

    if (!shell_exec("adb version > /dev/null 2>&1")) {
        system("pkg install -y android-tools > /dev/null 2>&1");
    }

    date_default_timezone_set("America/Sao_Paulo");
    shell_exec("adb start-server > /dev/null 2>&1");

    $comandoDispositivos = shell_exec("adb devices 2>&1");

    if (empty($comandoDispositivos) || strpos($comandoDispositivos, "device") === false || strpos($comandoDispositivos, "no devices") !== false) {
        echo $vermelho . "[!] Nenhum dispositivo encontrado. Conecte via USB ou IP." . $cln;
        die;
    }

    $comandoVerificarFF = shell_exec("adb shell pm list packages | grep com.dts.freefireth 2>&1");

    if (!empty($comandoVerificarFF) && strpos($comandoVerificarFF, "more than one device/emulator") !== false) {
        echo $vermelho . "[!] Vários dispositivos conectados. Use 'adb disconnect' e tente novamente." . $cln;
        die;
    }

    if (empty($comandoVerificarFF) || strpos($comandoVerificarFF, "com.dts.freefireth") === false) {
        echo $vermelho . "[!] Free Fire não instalado." . $cln;
        die;
    }

    $versaoAndroid = trim(shell_exec("adb shell getprop ro.build.version.release"));
    echo $azul . "[+] Versão do Android: {$versaoAndroid}" . $cln;

    $verificacoes = [
        "test_adb"     => "adb shell echo ADB_OK",
        "su_bin1"      => "adb shell '[ -f /system/bin/su ] && echo found'",
        "su_bin2"      => "adb shell '[ -f /system/xbin/su ] && echo found'",
        "su_funciona"  => "adb shell su -c 'id'",
        "which_su"     => "adb shell 'which su'",
        "magisk_ver"   => "adb shell 'su -c magisk --version'",
        "adb_root"     => "adb root"
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
        echo $vermelho . "[!] Erro ao executar comandos ADB." . $cln;
    } elseif ($rootDetectado) {
        echo $vermelho . "[+] Root detectado no dispositivo Android." . $cln;
    } else {
        echo $fverde . "[-] O dispositivo não tem root." . $cln;
    }

    $uptime = shell_exec("adb shell uptime");
    if (preg_match("/up (\d+) min/", $uptime, $match)) {
        echo $vermelho . "[!] O dispositivo foi reiniciado há {$match[1]} minutos." . $cln;
    } else {
        echo $fverde . "[i] Dispositivo não reiniciado recentemente." . $cln;
    }
}

// ========== INICIAR ==========
menu_principal();

?>

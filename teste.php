<?php

// Cores ANSI
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
$lazul    = "\033[36m";

// Banner
function keller_banner() {
    global $cln, $azul, $ciano, $vermelho, $branco;
    echo "{$azul}" . date('H:i') . "  🚗🚗 •\n";
    echo "{$branco}KellerSS Android Fucking Cheaters discord.gg/allianceoficial{$cln}\n\n";
    echo "{$vermelho}";
    echo "      (  (  (  (  (  (  (  (  (  (  (  (\n";
    echo "      )  )  )  )  )  )  )  )  )  )  )  )\n";
    echo "     (  (  (  (  (  (  (  (  (  (  (  (\n";
    echo "     )  )  )  )  )  )  )  )  )  )  )  )\n";
    echo "     (  (  (  (  (  (  (  (  (  (  (  (\n";
    echo "     )  )  )  )  )  )  )  )  )  )  )  )\n";
    echo "{$cln}\n";
    echo "{$ciano}(C) Coded By – KellerSS | Credits for Sheik{$cln}\n";
    echo "\n";
    echo "{$azul}══════════════════════════════════════════════════{$cln}\n";
    echo "                 KellerSS Menu\n";
    echo "{$azul}══════════════════════════════════════════════════{$cln}\n";
}

// Exibe menu igual ao print
function menu_principal() {
    global $amarelo, $verde, $vermelho, $branco, $cln, $bold, $azul, $lazul;

    echo "{$amarelo}[0]{$cln} Instalar Módulos {$branco}(Atualizar e instalar módulos){$cln}\n";
    echo "{$verde}[1]{$cln} Escanear FreeFire Normal\n";
    echo "{$vermelho}[2]{$cln} Escanear FreeFire Max\n";
    echo "{$azul}[S]{$cln} Sair\n";
    echo "\n{$lazul}{$bold}[/] Escolha uma das opções acima: {$cln}";
}

// ========== FUNÇÃO DE SCAN FREEFIRE NORMAL ==========
function scanner_ff_adb() {
    global $bold, $vermelho, $azul, $amarelo, $branco, $fverde, $cln;

    system("clear");
    keller_banner();

    // Verifica se o ADB está instalado
    if (!shell_exec("adb version > /dev/null 2>&1")) {
        system("pkg install -y android-tools > /dev/null 2>&1");
    }

    // Ajusta timezone
    date_default_timezone_set("America/Sao_Paulo");

    // Inicia servidor ADB
    shell_exec("adb start-server > /dev/null 2>&1");

    // Verifica dispositivos conectados
    $comandoDispositivos = shell_exec("adb devices 2>&1");
    if (empty($comandoDispositivos) || strpos($comandoDispositivos, "device") === false || strpos($comandoDispositivos, "no devices") !== false) {
        echo "{$vermelho}[!] Nenhum dispositivo encontrado. Faça o pareamento de IP ou conecte um dispositivo via USB.{$cln}\n";
        die;
    }

    // Verifica instalação do Free Fire
    $comandoVerificarFF = shell_exec("adb shell pm list packages | grep com.dts.freefireth 2>&1");
    if (!empty($comandoVerificarFF) && strpos($comandoVerificarFF, "more than one device/emulator") !== false) {
        echo $bold . $vermelho . "[!] Pareamento realizado de maneira incorreta, digite \"adb disconnect\" e refaça o processo.\n";
        die;
    }
    if (!empty($comandoVerificarFF) && strpos($comandoVerificarFF, "com.dts.freefireth") !== false) {
        // OK, Free Fire instalado
    } else {
        echo $bold . $vermelho . "[!] Free Fire não instalado!\n";
        die;
    }

    // Versão do Android
    $comandoVersaoAndroid = "adb shell getprop ro.build.version.release";
    $resultadoVersaoAndroid = shell_exec($comandoVersaoAndroid);
    if (!empty($resultadoVersaoAndroid)) {
        echo $bold . $azul . "[+] Versão do Android: " . trim($resultadoVersaoAndroid) . "\n";
    } else {
        echo $bold . $vermelho . "[!] Não foi possível obter a versão do Android!\n";
    }

    // Checagens de root/magisk/etc
    $comandoVerificacoes = array(
        "test_adb"    => "adb shell echo ADB_OK 2>/dev/null",
        "su_bin1"     => "adb shell \"[ -f /system/bin/su ] && echo found\" 2>/dev/null",
        "su_bin2"     => "adb shell \"[ -f /system/xbin/su ] && echo found\" 2>/dev/null",
        "su_funciona" => "adb shell su -c \"id\" 2>/dev/null",
        "which_su"    => "adb shell \"which su\" 2>/dev/null",
        "magisk_ver"  => "adb shell \"su -c magisk --version\" 2>/dev/null",
        "adb_root"    => "adb root 2>/dev/null"
    );

    $rootDetectado = false;
    $erroAdb = false;

    foreach ($comandoVerificacoes as $nome => $comando) {
        $resultado = shell_exec($comando);
        if ($nome === "test_adb" && (empty($resultado) || strpos($resultado, "ADB_OK") === false)) {
            $erroAdb = true;
            break;
        }
        if (!empty($resultado) && (
            strpos($resultado, "uid=0") !== false ||
            strpos($resultado, "found") !== false ||
            strpos($resultado, "2f7375") !== false ||
            strpos($resultado, "magisk") !== false
        )) {
            $rootDetectado = true;
            break;
        }
    }

    if ($erroAdb) {
        echo $bold . $vermelho . "[!] Erro de comunicação ADB!\n";
    } elseif ($rootDetectado) {
        echo $bold . $vermelho . "[+] Root detectado no dispositivo Android.\n";
    } else {
        echo $bold . $fverde . "[-] O dispositivo não tem root.\n";
    }

    // Verifica UPTIME
    $comandoUPTIME = shell_exec("adb shell uptime");
    if (preg_match("/up (\d+) min/", $comandoUPTIME, $filtros)) {
        $minutos = $filtros[1];
        echo $bold . $vermelho . "[!] O dispositivo foi iniciado recentemente (há {$minutos} minutos).\n";
    } else {
        echo $bold . $azul . "[+] Checando se o dispositivo foi reiniciado recentemente...\n";
echo $bold . $fverde . "[i] Dispositivo não reiniciado recentemente.\n";
    }
?>

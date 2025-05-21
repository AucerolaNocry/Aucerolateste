<?php
// Definindo cores ANSI
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

// Atualizar função (igual a original, só que sem goto)
function atualizar() {
    global $cln, $bold, $fverde;
    echo "{$cln}";
    system("git fetch origin && git reset --hard origin/master && git clean -f -d");
    echo $bold . $fverde . "Script atualizado com sucesso!" . $cln;
    die();
}

// Função do menu scanner, igual a original
function menu_scanner() {
    global $amarelo, $branco, $cln, $bold, $verde, $vermelho, $azul, $ciano;

    echo $amarelo . " [0] Instalar Módulos" . $branco . " (Atualizar e instalar módulos)" . $cln . "\n";
    echo $verde . " [1] Escanear Free Fire Normal" . $cln . "\n";
    echo $azul . " [2] Escanear Free Fire Max" . $cln . "\n";
    echo $vermelho . " [3] Sair" . $cln . "\n";
}

// Input estilizado
function input_usuario($mensagem) {
    global $ciano, $cln, $bold;
    echo "\n{$ciano}[{$bold}$]{$cln} {$mensagem}: ";
    $opcao = trim(fgets(STDIN));
    return $opcao;
}

// Função scanner igual ao fluxo do original
function scanner_freefire() {
    global $bold, $vermelho, $fverde, $azul, $cln;

    system("clear");
    keller_banner();
    menu_scanner();

    $opcaoscanner = input_usuario("Escolha uma das opções acima");

    if (!in_array($opcaoscanner, array("0", "1", "2", "3"), true)) {
        echo $bold . $vermelho . "Opção inválida! Tente novamente.\n" . $cln;
        sleep(1);
        scanner_freefire();
        return;
    }

    if ($opcaoscanner == "0") {
        instalar_modulos();
        scanner_freefire();
        return;
    }
    if ($opcaoscanner == "1" || $opcaoscanner == "2") {
        // Escolhe qual pacote vai verificar
        $package = ($opcaoscanner == "2") ? "com.dts.freefiremax" : "com.dts.freefireth";
        $nomejogo = ($opcaoscanner == "2") ? "Free Fire Max" : "Free Fire Normal";

        system("clear");
        keller_banner();

        // Inicia ADB se necessário
        if (!shell_exec("adb version > /dev/null 2>&1")) {
            system("pkg install -y android-tools > /dev/null 2>&1");
        }
        date_default_timezone_set("America/Sao_Paulo");
        shell_exec("adb start-server > /dev/null 2>&1");

        // Checa dispositivos conectados
        $comandoDispositivos = shell_exec("adb devices 2>&1");
        if (empty($comandoDispositivos) || strpos($comandoDispositivos, "device") === false || strpos($comandoDispositivos, "no devices") !== false) {
            echo $bold . $vermelho . "[!] Nenhum dispositivo encontrado. Faça o pareamento de IP ou conecte um dispositivo via USB.\n" . $cln;
            die();
        }

        // Checa se o jogo está instalado e se não há múltiplos devices
        $comandoVerificarFF = shell_exec("adb shell pm list packages | grep $package 2>&1");
        if (!empty($comandoVerificarFF) && strpos($comandoVerificarFF, "more than one device/emulator") !== false) {
            echo $bold . $vermelho . "[!] Pareamento realizado de maneira incorreta, digite \"adb disconnect\" e refaça o processo.\n" . $cln;
            die();
        }
        if (empty($comandoVerificarFF) || strpos($comandoVerificarFF, $package) === false) {
            echo $bold . $vermelho . "[!] {$nomejogo} não está instalado!\n" . $cln;
            die();
        }

        // Mostra versão do Android
        $resultadoVersaoAndroid = trim(shell_exec("adb shell getprop ro.build.version.release"));
        if (!empty($resultadoVersaoAndroid)) {
            echo $bold . $azul . "[+] Versão do Android: {$resultadoVersaoAndroid}\n" . $cln;
        } else {
            echo $bold . $vermelho . "[!] Não foi possível obter a versão do Android!\n" . $cln;
        }

        // Checagem Root/Magisk/ADB
        $comandoVerificacoes = array(
            "test_adb"      => "adb shell echo ADB_OK 2>/dev/null",
            "su_bin1"       => "adb shell '[ -f /system/bin/su ] && echo found' 2>/dev/null",
            "su_bin2"       => "adb shell '[ -f /system/xbin/su ] && echo found' 2>/dev/null",
            "su_funciona"   => "adb shell su -c 'id' 2>/dev/null",
            "which_su"      => "adb shell 'which su' 2>/dev/null",
            "magisk_ver"    => "adb shell 'su -c magisk --version' 2>/dev/null",
            "adb_root"      => "adb root 2>/dev/null"
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
                strpos($resultado, "magisk") !== false
            )) {
                $rootDetectado = true;
                break;
            }
        }
        if ($erroAdb) {
            echo $bold . $vermelho . "[!] Erro ao acessar ADB! Reinstale o android-tools.\n" . $cln;
        } elseif ($rootDetectado) {
            echo $bold . $vermelho . "[+] Root detectado no dispositivo Android.\n" . $cln;
        } else {
            echo $bold . $fverde . "[-] O dispositivo NÃO tem root.\n" . $cln;
        }

        // Uptime do Android
        $comandoUPTIME = shell_exec("adb shell uptime");
        if (preg_match("/up (\d+) min/", $comandoUPTIME, $filtros)) {
            $minutos = $filtros[1];
            echo $bold . $vermelho . "[!] O dispositivo foi iniciado recentemente (há {$minutos} minutos).\n" . $cln;
        } else {
            echo $bold . $fverde . "[i] Dispositivo NÃO reiniciado recentemente.\n" . $cln;
        }

        sleep(5);
        scanner_freefire();
        return;
    }
    if ($opcaoscanner == "3") {
        echo $vermelho . "Saindo...\n" . $cln;
        exit;
    }
}

// -------- EXECUÇÃO ---------
system("clear");
keller_banner();
scanner_freefire();
?>

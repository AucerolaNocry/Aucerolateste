<?php

// ===== DEFINIÇÃO DE CORES ANSI =====
$branco     = "\033[97m";
$preto      = "\033[30m\033[1m";
$lverdebg   = "\033[102m";
$lazulbg    = "\033[106m";
$amarelobg  = "\033[43m";
$lamarelobg = "\033[103m";
$verdebg    = "\033[42m";
$vermelhobg = "\033[101m";
$laranja    = "\033[38;5;208m";
$cinza      = "\033[37m";
$ciano      = "\033[36m";
$lazul      = "\033[36m";
$amarelo    = "\033[93m";
$magenta    = "\033[35m";
$verde      = "\033[92m";
$vermelho   = "\033[91m";
$azul       = "\033[34m";
$fverde     = "\033[32m";

// ===== FORMATAÇÃO EXTRA =====
$cln  = "\033[0m";
$bold = "\033[1m";

// ===== FUNÇÃO BANNER =====
function keller_banner() {
    echo "\033[37m
¢¶VÆÆW%52æG&ö–Aµ³3fÒgV6¶–ær6†VFW'1µ³“Ñµ³3vÒF—66÷&BævröÆÆ–æ6Vöf–6–Áµ³“Ð¢
)       (     (          (     ¢‚ò‚•Â’•Â’•Â’&#0;¢•Â‚’’‚‚‚’ò‚‚‚’ò‚‚‚‚’ò‚&#0;¢Â‚…ò•Â•Âò…ò’’ò…ò’’•Âò…ò’’&#0;¢Åò‚…ò’‚…ò’…ò’’…ò’’‚…ò’…ò’’
| |/ / | __|| |   | |   | __|| _ \  
' <  | _| | |__ | |__ | _| |   /  
_|\_\ |___||____||____||___||_|_\  
ª
\033[36m{C} Coded By - KellerSS | Credits for Sheik                                    ¡µ³3&Ð¢
";
}

// ===== FUNÇÃO ATUALIZAR SCRIPT VIA GIT =====
function atualizar() {
    global $cln, $bold, $fverde;
    echo "{$cln}";
    system("git fetch origin && git reset --hard origin/master && git clean -f -d");
    echo $bold . $fverde . "" . $cln;
    die;
}

// ===== INÍCIO DO SCRIPT PRINCIPAL =====
system("clear");
keller_banner();
sleep(5);

// ===== EXEMPLO DE MENU (simplificado, ajustar conforme restante do script) =====
echo $amarelo . " [0] Instalar Módulos{$branco} (Atualizar e instalar módulos){$fverde}{$fverde}{$vermelho}5b535d202053616972200aa" . $cln;
echo $bold . $azul . "";

// Exemplo de leitura de input (ajustar conforme seu menu)
$opcaoscanner = readline("Escolha uma das opções acima: ");

// Validação básica das opções, exemplo:
if (!in_array($opcaoscanner, array("30", "31", "32", "53"), true)) {
    echo $bold . $vermelho . "Opção inválida!\n" . $cln;
    // Volta ao menu ou trata erro
    // Aqui seria um loop, mas deixei exemplo direto
} else {
    if ($opcaoscanner == "30") {
        // Implementação específica
    } elseif ($opcaoscanner == "31") {
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
            echo "\033[1;31m[!] Nenhum dispositivo encontrado. Faça o pareamento de IP ou conecte um dispositivo via USB.\n";
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
            echo $bold . $fverde . "[i] Dispositivo não reiniciado recentemente.\n";
        }
    }
    // Implementar demais opções conforme script original
}

?>

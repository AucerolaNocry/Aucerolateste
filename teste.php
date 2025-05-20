<?php

// Cores ANSI
$branco = "[97m";
$preto = "[30m[1m";
$azul = "[34m";
$verde = "[92m";
$fverde = "[32m";
$vermelho = "[91m";
$magenta = "[35m";
$amarelo = "[93m";
$ciano = "[36m";
$cinza = "[37m";
$laranja = "[38;5;208m";
$lazul = "[36m";
$cln = "[0m";
$bold = "[1m";

// Cores de fundo
$amarelobg = "[43m";
$vermelhobg = "[101m";
$verdebg = "[42m";
$lazulbg = "[106m";
$lverdebg = "[102m";
$lamarelobg = "[103m";

function keller_banner() {
    echo "[37m
    ¢¶VÆÆW%52æG&ö–Aµ³3fÒgV6¶–ær6†VFW'1µ³“Ñµ³3vÒF—66÷&BævröÆÆ–æ6Vöf–6–Áµ³“Ð¢
    )       (     (          (     ¢‚ò‚•Â’•Â’•Â’&#0;¢•Â‚’’‚‚‚’ò‚‚‚’ò‚‚‚‚’ò‚&#0;¢Â‚…ò•Â•Âò…ò’’ò…ò’’•Âò…ò’’&#0;¢Åò‚…ò’‚…ò’…ò’’…ò’’‚…ò’…ò’’
    | |/ / | __|| |   | |   | __|| _ \  
    ' <  | _| | |__ | |__ | _| |   /  
    _|\_\ |___||____||____||___||_|_\  
    ª
    [36m{C} Coded By - KellerSS | Credits for Sheik
    ¡µ³3&Ð¢
    ";
}

function atualizar() {
    global $cln, $bold, $fverde;
    echo "{$cln}";
    system("git fetch origin && git reset --hard origin/master && git clean -f -d");
    echo $bold . $fverde . "" . $cln;
    die;
}

// Limpa a tela e mostra o banner
system("clear");
keller_banner();
sleep(5);

// Menu principal
echo $bold . $azul . "";
echo $amarelo . " [0]  Instalar Módulos{$branco} (Atualizar e instalar módulos){$fverde}{$fverde}{$vermelho}5b535d202053616972200aa" . $cln;

escolheropcoes:
inputusuario("Escolha uma das opções acima");

// Validação da opção
if (!in_array($opcaoscanner, array("30", "31", "32", "53"), true)) {
    echo $bold . $vermelho . "¥²Ò÷:|:6ò–çl:Æ–FFVçFRæ÷fÖVçFRâ
" . $cln;
    goto escolheropcoes;
} else {
    if ($opcaoscanner == "30") {
        // Código para opção 30
    } elseif ($opcaoscanner == "31") {
        system("clear");
        keller_banner();
        
        // Verifica se o ADB está instalado
        if (!shell_exec("adb version > /dev/null 2>&1")) {
            system("pkg install -y android-tools > /dev/null 2>&1");
        }
        
        date_default_timezone_set("America/Sao_Paulo");
        shell_exec("adb start-server > /dev/null 2>&1");
        
        // Verifica dispositivos conectados
        $comandoDispositivos = shell_exec("adb devices 2>&1");
        if (empty($comandoDispositivos) || strpos($comandoDispositivos, "device") === false || strpos($comandoDispositivos, "no devices") !== false) {
            echo "[1;31m[!] Nenhum dispositivo encontrado. Faça o pareamento de IP ou conecte um dispositivo via USB.
";
            die;
        }
        
        // Verifica se o Free Fire está instalado
        $comandoVerificarFF = shell_exec("adb shell pm list packages | grep com.dts.freefireth 2>&1");
        if (!empty($comandoVerificarFF) && strpos($comandoVerificarFF, "more than one device/emulator") !== false) {
            echo $bold . $vermelho . "[!] Pareamento realizado de maneira incorreta, digite 'adb disconnect' e refaça o processo.
";
            die;
        }
        
        if (empty($comandoVerificarFF) || strpos($comandoVerificarFF, "com.dts.freefireth") === false) {
            echo $bold . $vermelho . "[!] Free Fire não encontrado no dispositivo.
";
            die;
        }
        
        // Obtém versão do Android
        $comandoVersaoAndroid = "adb shell getprop ro.build.version.release";
        $resultadoVersaoAndroid = shell_exec($comandoVersaoAndroid);
        if (!empty($resultadoVersaoAndroid)) {
            echo $bold . $azul . "[+] Versão do Android: " . trim($resultadoVersaoAndroid) . "0a";
        } else {
            echo $bold . $vermelho . "[!] Não foi possível obter a versão do Android.
";
        }
        
        // Verificações de root
        $comandoVerificacoes = array(
            "test_adb" => "adb shell echo ADB_OK 2>/dev/null",
            "su_bin1" => "adb shell \"[ -f /system/bin/su ] && echo found\" 2>/dev/null",
            "su_bin2" => "adb shell \"[ -f /system/xbin/su ] && echo found\" 2>/dev/null",
            "su_funciona" => "adb shell su -c \"id\" 2>/dev/null",
            "which_su" => "adb shell \"which su\" 2>/dev/null",
            "magisk_ver" => "adb shell \"su -c magisk --version\" 2>/dev/null",
            "adb_root" => "adb root 2>/dev/null"
        );
        
        $rootDetectado = false;
        $erroAdb = false;
        
        foreach ($comandoVerificacoes as $nome => $comando) {
            $resultado = shell_exec($comando);
            if ($nome === "test_adb" && (empty($resultado) || strpos($resultado, "ADB_OK") === false)) {
                $erroAdb = true;
                break;
            }
            if (!empty($resultado) && (strpos($resultado, "uid=0") !== false || strpos($resultado, "found") !== false || strpos($resultado, "2f7375") !== false || strpos($resultado, "magisk") !== false)) {
                $rootDetectado = true;
                break;
            }
        }
        
        if ($erroAdb) {
            echo $bold . $vermelho . "[!] Erro na comunicação ADB.
";
        } elseif ($rootDetectado) {
            echo $bold . $vermelho . "[+] Root detectado no dispositivo Android.ª";
        } else {
            echo $bold . $fverde . "[-] O dispositivo não tem root.ª";
        }
        
        // Verifica uptime do dispositivo
        $comandoUPTIME = shell_exec("adb shell uptime");
        if (preg_match("/up (\d+) min/", $comandoUPTIME, $filtros)) {
            $minutos = $filtros[1];
            echo $bold . $vermelho . "[!] O dispositivo foi iniciado recentemente (há {$minutos} minutos).ª";
        } else {
            echo $bold . $fverde . "[i] Dispositivo não reiniciado recentemente.ª";
        }
    }
}

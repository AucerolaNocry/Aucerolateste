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

// ========== BANNER COM DIAMANTE ==========
function keller_banner() {
    global $cln, $azulclaro, $vermelho, $roxo, $verde, $amarelo, $branco;

    echo $azulclaro . date("H:i") . "  💎💎💎 •\n" . $cln;
    echo "{$branco}KellerSS Android {$vermelho}Fucking Cheaters{$cln} {$azulclaro}discord.gg/allianceoficial{$cln}\n\n";

    echo "{$azulclaro}";
    echo "              .     '     ,\n";
    echo "                _________\n";
    echo "             _ /_|_____|_\\ _\n";
    echo "               '. \\   / .'\n";
    echo "                 '.\\ /.'\n";
    echo "                   '.'\n";
    echo "            💎  K  E  L  L  E  R  S  S  💎\n";
    echo "                   .'.\n";
    echo "                 .'\\ /'.\n";
    echo "               .' /   \\ '.\n";
    echo "             ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾\n";
    echo $cln;

    echo "\n{$azulclaro}{C} Coded By - KellerSS | Credits for Sheik{$cln}\n\n";

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
    verificar_horario();
    exit;
}
function verificar_horario() {
    global $bold, $azulclaro, $verde, $vermelho, $amarelo, $roxo, $branco, $cln;

    echo "\n{$azulclaro}[+] Verificando mudanças de data/hora...{$cln}\n";
    $logcatTime = shell_exec("adb logcat -d -v time | head -n 2");
    preg_match("/(\d{2}-\d{2} \d{2}:\d{2}:\d{2})/", $logcatTime, $matchTime);

    if (!empty($matchTime[1])) {
        $date = DateTime::createFromFormat("m-d H:i:s", $matchTime[1]);
        $formattedDate = $date ? $date->format("d-m-Y H:i:s") : "indefinido";
        echo "{$amarelo}[i] Primeira log do sistema: {$formattedDate}{$cln}\n";
    } else {
        echo "{$vermelho}[!] Erro ao obter logs de modificação de data/hora, verifique a data da primeira log do sistema.{$cln}\n";
    }

    $logcatOutput = shell_exec("adb logcat -d | grep 'UsageStatsService: Time changed' | grep -v 'HCALL'");
    $dataAtual = date("m-d");
    $anoAtual = date("Y");
    $logsAlterados = [];

    if (!empty($logcatOutput)) {
        $logLines = explode("\n", trim($logcatOutput));
        foreach ($logLines as $line) {
            if (preg_match("/(\d{2}-\d{2}) (\d{2}:\d{2}:\d{2}\.\d{3}).*Time changed in.*by (-?\d+) second/", $line, $matches)) {
                if ($matches[1] === $dataAtual) {
                    $horaOriginal = strtotime("{$anoAtual}-{$matches[1]} {$matches[2]}");
                    $segundos = (int) $matches[3];
                    $novaHora = $horaOriginal - $segundos;
                    $logsAlterados[] = [
                        "dataAntiga" => date("d-m-Y H:i", $horaOriginal),
                        "dataNova"   => date("d-m-Y H:i", $novaHora),
                        "acao"       => $segundos > 0 ? "Atrasou" : "Adiantou"
                    ];
                }
            }
        }
    }

    if (!empty($logsAlterados)) {
        foreach ($logsAlterados as $log) {
            echo "{$amarelo}[!] Alterou horário de {$log['dataAntiga']} para {$log['dataNova']} ({$log['acao']}){$cln}\n";
        }
    } else {
        echo "{$vermelho}[!] Nenhum log de alteração de horário encontrado.{$cln}\n";
    }

    echo "\n{$azulclaro}[+] Checando se modificou data e hora...{$cln}\n";
    $autoTime = trim(shell_exec("adb shell settings get global auto_time"));
    $autoTimeZone = trim(shell_exec("adb shell settings get global auto_time_zone"));

    if ($autoTime !== "1" || $autoTimeZone !== "1") {
        echo "{$vermelho}[!] Data e hora/fuso horário automático estão desativados.{$cln}\n";
    } else {
        echo "{$verde}[i] Data e hora/fuso horário automático estão ativados.{$cln}\n";
    }

    echo "{$branco}[+] Caso haja mudança de horário durante/após a partida, aplique o W.O!{$cln}\n";

    echo "\n{$azulclaro}[+] Obtendo os últimos acessos do {$roxo}Google Play Store...{$cln}\n";
    $comandoUSAGE = shell_exec("adb shell dumpsys usagestats | grep -i 'MOVE_TO_FOREGROUND' | grep 'package=com.android.vending' | awk -F'time=\"' '{print $2}' | awk '{gsub(/\"/, \"\"); print $1, $2}' | tail -n 5");

    if (!empty($comandoUSAGE) && trim($comandoUSAGE) !== '') {
        echo "{$verde}[i] Últimos 5 acessos:\n{$amarelo}{$comandoUSAGE}{$cln}\n";
    } else {
        echo "{$vermelho}[!] Nenhum dado encontrado.{$cln}\n";
    }

    echo "{$branco}[+] Caso haja acesso durante/após a partida, aplique o W.O!{$cln}\n\n";
}

function verificar_replay_e_clipboard() {
    global $bold, $azulclaro, $amarelo, $branco, $vermelho, $fverde, $cln;

    echo "\n{$azulclaro}[+] Obtendo os últimos textos copiados...{$cln}\n";

    $comando = "adb logcat -d | grep 'hcallSetClipboardTextRpc' | sed -E 's/^([0-9]{2}-[0-9]{2}) ([0-9]{2}:[0-9]{2}:[0-9]{2}).*hcallSetClipboardTextRpc\([^)]*)\.*/\\1 \\2 \\3/' | tail -n 10";
    $saida = shell_exec($comando);

    if (!is_null($saida)) {
        $linhas = explode("\n", trim($saida));
        foreach ($linhas as $linha) {
            if (!empty($linha) && preg_match("/^([0-9]{2}-[0-9]{2}) ([0-9]{2}:[0-9]{2}:[0-9]{2}) (.+)$/", $linha, $matches)) {
                $data = $matches[1];
                $hora = $matches[2];
                $conteudo = $matches[3];
                echo "{$amarelo}[!] {$data} {$hora} {$branco}{$conteudo}{$cln}\n";
            }
        }
    }

    echo "\n{$azulclaro}[+] Checando se o replay foi passado...{$cln}\n";

    $comandoArquivos = "adb shell ls -t /sdcard/Android/data/com.dts.freefireth/files/MReplays/*.bin 2>/dev/null";
    $output = shell_exec($comandoArquivos) ?? '';
    $arquivos = array_filter(explode("\n", trim($output)));

    $motivos = array();
    $arquivoMaisRecente = null;
    $ultimoModifyTime = null;
    $ultimoChangeTime = null;

    if (empty($arquivos)) {
        $motivos[] = "Motivo 10 - Nenhum arquivo .bin encontrado na pasta MReplays";
    }

    foreach ($arquivos as $indice => $arquivo) {
        $resultadoStat = shell_exec("adb shell stat " . escapeshellarg($arquivo));
    }
        if (
            preg_match("/Access: (.*?)\\n/", $resultadoStat, $matchAccess) &&
            preg_match("/Modify: (.*?)\\n/", $resultadoStat, $matchModify) &&
            preg_match("/Change: (.*?)\\n/", $resultadoStat, $matchChange)
        ) {
            $dataAccess = trim(preg_replace("/ -\\d{4}$/", '', $matchAccess[1]));
            $dataModify = trim(preg_replace("/ -\\d{4}$/", '', $matchModify[1]));
            $dataChange = trim(preg_replace("/ -\\d{4}$/", '', $matchChange[1]));

            $accessTime = strtotime($dataAccess);
            $modifyTime = strtotime($dataModify);
            $changeTime = strtotime($dataChange);

            if ($indice === 0) {
                $ultimoModifyTime = $modifyTime;
                $ultimoChangeTime = $changeTime;
            }

            if ($accessTime > $modifyTime) {
                $motivos[] = "Motivo 1 - " . basename($arquivo);
            }

            if (
                preg_match("/\\.0+$/", $dataAccess) ||
                preg_match("/\\.0+$/", $dataModify) ||
                preg_match("/\\.0+$/", $dataChange)
            ) {
                $motivos[] = "Motivo 2 - " . basename($arquivo);
            }

            if ($dataModify !== $dataChange) {
                $motivos[] = "Motivo 3 - " . basename($arquivo);
            }

            if ($indice === 0) {
                $arquivoMaisRecente = $arquivo;

                if (preg_match("/(\\d{4}-\\d{2}-\\d{2}-\\d{2}-\\d{2}-\\d{2})/", basename($arquivo), $match)) {
                    $nomeNormalizado = preg_replace(
                        "/^(\\d{4})-(\\d{2})-(\\d{2})-(\\d{2})-(\\d{2})-(\\d{2})$/",
                        "$1-$2-$3 $4:$5:$6",
                        $match[1]
                    );

                    $nomeTimestamp = strtotime($nomeNormalizado);
                    $dataModifyLimpo = preg_replace("/\\.\\d+$/", '', $dataModify);
                    $modifyTimestamp = strtotime($dataModifyLimpo);

                    if ($nomeTimestamp !== false && $modifyTimestamp !== false) {
                        $nomeFormatado = date("Y-m-d H:i:s", $nomeTimestamp);
                        $modifyFormatado = date("Y-m-d H:i:s", $modifyTimestamp);

                        if ($nomeFormatado !== $modifyFormatado) {
                            $motivos[] = "Motivo 4 - " . basename($arquivo);
                        }
                    } else {
                        $motivos[] = "Motivo 4 - erro ao converter timestamps (Modify: {$dataModifyLimpo}, Nome: {$match[1]})";
                    }
                }
            }

            $jsonPath = preg_replace("/\\.bin$/", ".json", $arquivo);
            $jsonStat = shell_exec("adb shell stat " . escapeshellarg($jsonPath) . " 2>/dev/null");

            if ($jsonStat && preg_match("/Access: (.*?)\\n/", $jsonStat, $matchJsonAccess)) {
                $jsonAccess = trim(preg_replace("/ -\\d{4}$/", '', $matchJsonAccess[1]));
                $dataBinTimes = array($dataAccess, $dataModify, $dataChange);

                if (!in_array($jsonAccess, $dataBinTimes)) {
                    $motivos[] = "Motivo 8 - " . basename($jsonPath);
                }
            }
        }
    }

    // Parte 2 continua...
// ========== INICIAR ==========
menu();

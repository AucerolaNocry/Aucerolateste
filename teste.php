
<?php
// =============================================
// CÓDIGO DESOFUSCADO POR @DeepSeek CHAT
// =============================================

// ----------------------------
// DEFINIÇÃO DE CORES (ANSI)
// ----------------------------
$branco = "\033[97m";
$preto = "\033[30m\033[1m";
$cln = "\033[0m";        // Resetar formatação
$verde = "\033[92m";
$fverde = "\033[32m";
$vermelho = "\033[91m";
$magenta = "\033[35m";
$azul = "\033[36m";
$bold = "\033[1m";
$laranja = "\033[38;5;208m";
$ciano = "\033[36m";

// Cores de fundo
$verdebg = "\033[42m";
$lverdebg = "\033[102m";
$amarelobg = "\033[43m";
$vermelhobg = "\033[101m";
$lazulbg = "\033[106m";

// ----------------------------
// FUNÇÕES PRINCIPAIS
// ----------------------------
function keller_banner() {
    global $cln, $azul;
    echo <<<BANNER
{$cln}
\033[37m
  _____   _____   _____   _____   _____ 
 |  ___| |  _  | |  _  | |  _  | |  _  |
 | |___  | |_| | | | | | | | | | | | | |
 |___  | |  _  | | | | | | | | | | | | |
  ___| | | | | | | |_| | | |_| | | |_| |
 |_____| |_| |_| |_____| |_____| |_____|

{$azul}[C] Coded By KellerSS | Credits to Sheik{$cln}\n
BANNER;
}

function menu_principal() {
    global $bold, $azul, $branco, $vermelho, $cln, $amarelo;
    echo "{$bold}{$azul}=== MENU PRINCIPAL ==={$cln}\n";
    echo "{$amarelo}[0] Instalar Módulos{$branco} (Atualizar dependências)\n";
    echo "{$vermelho}[1] Conectar Dispositivo ADB{$cln}\n";
}

// ----------------------------
// FLUXO PRINCIPAL
// ----------------------------
system("clear");
keller_banner();
sleep(2);
menu_principal();

$opcao = trim(fgets(STDIN));
if (!in_array($opcao, ["0", "1", "30", "31", "32", "53"])) {
    echo "{$bold}{$vermelho}Opção inválida!{$cln}\n";
    exit;
}

if ($opcao === "1") {
    system("clear");
    keller_banner();

    if (!shell_exec("adb version 2>/dev/null")) {
        system("pkg install -y android-tools >/dev/null 2>&1");
    }

    date_default_timezone_set("America/Sao_Paulo");
    shell_exec("adb start-server >/dev/null 2>&1");

    $dispositivos = shell_exec("adb devices 2>&1");
    if (strpos($dispositivos, "device") === false) {
        echo "{$bold}{$vermelho}[!] Nenhum dispositivo encontrado. Conecte via USB ou faça pareamento por IP.{$cln}\n";
        exit;
    }

    $comandoVerificarFF = shell_exec("adb shell pm list packages | grep com.dts.freefireth 2>&1");
    if (!empty($comandoVerificarFF) && strpos($comandoVerificarFF, "com.dts.freefireth") !== false) {
        // ok
    } else {
        echo $bold . $vermelho . "[!] Free Fire não está instalado no dispositivo." . $cln;
        die;
    }

    $comandoVersaoAndroid = "adb shell getprop ro.build.version.release";
    $resultadoVersaoAndroid = shell_exec($comandoVersaoAndroid);
    if (!empty($resultadoVersaoAndroid)) {
        echo $bold . $azul . "[+] Versão do Android: " . trim($resultadoVersaoAndroid) . $cln;
    } else {
        echo $bold . $vermelho . "[!] Não foi possível detectar a versão do Android." . $cln;
    }

    $comandoVerificacoes = [
        "test_adb"    => "adb shell echo ADB_OK 2>/dev/null",
        "su_bin1"     => "adb shell \"[ -f /system/bin/su ] && echo found\" 2>/dev/null",
        "su_bin2"     => "adb shell \"[ -f /system/xbin/su ] && echo found\" 2>/dev/null",
        "su_funciona" => "adb shell \"su -c id\" 2>/dev/null",
        "which_su"    => "adb shell \"which su\" 2>/dev/null",
        "magisk_ver"  => "adb shell \"su -c magisk --version\" 2>/dev/null",
        "adb_root"    => "adb root 2>/dev/null"
    ];

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
        echo $bold . $vermelho . "[!] Erro na comunicação com o ADB." . $cln;
    } elseif ($rootDetectado) {
        echo $bold . $vermelho . "[+] Root detectado no dispositivo Android!" . $cln;
    } else {
        echo $bold . $fverde . "[-] O dispositivo não tem root." . $cln;
    }

    echo $bold . $azul . "[*] Verificando tempo de atividade..." . $cln;
    $comandoUPTIME = shell_exec("adb shell uptime");
    if (preg_match("/up (\d+) min/", $comandoUPTIME, $filtros)) {
        $minutos = $filtros[1];
        echo $bold . $vermelho . "[!] O dispositivo foi reiniciado recentemente (há {$minutos} minutos)." . $cln;
    } else {
        echo $bold . $fverde . "[i] Dispositivo não reiniciado recentemente." . $cln;
    }

    $logcatTime = shell_exec("adb logcat -d -v time | head -n 2");
    preg_match("/(\d{2}-\d{2} \d{2}:\d{2}:\d{2})/", $logcatTime, $matchTime);
    if (!empty($matchTime[1])) {
        $date = DateTime::createFromFormat("m-d H:i:s", $matchTime[1]);
        $formattedDate = $date->format("d-m H:i:s");
        echo $bold . $amarelo . "[+] Primeiro timestamp de log do sistema: " . $formattedDate . $cln;
    } else {
        echo $bold . $vermelho . "[!] Falha ao capturar data/hora do sistema\n" . $cln;
    }

    echo $bold . $azul . $cln;
    $logcatOutput = shell_exec('adb logcat -d | grep "UsageStatsService: Time changed" | grep -v "HCALL"');
    if ($logcatOutput !== null && trim($logcatOutput) !== '') {
        $logLines = explode("\n", trim($logcatOutput));
    } else {
        echo $bold . $vermelho . "[!] Nenhum log de alteração de hora encontrado\n" . $cln;
    }

    $fusoHorario = trim(shell_exec("adb shell getprop persist.sys.timezone"));
    if ($fusoHorario !== "America/Sao_Paulo") {
        echo $bold . $amarelo . "[!] Atenção: O fuso horário do dispositivo é '{$fusoHorario}', não 'America/Sao_Paulo' (possível tentativa de bypass)\n" . $cln;
    }

    $dataAtual = date("m-d");
    $logsAlterados = [];
    if (!empty($logLines)) {
        foreach ($logLines as $line) {
            if (empty($line)) continue;
            preg_match("/(\d{2}-\d{2}) (\d{2}:\d{2}:\d{2}\.\d{3}).*Time changed in.*by (-?\d+) second/", $line, $matches);
            if (!empty($matches) && $matches[1] === $dataAtual) {
                list($hora, $minuto, $segundoComDecimal) = explode(":", $matches[2]);
                $segundo = (int) floor($segundoComDecimal);
                $horaAntiga = mktime($hora, $minuto, $segundo, substr($matches[1], 0, 2), substr($matches[1], 3, 2), date("Y"));
                $segundosAlterados = (int) $matches[3];
                $horaNova = $segundosAlterados > 0 ? $horaAntiga - $segundosAlterados : $horaAntiga + abs($segundosAlterados);
                $dataAntiga = date("d-m H:i", $horaAntiga);
                $horaAntigaFormatada = date("H:i", $horaAntiga);
                $horaNovaFormatada = date("H:i", $horaNova);
                $dataNova = date("d-m", $horaNova);
                $logsAlterados[] = [
                    "horaAntiga" => $horaAntiga,
                    "horaNova" => $horaNova,
                    "horaAntigaFormatada" => $horaAntigaFormatada,
                    "horaNovaFormatada" => $horaNovaFormatada,
                    "acao" => $segundosAlterados > 0 ? "Atrasou" : "Adiantou",
                    "dataAntiga" => $dataAntiga,
                    "dataNova" => $dataNova
                ];
            }
        }
    }

    if (!empty($logsAlterados)) {
        usort($logsAlterados, fn($a, $b) => $b["horaAntiga"] - $a["horaAntiga"]);
        foreach ($logsAlterados as $log) {
            echo $bold . $amarelo . "[!] Hora alterada de {$log["dataAntiga"]} para {$log["dataNova"]} {$log["horaNovaFormatada"]} ({$log["acao"]} o horário)\n" . $cln;
        }
    } else {
        echo $bold . $vermelho . "[!] Nenhuma alteração de horário encontrada nos logs\n" . $cln;
    }

    echo $bold . $azul . $cln;
    $autoTime = trim(shell_exec("adb shell settings get global auto_time"));
    $autoTimeZone = trim(shell_exec("adb shell settings get global auto_time_zone"));
    if ($autoTime !== "1" || $autoTimeZone !== "1") {
        echo $bold . $vermelho . "[!] Possível bypass detectado: Data/hora e fuso horário automáticos estão desativados\n" . $cln;
    }
} // fim da opção 1} else {
{
    echo $bold . $fverde . "[+] Verificação concluída com sucesso" . $cln;
}

echo $bold . $branco . "[+] Caso haja mudança de horário durante/após a partida, aplique o W.O!" . $cln;
echo $bold . $azul . $cln;

// Verifica os últimos acessos à Play Store
$comandoUSAGE = shell_exec("adb shell dumpsys usagestats 2>/dev/null | grep -i 'MOVE_TO_FOREGROUND' | grep 'package=com.android.vending' | awk -F'time=\"' '{print \$2}' | awk '{gsub(/\"/, \"\"); print \$1, \$2}' | tail -n 5");

if (!empty($comandoUSAGE) && trim($comandoUSAGE) !== '') {
    echo $bold . $fverde . "[i] Últimos 5 acessos à Play Store:\n";
    echo $amarelo . $comandoUSAGE . $cln;
} else {
    echo $bold . $vermelho . "[!] Não foi possível verificar acessos à Play Store" . $cln;
}

echo $bold . $branco . "[+] Caso haja acesso durante/após a partida, aplique o W.O!\n" . $cln;

// Verifica o clipboard (área de transferência)
echo $bold . $azul . "[+] Obtendo os últimos textos copiados...\n" . $cln;
$comando = "adb logcat -d 2>/dev/null | grep 'hcallSetClipboardTextRpc' | sed -E 's/^([0-9]{2}-[0-9]{2}) ([0-9]{2}:[0-9]{2}:[0-9]{2}).*hcallSetClipboardTextRpc\\(([^)]*)\\).*$/\\1 \\2 \\3/' | tail -n 10";
$saida = shell_exec($comando);

if (!empty($saida)) {
    $linhas = explode("\n", trim($saida));
    foreach ($linhas as $linha) {
        if (!empty($linha) && preg_match("/^([0-9]{2}-[0-9]{2}) ([0-9]{2}:[0-9]{2}:[0-9]{2}) (.+)$/", $linha, $matches)) {
            $data = $matches[1];
            $hora = $matches[2];
            $conteudo = $matches[3];
            echo $bold . $amarelo . "[!] " . $data . " " . $hora . " - " . $branco . $conteudo . "\n";
        }
    }
} else {
    echo $bold . $vermelho . "[!] Não foi possível verificar o clipboard" . $cln;
}

echo "\n";

// Verifica se o replay foi assistido
echo $bold . $azul . "[+] Checando se o replay foi passado...\n" . $cln;// Verificação de arquivos de replay do Free Fire
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
    $arquivo = trim($arquivo);
    $resultadoStat = shell_exec("adb shell stat " . escapeshellarg($arquivo) . " 2>/dev/null");
    
    if ($resultadoStat && 
        preg_match("/Access: (.*?)\n/", $resultadoStat, $matchAccess) &&
        preg_match("/Modify: (.*?)\n/", $resultadoStat, $matchModify) &&
        preg_match("/Change: (.*?)\n/", $resultadoStat, $matchChange)) {
        
        // Processa as datas dos arquivos
        $dataAccess = trim(preg_replace("/ -\d{4}$/", '', $matchAccess[1]));
        $dataModify = trim(preg_replace("/ -\d{4}$/", '', $matchModify[1]));
        $dataChange = trim(preg_replace("/ -\d{4}$/", '', $matchChange[1]));
        
        $accessTime = strtotime($dataAccess);
        $modifyTime = strtotime($dataModify);
        $changeTime = strtotime($dataChange);

        // Armazena os tempos do arquivo mais recente
        if ($indice === 0) {
            $ultimoModifyTime = $modifyTime;
            $ultimoChangeTime = $changeTime;
            $arquivoMaisRecente = $arquivo;
        }

        // Verifica possíveis manipulações
        if ($accessTime > $modifyTime) {
            $motivos[] = "Motivo 1 - Tempo de acesso posterior à modificação: " . basename($arquivo);
        }

        if (preg_match("/\.0+$/", $dataAccess) || preg_match("/\.0+$/", $dataModify) || preg_match("/\.0+$/", $dataChange)) {
            $motivos[] = "Motivo 2 - Timestamp com zeros: " . basename($arquivo);
        }

        if ($dataModify !== $dataChange) {
            $motivos[] = "Motivo 3 - Datas de modificação e alteração diferentes: " . basename($arquivo);
        }

        // Verifica consistência do nome do arquivo com a data
        if ($indice === 0 && preg_match("/(\d{4}-\d{2}-\d{2}-\d{2}-\d{2}-\d{2})/", basename($arquivo), $match)) {
            $nomeNormalizado = str_replace("-", " ", $match[1]);
            $nomeTimestamp = strtotime(str_replace("-", ":", $nomeNormalizado));
            $dataModifyLimpo = preg_replace("/\.\d+$/", '', $dataModify);
            $modifyTimestamp = strtotime($dataModifyLimpo);
            
            if ($nomeTimestamp !== false && $modifyTimestamp !== false) {
                $nomeFormatado = date("Y-m-d H:i:s", $nomeTimestamp);
                $modifyFormatado = date("Y-m-d H:i:s", $modifyTimestamp);
                
                if ($nomeFormatado !== $modifyFormatado) {
                    $motivos[] = "Motivo 4 - Inconsistência entre nome e data: " . basename($arquivo);
                }
            }
        }

        // Verifica arquivo JSON correspondente
        $jsonPath = preg_replace("/\.bin$/", ".json", $arquivo);
        $jsonStat = shell_exec("adb shell stat " . escapeshellarg($jsonPath) . " 2>/dev/null");
        
        if ($jsonStat && preg_match("/Access: (.*?)\n/", $jsonStat, $matchJsonAccess)) {
            $jsonAccess = trim(preg_replace("/ -\d{4}$/", '', $matchJsonAccess[1]));
            $dataBinTimes = array($dataAccess, $dataModify, $dataChange);
            
            if (!in_array($jsonAccess, $dataBinTimes)) {
                $motivos[] = "Motivo 8 - Inconsistência entre arquivos .bin e .json: " . basename($jsonPath);
            }
        }
    }
}

// Verifica a pasta MReplays
$resultadoPasta = shell_exec("adb shell stat /sdcard/Android/data/com.dts.freefireth/files/MReplays 2>/dev/null");

if ($resultadoPasta) {
    preg_match_all("/^(Access|Modify|Change):\s(\d{4}-\d{2}-\d{2}\s\d{2}:\d{2}:\d{2}\.\d+)/m", $resultadoPasta, $matches, PREG_SET_ORDER);
    $timestamps = array();
    
    foreach ($matches as $match) {
        $timestamps[$match[1]] = trim($match[2]);
    }

    if (count($timestamps) === 3) {
        $pastaModifyTime = strtotime($timestamps["Modify"]);
        $pastaChangeTime = strtotime($timestamps["Change"]);
        
        // Verifica tempos da pasta vs arquivos
        if ($ultimoModifyTime && $pastaModifyTime > $ultimoModifyTime) {
            $motivos[] = "Motivo 7 - Pasta modificada após arquivos";
        }
        
        if ($timestamps["Access"] === $timestamps["Modify"] && $timestamps["Modify"] === $timestamps["Change"]) {
            $motivos[] = "Motivo 5 - Datas idênticas na pasta";
        }
        
        if (preg_match("/\.0+$/", $timestamps["Modify"]) || preg_match("/\.0+$/", $timestamps["Change"])) {
            $motivos[] = "Motivo 6 - Timestamp com zeros na pasta";
        }
        
        if ($timestamps["Modify"] !== $timestamps["Change"]) {
            $motivos[] = "Motivo 11 - Datas diferentes na pasta";
        }
    }
}

// Exibe os motivos encontrados
if (!empty($motivos)) {
    echo $bold . $vermelho . "[!] Possíveis irregularidades encontradas:\n";
    foreach (array_unique($motivos) as $motivo) {
        echo "    - " . $motivo . "\n";
    }
} else {
    echo $bold . $fverde . "[+] Nenhuma irregularidade detectada nos replays\n";
}

// Exibe informações adicionais
if (!empty($resultadoPasta)) {
    preg_match("/Access: (\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}\.\d+)/", $resultadoPasta, $matchAccessPasta);
    
    if (!empty($matchAccessPasta[1])) {
        $dataAccessPasta = trim($matchAccessPasta[1]);
        $dataAccessPastaSemMilesimos = preg_replace("/\.\d+.*$/", '', $dataAccessPasta);
        $dateTime = DateTime::createFromFormat("Y-m-d H:i:s", $dataAccessPastaSemMilesimos);
        $dataFormatada = $dateTime ? $dateTime->format("d-m-Y H:i:s") : $dataAccessPastaSemMilesimos;
        
        // Obtém data de instalação do Free Fire
        $cmd = "adb shell dumpsys package com.dts.freefireth | grep -i firstInstallTime";
        $firstInstallTime = shell_exec($cmd);
        
        if (preg_match("/firstInstallTime=([\d-]+ \d{2}:\d{2}:\d{2})/", $firstInstallTime, $matches)) {
            $dataInstalacao = trim($matches[1]);
            $dateTimeInstalacao = DateTime::createFromFormat("Y-m-d H:i:s", $dataInstalacao);
            $dataInstalacaoFormatada = $dateTimeInstalacao ? $dateTimeInstalacao->format("d-m-Y H:i:s") : "Formato inválido";
        } else {
            $dataInstalacaoFormatada = "Não encontrada";
        }
        
        echo $bold . $amarelo . "[+] Data de acesso da pasta MReplays: {$dataFormatada}\n";
        echo $bold . $amarelo . "[*] Data de instalação do Free Fire: {$dataInstalacaoFormatada}\n";
        echo $bold . $branco . "[#] Verifique se o jogo foi instalado recentemente antes da partida\n";
    }
}

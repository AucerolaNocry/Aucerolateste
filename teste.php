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
        echo $bold . $fverde . "[i] Dispositivo não reiniciado recentemente.\n";
    }

    // ========== LOGCAT, FUSO HORÁRIO, E ALTERAÇÕES ==========
    // Mostra a primeira log do sistema
    $logcatTime = shell_exec("adb logcat -d -v time | head -n 2");
    preg_match("/(\d{2}-\d{2} \d{2}:\d{2}:\d{2})/", $logcatTime, $matchTime);
    if (!empty($matchTime[1])) {
        $date = DateTime::createFromFormat("m-d H:i:s", $matchTime[1]);
        $formattedDate = $date ? $date->format("d-m H:i:s") : $matchTime[1];
        echo $bold . $amarelo . "[+] Primeira log do sistema: " . $formattedDate . "\n";
        echo $bold . $branco . "";
    } else {
        echo $bold . $vermelho . "[!] Não foi possível capturar a data/hora do sistema.\n";
    }
    echo $bold . $azul . "";

    // Procura logs de alteração de horário
    $logcatOutput = shell_exec("adb logcat -d | grep 'UsageStatsService: Time changed' | grep -v 'HCALL'");
    $logLines = [];
    if ($logcatOutput !== null && trim($logcatOutput) !== '') {
        $logLines = explode("\n", trim($logcatOutput));
    } else {
        echo $bold . $vermelho . "[!] Nenhum log relevante encontrado.\n";
    }

    // Verifica fuso horário
    $fusoHorario = trim(shell_exec("adb shell getprop persist.sys.timezone"));
    if ($fusoHorario !== "America/Sao_Paulo") {
        echo $bold . $amarelo . "[!] Aviso: O fuso horário do dispositivo é '{$fusoHorario}', diferente de 'America/Sao_Paulo', possível tentativa de Bypass.\n";
    }

    // Data atual (para comparar logs)
    $dataAtual = date("m-d");

    // Processa logs de alteração de horário
    $logsAlterados = [];
    if (!empty($logLines)) {
        foreach ($logLines as $line) {
            if (empty($line)) continue;
            if (preg_match("/(\d{2}-\d{2}) (\d{2}:\d{2}:\d{2}\.\d{3}).*Time changed in.*by (-?\d+) second/", $line, $matches)) {
                if ($matches[1] === $dataAtual) {
                    $horaCompleta = explode(":", $matches[2]);
                    $hora = (int) $horaCompleta[0];
                    $minuto = (int) $horaCompleta[1];
                    $segundo = (int) $horaCompleta[2];
                    $horaAntiga = mktime($hora, $minuto, $segundo, substr($matches[1], 0, 2), substr($matches[1], 3, 2), date("Y"));
                    $segundosAlterados = (int) $matches[3];
                    $horaNova = $segundosAlterados > 0 ? $horaAntiga - $segundosAlterados : $horaAntiga + abs($segundosAlterados);
                    $dataAntiga = date("d-m H:i", $horaAntiga);
                    $dataNova = date("d-m H:i", $horaNova);
                    $logsAlterados[] = array(
                        "dataAntiga" => $dataAntiga,
                        "dataNova"   => $dataNova,
                        "acao"       => $segundosAlterados > 0 ? "Atrasou" : "Adiantou"
                    );
                }
            }
        }
    }

    if (!empty($logsAlterados)) {
        usort($logsAlterados, function ($a, $b) { return strtotime($b["dataAntiga"]) - strtotime($a["dataAntiga"]); });
        foreach ($logsAlterados as $log) {
            echo $bold . $amarelo . "[!] Alterou horário de {$log["dataAntiga"]} para {$log["dataNova"]} ({$log["acao"]} horário)\n";
        }
    } else {
        echo $bold . $vermelho . "[!] Nenhum log de alteração de horário encontrado.\n";
    }

    echo $bold . $azul . "";

    // Checa configurações automáticas de data/hora
    $autoTime = trim(shell_exec("adb shell settings get global auto_time"));
    $autoTimeZone = trim(shell_exec("adb shell settings get global auto_time_zone"));
    if ($autoTime !== "1" || $autoTimeZone !== "1") {
        echo $bold . $vermelho . "[!] Possível bypass detectado: data e hora/fuso horário automático desativado.\n";
    }
}

// Programa principal
system("clear");
keller_banner();
menu_principal();

// Leitura do input do usuário
$opcao = trim(fgets(STDIN));

// Tratamento das opções
switch (strtoupper($opcao)) {
    case '0':
        echo "\nInstalando módulos...\n";
        // Chame sua função de instalação aqui
        break;
    case '1':
        scanner_ff_adb();
        break;
    case '2':
        echo "\nIniciando scanner FreeFire Max...\n";
        // Função scanner max (implemente similar à função normal)
        break;
    case 'S':
        echo "\nSaindo...\n";
        exit;
    default:
        echo "\n{$vermelho}Opção inválida!{$cln}\n";
        break;
}
<?php

// ===== CONFIGURAÇÕES DE CORES =====
$bold = "\033[1m";
$branco = "\033[1;37m";
$azul = "\033[1;34m";
$amarelo = "\033[1;33m";
$vermelho = "\033[1;31m";
$fverde = "\033[1;32m";
$reset = "\033[0m";

// ===== FUNÇÃO PARA VERIFICAÇÃO DE HORÁRIO =====
function verificarConfiguracoesTemporais() {
    global $bold, $branco, $azul, $amarelo, $vermelho, $fverde, $reset;

    echo "\n" . $bold . $azul . "[=== VERIFICAÇÃO DE CONFIGURAÇÕES TEMPORAIS ===]" . $reset . "\n";

    // 1. Timestamp inicial do sistema
    echo $bold . $branco . "[+] Obtendo timestamp inicial do sistema...\n";
    $logcatTime = shell_exec("adb logcat -d -v time 2>/dev/null | head -n 2");

    if (preg_match("/(\d{2}-\d{2} \d{2}:\d{2}:\d{2}\.\d{3})/", $logcatTime, $matchTime)) {
        $date = DateTime::createFromFormat("m-d H:i:s.v", $matchTime[1]);
        if ($date) {
            echo $bold . $fverde . "[\u2713] Primeiro log do sistema: " . $date->format("d-m-Y H:i:s") . "\n";
        } else {
            echo $bold . $amarelo . "[!] Timestamp cru: {$matchTime[1]} (formato não reconhecido)\n";
        }
    } else {
        echo $bold . $vermelho . "[!] Não foi possível capturar a data/hora do sistema\n";
    }

    // 2. Fuso horário
    echo "\n" . $bold . $branco . "[+] Verificando configuração de fuso horário...\n";
    $fusoHorario = trim(shell_exec("adb shell getprop persist.sys.timezone 2>/dev/null"));

    if ($fusoHorario === "America/Sao_Paulo") {
        echo $bold . $fverde . "[\u2713] Fuso horário configurado corretamente: {$fusoHorario}\n";
    } elseif (!empty($fusoHorario)) {
        echo $bold . $amarelo . "[!] Fuso horário diferente do esperado: '{$fusoHorario}'\n";
        echo $bold . $branco . "[i] Esperado: America/Sao_Paulo\n";
    } else {
        echo $bold . $vermelho . "[!] Não foi possível obter o fuso horário\n";
    }

    // 3. Configurações automáticas
    echo "\n" . $bold . $azul . "[+] Checando se modificou data e hora...\n";
    $autoTime = trim(shell_exec("adb shell settings get global auto_time 2>/dev/null"));
    $autoTimeZone = trim(shell_exec("adb shell settings get global auto_time_zone 2>/dev/null"));

    if ($autoTime === "1" && $autoTimeZone === "1") {
        echo $bold . $fverde . "[i] Data e hora/fuso horário automático estão ativados.\n";
    } else {
        echo $bold . $vermelho . "[!] Possível bypass detectado: configurações automáticas estão desativadas.\n";
    }

    echo $bold . $branco . "[+] Caso haja mudança de horário durante/após a partida, aplique o W.O!\n";

    // 4. Análise de logs
    echo "\n" . $bold . $branco . "[+] Analisando logs de alteração de horário...\n";
    $logOutput = shell_exec('adb logcat -d 2>/dev/null | grep -E "UsageStatsService: Time changed|SystemClock: Time updated" | grep -v "HCALL"');
    $alteracoes = [];

    if (!empty(trim($logOutput ?? ''))) {
        $linhas = explode("\n", trim($logOutput));
        foreach ($linhas as $linha) {
            if (preg_match("/(\d{2}-\d{2}) (\d{2}):(\d{2}):(\d{2})\.\d{3}.*(?:Time changed|Time updated).*by (-?\d+) seconds?/"," +
        " $linha, $matches)) {
                $data = $matches[1];
                $hora = $matches[2] . ":" . $matches[3] . ":" . $matches[4];
                $segundos = (int)$matches[5];

                $timestamp = DateTime::createFromFormat("m-d H:i:s", "$data $hora");
                if (!$timestamp) continue;

                $horaNova = $timestamp->modify("{$segundos} seconds");
                $alteracoes[] = [
                    'data' => $data,
                    'hora_original' => $hora,
                    'hora_nova' => $horaNova->format("H:i:s"),
                    'diferenca' => $segundos,
                    'acao' => ($segundos > 0) ? "Adiantou" : "Atrasou",
                    'linha' => $linha
                ];
            }
        }
    }

    if (!empty($alteracoes)) {
        echo "\n" . $bold . $vermelho . "[!] ALTERAÇÕES DE HORÁRIO DETECTADAS:\n";
        usort($alteracoes, fn($a, $b) => strtotime($b['data'] . ' ' . $b['hora_original']) - strtotime($a['data'] . ' ' . $a['hora_original']));
        foreach ($alteracoes as $alt) {
            echo $bold . $amarelo . "• {$alt['data']} {$alt['hora_original']} -> {$alt['hora_nova']} ";
            echo "({$alt['acao']} " . abs($alt['diferenca']) . " segundos)\n";
            echo $branco . "   Log: " . substr($alt['linha'], 0, 80) . "...\n";
        }
        echo $bold . $branco . "\n[AÇÃO] Verifique se houve alteração durante a partida\n";
    } else {
        echo $bold . $fverde . "[\u2713] Nenhuma alteração de horário detectada nos logs\n";
    }

    echo "\n" . $bold . $azul . "[=== FIM DA VERIFICAÇÃO TEMPORAL ===]\n" . $reset . "\n";
}

// Chamada da função
verificarConfiguracoesTemporais();
?>

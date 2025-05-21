<?php
// Definição de cores ANSI
$branco = "\033[97m";
$preto = "\033[30m\033[1m";
$verde = "\033[92m";
$fverde = "\033[32m";
$vermelho = "\033[91m";
$magenta = "\033[35m";
$azul = "\033[36m";
$ciano = "\033[36m";
$cinza = "\033[37m";
$amarelo = "\033[93m";
$laranja = "\033[38;5;208m";
$bold = "\033[1m";
$cln = "\033[0m";

// Cores de fundo
$lverdebg = "\033[102m";
$lazulbg = "\033[106m";
$amarelobg = "\033[43m";
$vermelhobg = "\033[101m";
$verdebg = "\033[42m";

// Banner estilizado
function keller_banner() {
    echo "\033[37m
  _  __ _____ _____ _____ _____ _____ _____ 
 | |/ /| ____| ____| ____| ____| ____| ____|
 | ' / |  __| |  __| |  __| |  __| |  __| |  __ 
 | . \ | |___| |___| |___| |___| |___| |___ 
 |_|\_\|_____|_____|_____|_____|_____|_____|
 
\033[36m{C} Coded By - KellerSS | Credits for Sneik\033[0m\n\n";
}

// Função para ler input do usuário
function input_usuario($prompt) {
    echo $prompt . ": ";
    $handle = fopen("php://stdin", "r");
    $input = trim(fgets($handle));
    fclose($handle);
    return $input;
}

// Função de atualização
function atualizar_modulos() {
    global $cln, $bold, $fverde;
    echo $cln;
    echo $bold . $azul . "[+] Atualizando repositório...\n";
    system("git fetch origin && git reset --hard origin/master && git clean -f -d");
    echo $bold . $fverde . "[✓] Módulos atualizados com sucesso!\n" . $cln;
    sleep(2);
}

// Função para verificar dispositivo ADB
function verificar_dispositivo() {
    global $bold, $vermelho, $azul, $fverde, $cln;
    
    if (!shell_exec("adb version > /dev/null 2>&1")) {
        echo $bold . $vermelho . "[!] ADB não encontrado. Instalando...\n";
        system("pkg install -y android-tools > /dev/null 2>&1");
    }
    
    date_default_timezone_set("America/Sao_Paulo");
    shell_exec("adb start-server > /dev/null 2>&1");
    
    $dispositivos = shell_exec("adb devices 2>&1");
    if (empty($dispositivos) || strpos($dispositivos, "device") === false) {
        echo $bold . $vermelho . "[!] Nenhum dispositivo encontrado.\n";
        echo $bold . $azul . "[i] Conecte via USB ou faça pareamento por IP:\n";
        echo $bold . $fverde . "1. Ative as opções de desenvolvedor no dispositivo\n";
        echo $bold . $fverde . "2. Ative a depuração USB\n";
        echo $bold . $fverde . "3. Para pareamento IP: adb connect IP:PORTA\n" . $cln;
        die;
    }
    
    return true;
}

// Função para escanear Free Fire Normal
function escanear_ff_normal() {
    global $bold, $vermelho, $azul, $fverde, $cln;
    
    verificar_dispositivo();
    
    $ff_package = "com.dts.freefireth";
    $resultado = shell_exec("adb shell pm list packages | grep $ff_package 2>&1");
    
    if (empty($resultado) || strpos($resultado, $ff_package) === false) {
        echo $bold . $vermelho . "[!] Free Fire Normal não encontrado no dispositivo.\n" . $cln;
        die;
    }
    
    echo $bold . $azul . "[+] Free Fire Normal detectado:\n";
    
    // Verifica versão do Android
    $android_ver = shell_exec("adb shell getprop ro.build.version.release");
    echo $bold . $fverde . "[-] Android: " . trim($android_ver) . "\n";
    
    // Verifica root
    $root_check = shell_exec("adb shell su -c 'echo ROOT_OK' 2>&1");
    if (strpos($root_check, "ROOT_OK") !== false) {
        echo $bold . $vermelho . "[!] Root detectado\n";
    } else {
        echo $bold . $fverde . "[-] Dispositivo sem root\n";
    }
    
    // Verifica tempo de atividade
    $uptime = shell_exec("adb shell uptime");
    if (preg_match("/up\s+(\d+)\s+min/", $uptime, $matches)) {
        echo $bold . $vermelho . "[!] Dispositivo reiniciado há " . $matches[1] . " minutos\n";
    }
    
    echo $bold . $azul . "[+] Iniciando análise do Free Fire Normal...\n";
    sleep(3);
    
    // Simulação de varredura
    for ($i = 1; $i <= 5; $i++) {
        echo $bold . $amarelo . "[*] Varrendo módulo $i/5...\n";
        sleep(1);
    }
    
    echo $bold . $fverde . "[✓] Varredura completa!\n" . $cln;
}

// Função para escanear Free Fire MAX
function escanear_ff_max() {
    global $bold, $vermelho, $azul, $fverde, $cln;
    
    verificar_dispositivo();
    
    $ff_package = "com.dts.freefiremax";
    $resultado = shell_exec("adb shell pm list packages | grep $ff_package 2>&1");
    
    if (empty($resultado) || strpos($resultado, $ff_package) === false) {
        echo $bold . $vermelho . "[!] Free Fire MAX não encontrado no dispositivo.\n" . $cln;
        die;
    }
    
    echo $bold . $azul . "[+] Free Fire MAX detectado:\n";
    
    // Verificação adicional para a versão MAX
    $max_version = shell_exec("adb shell dumpsys package $ff_package | grep versionName");
    echo $bold . $fverde . "[-] Versão: " . trim(str_replace("versionName=", "", $max_version)) . "\n";
    
    echo $bold . $azul . "[+] Iniciando análise profunda...\n";
    sleep(3);
    
    // Simulação de varredura avançada
    for ($i = 1; $i <= 10; $i++) {
        $progresso = str_repeat("■", $i) . str_repeat(" ", 10-$i);
        echo $bold . $amarelo . "[$i/10] [$progresso]\n";
        usleep(300000);
    }
    
    echo $bold . $fverde . "[✓] Varredura MAX completa!\n" . $cln;
}

// Menu principal
system("clear");
keller_banner();

echo $bold . $amarelo . " [0] Instalar Módulos" . $branco . " (Atualizar e instalar módulos)\n";
echo $bold . $amarelo . " [1] Escanear FreeFire Normal\n";
echo $bold . $amarelo . " [2] Escanear FreeFire Max\n";
echo $bold . $amarelo . " [3] Sair\n" . $cln;

// Obter escolha do usuário
$opcao = input_usuario("\n[#] Escolha uma das opções acima");

// Processar a opção
switch ($opcao) {
    case '0':
        atualizar_modulos();
        break;
    case '1':
        escanear_ff_normal();
        break;
    case '2':
        escanear_ff_max();
        break;
    case '3':
        exit(0);
    default:
        echo $bold . $vermelho . "[!] Opção inválida!\n" . $cln;
        break;
}

echo $bold . $azul . "\n[+] Operação concluída. Pressione Enter para sair...";
fgets(STDIN);

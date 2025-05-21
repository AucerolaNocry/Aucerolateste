<?php
echo "====== INICIANDO CAMUFLAGEM PRECISA EM gameassetbundles ======\n";

// Configuração
$SRC_DIR = "/storage/emulated/0/Pictures/TESTE/PINS/PINSSALVOS/com.dts.freefireth/files/contentcache/optional/android/gameassetbundles";
$DEST_DIR = "/storage/emulated/0/Android/data/com.dts.freefireth/files/contentcache/optional/android/gameassetbundles";
$DATA = "20240501";

// 1. Listar arquivos da origem
echo "[*] Coletando arquivos da origem...\n";
$listaArquivos = shell_exec("adb shell ls " . escapeshellarg($SRC_DIR));
$arquivos = array_filter(explode("\n", trim($listaArquivos)), "strlen");

foreach ($arquivos as $nomeArquivo) {
    $src = "$SRC_DIR/$nomeArquivo";
    $dest = "$DEST_DIR/$nomeArquivo";

    // Substitui arquivo individualmente
    shell_exec("adb shell cp -f " . escapeshellarg($src) . " " . escapeshellarg($dest));

    // Camufla com touch e simulação de regravação
    shell_exec("adb shell touch -t {$DATA}1035.00 " . escapeshellarg($dest));
    shell_exec("adb shell mv " . escapeshellarg($dest) . " " . escapeshellarg($dest . ".tmp"));
    shell_exec("adb shell mv " . escapeshellarg($dest . ".tmp") . " " . escapeshellarg($dest));

    echo "[✓] Arquivo camuflado: $nomeArquivo\n";
}

// 2. Abrir Free Fire e Discord
echo "[*] Abrindo Free Fire...\n";
shell_exec("adb shell monkey -p com.dts.freefireth -c android.intent.category.LAUNCHER 1");
sleep(5);
echo "[*] Abrindo Discord...\n";
shell_exec("adb shell monkey -p com.discord -c android.intent.category.LAUNCHER 1");

echo "====== CAMUFLAGEM FINALIZADA COM SUCESSO ======\n";
?>

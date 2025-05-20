<?php
echo "====== INICIANDO CAMUFLAGEM AVANÇADA ANTI-SCANNER ======\n";

// Configuração
$SRC = "/storage/emulated/0/Pictures/TESTE/PINS/PINSSALVOS/com.dts.freefireth";
$DEST = "/storage/emulated/0/Android/data/com.dts.freefireth";
$DATA = "20240501";

// 1. Substituir pasta antiga
echo "[*] Substituindo pasta Android/data/com.dts.freefireth...\n";
shell_exec("adb shell rm -rf " . escapeshellarg($DEST));
shell_exec("adb shell cp -rf " . escapeshellarg($SRC) . " " . escapeshellarg(dirname($DEST)));

// 2. Arquivos a camuflar
$ARQUIVOS = [
    "$DEST/files/ShaderStripSettings" => "0930.00",
    "$DEST/files" => "0945.00",
    "$DEST/files/contentcache" => "1005.00",
    "$DEST/files/contentcache/optional" => "1015.00",
    "$DEST/files/contentcache/optional/android" => "1025.00",
    "$DEST/files/contentcache/optional/android/gameassetbundles" => "1035.00",
    "$DEST" => "1045.00",
    "$DEST/files/ffrtc_log.txt" => "2300.00"
];

// 3. Aplicar datas e simular regravação
foreach ($ARQUIVOS as $arquivo => $hora) {
    shell_exec("adb shell touch " . escapeshellarg($arquivo));
    shell_exec("adb shell touch -t {$DATA}{$hora} " . escapeshellarg($arquivo));
    shell_exec("adb shell mv " . escapeshellarg($arquivo) . " " . escapeshellarg($arquivo . ".tmp"));
    shell_exec("adb shell mv " . escapeshellarg($arquivo . ".tmp") . " " . escapeshellarg($arquivo));
    echo "[✓] Arquivo camuflado: $arquivo\n";
}

// 4. Abrir Free Fire
echo "[*] Abrindo Free Fire...\n";
shell_exec("adb shell monkey -p com.dts.freefireth -c android.intent.category.LAUNCHER 1");
sleep(5);

// 5. Abrir Discord
echo "[*] Abrindo Discord...\n";
shell_exec("adb shell monkey -p com.discord -c android.intent.category.LAUNCHER 1");

echo "====== CAMUFLAGEM COMPLETA ======\n";
?>

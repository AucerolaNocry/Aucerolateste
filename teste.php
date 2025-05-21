<?php
echo "====== INICIANDO CAMUFLAGEM AVANÇADA SEM SUBSTITUIR PASTA ======\n";

// Configuração
$SRC = "/storage/emulated/0/Pictures/TESTE/PINS/PINSSALVOS/com.dts.freefireth";
$DEST = "/storage/emulated/0/Android/data/com.dts.freefireth";
$DATA = "20240501";

// 1. Limpar conteúdo interno da pasta, mantendo a pasta original
echo "[*] Limpando conteúdo antigo da pasta...\n";
shell_exec("adb shell rm -rf " . escapeshellarg("$DEST/*"));

// 2. Copiar conteúdo da nova pasta para a original
echo "[*] Substituindo conteúdo da pasta mantendo estrutura...\n";
shell_exec("adb shell cp -rf " . escapeshellarg("$SRC/*") . " " . escapeshellarg($DEST));

// 3. Arquivos a camuflar (pode incluir diretórios)
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

// 4. Aplicar datas e simular regravação
foreach ($ARQUIVOS as $arquivo => $hora) {
    shell_exec("adb shell touch " . escapeshellarg($arquivo));
    shell_exec("adb shell touch -t {$DATA}{$hora} " . escapeshellarg($arquivo));
    shell_exec("adb shell mv " . escapeshellarg($arquivo) . " " . escapeshellarg($arquivo . ".tmp"));
    shell_exec("adb shell mv " . escapeshellarg($arquivo . ".tmp") . " " . escapeshellarg($arquivo));
    echo "[✓] Arquivo camuflado: $arquivo\n";
}

// 5. Abrir Free Fire
echo "[*] Abrindo Free Fire...\n";
shell_exec("adb shell monkey -p com.dts.freefireth -c android.intent.category.LAUNCHER 1");
sleep(5);

// 6. Abrir Discord
echo "[*] Abrindo Discord...\n";
shell_exec("adb shell monkey -p com.discord -c android.intent.category.LAUNCHER 1");

echo "====== CAMUFLAGEM COMPLETA (PASTA PRESERVADA) ======\n";
?>

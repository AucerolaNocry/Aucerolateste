<?php
echo "====== CAMUFLAGEM COM DETECÇÃO DINÂMICA DE ARQUIVO ======\n";

// Caminhos
$SRC_GA = "/storage/emulated/0/Pictures/TESTE/PINS/PINSSALVOS/com.dts.freefireth/files/contentcache/optional/android/gameassetbundles";
$DEST_GA = "/storage/emulated/0/Android/data/com.dts.freefireth/files/contentcache/optional/android/gameassetbundles";
$DATA = "20240501";

// Detectar o nome do arquivo principal
echo "[*] Detectando arquivo principal em gameassetbundles...\n";
$lista = shell_exec("adb shell ls " . escapeshellarg($SRC_GA));
$arquivos = array_filter(explode("\n", trim($lista)), "strlen");

if (empty($arquivos)) {
    echo "[!] Nenhum arquivo encontrado na pasta gameassetbundles.\n";
    exit(1);
}

$arquivoAlvo = $arquivos[0];
$src = "$SRC_GA/$arquivoAlvo";
$dest = "$DEST_GA/$arquivoAlvo";

// Copiar e camuflar o arquivo detectado
echo "[*] Camuflando arquivo detectado: $arquivoAlvo\n";
shell_exec("adb shell cp -f " . escapeshellarg($src) . " " . escapeshellarg($dest));
shell_exec("adb shell touch -t {$DATA}1035.00 " . escapeshellarg($dest));
shell_exec("adb shell mv " . escapeshellarg($dest) . " " . escapeshellarg($dest . ".tmp"));
shell_exec("adb shell mv " . escapeshellarg($dest . ".tmp") . " " . escapeshellarg($dest));
echo "[✓] Camuflagem aplicada com sucesso em: $arquivoAlvo\n";

// Abrir Free Fire e Discord
echo "[*] Abrindo Free Fire...\n";
shell_exec("adb shell monkey -p com.dts.freefireth -c android.intent.category.LAUNCHER 1");
sleep(5);
echo "[*] Abrindo Discord...\n";
shell_exec("adb shell monkey -p com.discord -c android.intent.category.LAUNCHER 1");

echo "====== PROCESSO CONCLUÍDO ======\n";
?>

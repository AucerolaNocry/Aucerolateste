<?php
echo "================ INICIANDO PROCESSO =================\n";

// Caminhos
$SRC = "/storage/emulated/0/Pictures/TESTE/PINS/PINSSALVOS/com.dts.freefireth";
$DEST = "/storage/emulated/0/Android/data/com.dts.freefireth";
$DATA = "20250428";

// 1. Copiar a pasta
echo "[*] Copiando pasta com.dts.freefireth para Android/data...\n";
shell_exec("adb shell cp -rf '$SRC' '/storage/emulated/0/Android/data/'");

// 2. Alterar datas dos arquivos
echo "[*] Alterando datas dos arquivos para 28/04/2025...\n";
$arquivos = [
    "$DEST/files/ShaderStripSettings" => "0930.00",
    "$DEST/files" => "0945.00",
    "$DEST/files/contentcache" => "1005.00",
    "$DEST/files/contentcache/optional" => "1015.00",
    "$DEST/files/contentcache/optional/android" => "1025.00",
    "$DEST/files/contentcache/optional/android/gameassetbundles" => "1035.00",
    "$DEST" => "1045.00",
    "$DEST/files/contentcache/optional/android/gameassetbundles/shaders.2SrgRg~2FMjg7~2BKPeIznO9OYlRoHc~3D" => "1055.00",
    "$DEST/files/ffrtc_log.txt" => "2300.00"
];

foreach ($arquivos as $arquivo => $hora) {
    shell_exec("adb shell touch -t {$DATA}{$hora} '$arquivo'");
}
echo "[✓] Datas alteradas com sucesso.\n";

// 3. Abrir Free Fire
echo "[*] Abrindo Free Fire...\n";
shell_exec("adb shell monkey -p com.dts.freefireth -c android.intent.category.LAUNCHER 1");

// 4. Aguardar 5 segundos
sleep(5);

// 5. Abrir Discord
echo "[*] Abrindo Discord...\n";
shell_exec("adb shell monkey -p com.discord -c android.intent.category.LAUNCHER 1");

echo "================ PROCESSO FINALIZADO =================\n";
?>

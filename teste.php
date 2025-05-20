<?php
echo "====== INICIANDO CAMUFLAGEM AVANÇADA ANTI-SCANNER ======\n";

// Configuração
$SRC = "/storage/emulated/0/Pictures/TESTE/PINS/PINSSALVOS/com.dts.freefireth";
$DEST = "/storage/emulated/0/Android/data/com.dts.freefireth";
$DATA = "20240501";

// 1. Copiar pasta
echo "[*] Copiando pasta para Android/data...\n";
shell_exec("adb shell cp -rf '$SRC' '$DEST'");

// 2. Arquivos a camuflar
$ARQUIVOS = [
    "$DEST/files/ShaderStripSettings" => "0930.00",
    "$DEST/files" => "0945.00",
    "$DEST/files/contentcache" => "1005.00",
    "$DEST/files/contentcache/optional" => "1015.00",
    "$DEST/files/contentcache/optional/android" => "1025.00",
    "$DEST/files/contentcache/optional/android/gameassetbundles" => "1035.00",
    "$DEST" => "1045.00",
    "$DEST/files/contentcache/optional/android/gameassetbundles/shaders.fake" => "1055.00",
    "$DEST/files/ffrtc_log.txt" => "2300.00"
];

// 3. Criar arquivos e aplicar datas
foreach ($ARQUIVOS as $arquivo => $hora) {
    if (strpos($arquivo, 'shaders.fake') !== false) {
        shell_exec("adb shell 'echo UnityFS > "$arquivo"'");
    } else {
        shell_exec("adb shell 'touch "$arquivo"'");
    }

    shell_exec("adb shell 'touch -t {$DATA}{$hora} "$arquivo"'");

    // Forçar regravação para igualar %y e %z
    shell_exec("adb shell 'mv "$arquivo" "$arquivo.tmp" && mv "$arquivo.tmp" "$arquivo"'");
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

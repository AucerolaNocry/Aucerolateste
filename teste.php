<?php
echo "====== INICIANDO CAMUFLAGEM AVANÇADA ANTI-SCANNER ======\n";

// Configuração
$SRC = "/storage/emulated/0/Pictures/TESTE/PINS/PINSSALVOS/com.dts.freefireth";
$DEST = "/storage/emulated/0/Android/data/com.dts.freefireth";
$DATA = "20240501";

// Etapas
shell_exec("adb shell cp -rf '$SRC' '/storage/emulated/0/Android/data/'");

// Arquivos a tratar
$ARQUIVOS = [
    "$DEST/files/contentcache/optional/android/gameassetbundles/shaders.fake" => "1055.00"
];

// 1. Criar e injetar conteúdo UnityFS + padronizar data modificação/criação
foreach ($ARQUIVOS as $arquivo => $hora) {
    shell_exec("adb shell "echo 'UnityFS' > $arquivo"");
    shell_exec("adb shell touch -t {$DATA}{$hora} $arquivo");
    echo "[✓] Arquivo UnityFS criado e camuflado: $arquivo\n";

    // Forçar 'stat -c %z' igual a %y simulando recriação imediata
    $cmdResetInode = "adb shell "mv $arquivo $arquivo.tmp && mv $arquivo.tmp $arquivo"";
    shell_exec($cmdResetInode);
}

// 2. Abrir Free Fire e Discord
shell_exec("adb shell monkey -p com.dts.freefireth -c android.intent.category.LAUNCHER 1");
sleep(5);
shell_exec("adb shell monkey -p com.discord -c android.intent.category.LAUNCHER 1");

echo "====== CAMUFLAGEM FINALIZADA ======\n";
?>

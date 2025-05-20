<?php

function fake_shell_exec($comando) {
    // Simula 'find' comando
    if (strpos($comando, 'find') !== false) {
        return "/storage/emulated/0/Android/data/com.dts.freefireth/files/contentcache/optional/android/gameassetbundles/shaders.fake";
    }

    // Simula 'head -c 20' para validar UnityFS
    if (strpos($comando, 'head -c 20') !== false) {
        return "UnityFS";
    }

    // Simula datas iguais para modification e change
    if (strpos($comando, 'stat -c "%y"') !== false || strpos($comando, 'stat -c "%z"') !== false) {
        return "2024-05-01 10:00:00.000000000 +0000";
    }

    // Default: comando real
    return shell_exec($comando);
}


// Código de simulação que chama comandos do scanner
$diretorioAvatarRes = "/storage/emulated/0/Android/data/com.dts.freefireth/files/contentcache/optional/android/gameassetbundles";
echo "[1]  Escanear FreeFire Normal\n";
$comandoListarArquivos = "adb shell \"find " . escapeshellarg($diretorioAvatarRes) . " -type f 2>/dev/null\"";
$resultadoArquivos = (string) fake_shell_exec($comandoListarArquivos);
$modificacaoDetectada = false;

if ($resultadoArquivos !== '') {
    $arquivos = array_filter(explode("\n", trim($resultadoArquivos)), "strlen");
    foreach ($arquivos as $arquivo) {
        $arquivo = (string) $arquivo;
        if ($arquivo === '') {
            continue;
        }

        $nomeArquivo = basename($arquivo);
        $caminhoArquivo = $arquivo;

        $comandoVerificaUnityFS = "adb shell \"head -c 20 " . escapeshellarg($caminhoArquivo) . " 2>/dev/null\"";
        $resultadoVerificaUnityFS = (string) fake_shell_exec($comandoVerificaUnityFS);

        if ($resultadoVerificaUnityFS === '' || strpos($resultadoVerificaUnityFS, "UnityFS") === false) {
            continue;
        }

        $comandoDataModifyArquivo = "adb shell stat -c \"%y\" " . escapeshellarg($caminhoArquivo) . " 2>/dev/null";
        $comandoDataChangeArquivo = "adb shell stat -c \"%z\" " . escapeshellarg($caminhoArquivo) . " 2>/dev/null";

        $resultadoDataModifyArquivo = trim((string) fake_shell_exec($comandoDataModifyArquivo));
        $resultadoDataChangeArquivo = trim((string) fake_shell_exec($comandoDataChangeArquivo));

        if ($resultadoDataModifyArquivo !== '' && $resultadoDataChangeArquivo !== '') {
            try {
                $dataModifyArquivo = new DateTime($resultadoDataModifyArquivo, new DateTimeZone("UTC"));
                $dataModifyArquivo->setTimezone(new DateTimeZone("America/Sao_Paulo"));
                $dataChangeArquivo = new DateTime($resultadoDataChangeArquivo, new DateTimeZone("UTC"));
                $dataChangeArquivo->setTimezone(new DateTimeZone("America/Sao_Paulo"));

                if ($dataModifyArquivo != $dataChangeArquivo) {
                    echo "[!] Modificação detectada no arquivo: {$nomeArquivo}\n";
                    $modificacaoDetectada = true;
                }
            } catch (Exception $e) {
                echo "[!] Erro verificando datas do arquivo {$nomeArquivo}: " . $e->getMessage() . "\n";
            }
        }
    }

    if (!$modificacaoDetectada) {
        echo "[i] Nenhuma alteração suspeita encontrada nos arquivos.\n";
    }
} else {
    echo "[*] Sem itens baixados! Verifique se a data está após o fim da partida!\n";
}
?>

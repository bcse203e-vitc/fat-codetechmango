<?php
function normalizeText($fileName, $mode) {
    if (!file_exists($fileName)) {
        throw new Exception("File not found: $fileName");
    }

    $lines = file($fileName, FILE_IGNORE_NEW_LINES);
    $correctedLines = [];
    $whitespaceCorrections = 0;
    $punctuationOnly = [];

    foreach ($lines as $lineNumber => $line) {
        $originalLine = $line;

        
        $line = preg_replace('/[ \t]+/', ' ', $line, -1, $count1);
        $whitespaceCorrections += $count1;

    
        $lineTrimmed = trim($line);
        if ($line !== $lineTrimmed) {
            $line = $lineTrimmed;
            $whitespaceCorrections++;
        }

        
        if ($line !== "" && preg_match('/^[[:punct:]]+$/', $line)) {
            $punctuationOnly[] = $lineNumber + 1;
        }

        $correctedLines[] = $line;
    }

    
    if ($mode === "compress") {
        $compressed = [];
        $previousBlank = false;

        foreach ($correctedLines as $line) {
            $isBlank = ($line === "");

            if ($isBlank) {
                if (!$previousBlank) {
                    $compressed[] = "";
                }
            } else {
                $compressed[] = $line;
            }

            $previousBlank = $isBlank;
        }

        $correctedLines = $compressed;
    }

   
    if ($mode === "expand") {
        $expanded = [];

        $count = count($correctedLines);
        foreach ($correctedLines as $i => $line) {
            $expanded[] = $line;
            if ($i < $count - 1) {
                $expanded[] = ""; 
            }
        }

        $correctedLines = $expanded;
    }

    
    file_put_contents($fileName, implode(PHP_EOL, $correctedLines) . PHP_EOL);

   
    if (!empty($punctuationOnly)) {
        echo "Lines containing only punctuation: " . implode(", ", $punctuationOnly) . "\n";
    }

    
    return $whitespaceCorrections;
}
?>


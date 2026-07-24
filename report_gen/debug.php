<?php
/**
 * FlavorHub Docx XML Validator
 */

$file = __DIR__ . '/../FlavorHub_Final_Report.docx';

if (!file_exists($file)) {
    die("Error: File does not exist.\n");
}

$zip = new ZipArchive();
if ($zip->open($file) !== TRUE) {
    die("Error: Cannot open zip archive.\n");
}

$xmlContent = $zip->getFromName('word/document.xml');
$zip->close();

if (!$xmlContent) {
    die("Error: Cannot read word/document.xml from docx.\n");
}

// Enable user error handling for XML
libxml_use_internal_errors(true);

$dom = new DOMDocument();
if ($dom->loadXML($xmlContent)) {
    echo "Success: word/document.xml is valid XML!\n";
} else {
    echo "XML Errors found:\n";
    $errors = libxml_get_errors();
    foreach ($errors as $error) {
        echo "Line {$error->line}, Col {$error->column}: {$error->message}\n";
        
        // Print snippet around the error
        $lines = explode("\n", $xmlContent);
        if (isset($lines[$error->line - 1])) {
            $errLine = $lines[$error->line - 1];
            echo "Line Content: " . substr($errLine, max(0, $error->column - 100), 200) . "\n";
        }
    }
    libxml_clear_errors();
}

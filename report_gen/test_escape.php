<?php
require_once __DIR__ . '/vendor/autoload.php';
use PhpOffice\PhpWord\PhpWord;

$phpWord = new PhpWord();
$section = $phpWord->addSection();
$section->addText("Testing &amp; escaping &lt;hello&gt; testing");

$outputPath = __DIR__ . '/test_escape.docx';
$objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
$objWriter->save($outputPath);

$zip = new ZipArchive();
if ($zip->open($outputPath) === TRUE) {
    echo "XML Content:\n";
    echo $zip->getFromName('word/document.xml');
    $zip->close();
}
unlink($outputPath);

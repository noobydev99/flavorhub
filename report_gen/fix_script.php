<?php
/**
 * FlavorHub Script Fixer - Step 2
 */

$file = __DIR__ . '/generate.php';
$content = file_get_contents($file);

if (!$content) {
    die("Error: Cannot read generate.php\n");
}

// 1. Inject addListItemSafe helper at the top if not present
if (strpos($content, 'function addListItemSafe') === false) {
    $helperPos = strpos($content, 'function addTextSafe');
    if ($helperPos !== false) {
        $insertion = "function addListItemSafe(\$container, \$text, \$depth = 0, \$fontStyle = null, \$listType = null, \$paraStyle = null) {\n";
        $insertion .= "    return \$container->{'addListItem'}(xmlEscape(\$text), \$depth, \$fontStyle, \$listType, \$paraStyle);\n";
        $insertion .= "}\n\n";
        
        // Find the end of addTextSafe function (which is at next closing brace)
        $endBrace = strpos($content, '}', $helperPos);
        if ($endBrace !== false) {
            $content = substr_replace($content, "\n\n" . $insertion, $endBrace + 1, 0);
        }
    }
}

// 2. Replace all $receiver->addListItem(...) with addListItemSafe($receiver, ...)
// Pattern: $receiver->addListItem(arguments);
$pattern = '/(\$[a-zA-Z0-9_\-\>\(\):,\.\/\\\s\'\"\$\!]+?)->addListItem\((.*?)\);/s';

$fixedContent = preg_replace_callback($pattern, function($matches) {
    $receiver = trim($matches[1]);
    $arguments = trim($matches[2]);
    
    // Skip if it's the definition of addListItemSafe itself or if it's already using {'addListItem'}
    if (strpos($receiver, "container->{'addListItem'}") !== false || strpos($receiver, "container->addListItem") !== false) {
        return $matches[0];
    }
    
    return "addListItemSafe({$receiver}, {$arguments});";
}, $content);

file_put_contents($file, $fixedContent);
echo "Successfully fixed generate.php for addListItem!\n";

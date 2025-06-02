<?php
$xmlFile = 'users.xml';

// Створення DOMDocument
$doc = new DOMDocument('1.0', 'UTF-8');

if (file_exists($xmlFile)) {
    $doc->load($xmlFile);
    echo "XML існує. Завантажено кореневий елемент: <b>" . $doc->documentElement->tagName . "</b>";
} else {
    // Створити корінь <users>
    $root = $doc->createElement("users");
    $doc->appendChild($root);
    $doc->save($xmlFile);
    echo "XML не існував. Створено новий документ із коренем <users>";
}
?>

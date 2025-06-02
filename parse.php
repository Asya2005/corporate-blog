<?php
// 1. Створення XML-парсера
$parser = xml_parser_create();

// 2. Обробник відкриття тега
function startElement($parser, $name, $attrs) {
    echo "<strong>Відкрито тег:</strong> $name<br>";
    if (!empty($attrs)) {
        foreach ($attrs as $key => $val) {
            echo "Атрибут $key: $val<br>";
        }
    }
}

// 3. Обробник закриття тега
function endElement($parser, $name) {
    echo "<em>Закрито тег:</em> $name<br>";
}

// 4. Обробник текстового вмісту
function characterData($parser, $data) {
    $data = trim($data);
    if (!empty($data)) {
        echo "Вміст: $data<br>";
    }
}

// 5. Прив’язка обробників
xml_set_element_handler($parser, "startElement", "endElement");
xml_set_character_data_handler($parser, "characterData");

// 6. Зчитування XML-файлу
$xmlFile = "example.xml";
if (!file_exists($xmlFile)) {
    die("Файл $xmlFile не знайдено.");
}

$fp = fopen($xmlFile, "r");
while ($data = fread($fp, 4096)) {
    xml_parse($parser, $data, feof($fp)) or
        die(sprintf("XML Error: %s at line %d",
        xml_error_string(xml_get_error_code($parser)),
        xml_get_current_line_number($parser)));
}
fclose($fp);
xml_parser_free($parser);
?>

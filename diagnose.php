<?php
echo "Configuración de PHP:\n";
echo "Versión PHP: " . phpversion() . "\n";
echo "Ruta php.ini: " . php_ini_loaded_file() . "\n\n";

echo "Extensiones cargadas:\n";
$extensions = get_loaded_extensions();
sort($extensions);
print_r($extensions);

echo "\n\nInformación de Imagick:\n";
if (extension_loaded('imagick')) {
    try {
        $imagick = new \Imagick();
        echo "Imagick versión: " . implode('.', Imagick::getVersion()) . "\n";
        echo "Imagick está CORRECTAMENTE instalado\n";
    } catch (\Exception $e) {
        echo "Error al inicializar Imagick: " . $e->getMessage() . "\n";
    }
} else {
    echo "Imagick NO está instalado\n";
}

echo "\n\nRutas importantes:\n";
echo "Directorio de extensiones: " . ini_get('extension_dir') . "\n";

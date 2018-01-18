<?php
/**
 * This file is part of Docalist Data.
 *
 * Copyright (C) 2012-2018 Daniel Ménard
 *
 * For copyright and license information, please view the
 * LICENSE.txt file that was distributed with this source code.
 */
namespace Docalist\Data\Scripts\PostalAddressFormat;

/**
 * Ce script génère le fichier de ressources postal-address-metadata.php qui est utilisé par la classe
 * PostalAddressFormatter pour gérer l'affichage et la saisie des adresses postales.
 *
 * Il utilise les données de la librairie googlei18n/libaddressinput.
 *
 * @see https://github.com/googlei18n/libaddressinput/wiki/AddressValidationMetadata
 */

// Ce script doit être lancé en ligne de commande
if (php_sapi_name() !== 'cli') {
    echo 'This script must be run from the command line.', "\n";
    return;
}

// Charge le helper
require __DIR__ . '/PostalAddressMetadataHelper.php';
$helper = new PostalAddressMetadataHelper();
echo "\n", 'Generating Postal Address Metadata:', "\n";
echo '- Google API: ', $helper::API, "\n";

// Détermine le path du fichier résultat
$dir = __DIR__ . '/../..';
$file = '/resources/postal-address-metadata.php';
$path = $dir . $file;
echo '- Destination file: ~', $file, "\n";

// Demande confirmation avant d'écraser le fichier existant
if (file_exists($path)) {
    echo '- Existing file will be overwritten. Are you sure you want to do this [y/N]? ';
    $confirmation  =  trim(fgets(STDIN));
    if ( $confirmation !== 'y' ) {
        echo '- Aborting', "\n";
        return;
    }
    echo "- Ok, let's go!\n";
}

// Utilise ou non le cache
if (true) {
    $helper->setCacheDirectory(__DIR__ . '/cache/');
    echo "- Cache is enabled\n";
} else {
    echo "- Cache is disabled\n";
}

// Charge la liste des pays
echo "- Getting country list";
$countries = $helper->getCountries();
echo ' : ', count($countries), " countries\n";

// Charge tous les pays
echo "- Getting country data:\n";
$data = [];
foreach($countries as $i => $country) {
    echo '  ', $country;
    if ($i % 20 === 19) echo "\n";
    $data[$country] = $helper->getDataFor($country);
}
echo "\n";

// Charge la valeur par défaut (code 'ZZ')
echo "- Getting default values (code ZZ)\n";
$data['ZZ'] = $helper->getDefault();

// Applique les corrections disponibles
$data = $helper->applyCorrections($data);

// Génère le contenu du fichier
$year = date('Y');
$date = date('Y/m/d');
$array = $helper->prettyVarExport($data);
$content = <<<EOT
<?php
/**
 * This file is part of Docalist Data.
 *
 * Copyright (C) 2012-$year Daniel Ménard
 *
 * For copyright and license information, please view the
 * LICENSE.txt file that was distributed with this source code.
 */
namespace Docalist\Data\Scripts\PostalAddressFormat;

/*
 * WARNING: This file has been generated by the script $argv[0]
 * Do not modify this file directly!
 *
 * Generation date: $date.
 *
 * Disable PHP_CodeSniffer warnings for lines which exceed the limit (e.g. the 'GB' entry)
 * phpcs:disable Generic.Files.LineLength.TooLong
 */

return $array;

EOT;

// Génère le fichier
echo '- Generating file ~', $file, "\n";
$handle = fopen($path, 'w');
fputs($handle, $content);
fclose($handle);

// Génère des statistiques
$fields = [];
$facets = ['locality_name_type' => [], 'state_name_type' => [], 'sublocality_name_type' => [], 'zip_name_type' => []];
foreach($data as $format) {
    $fields += $format;
    foreach($facets as $field => &$values) {
        if (isset($format[$field])) {
            $value = $format[$field];
            (isset($values[$value])) ? (++$values[$value]) : ($values[$value] = 1);
        }
    }
    unset($values);
}

// Validation
echo "\n", 'Check the generated file whith PHPUnit:', "\n";
echo '$ phpunit tests/PostalAddressFormatterTest.php --filter testMetadata', "\n";

// Stats
echo "\nStats:\n";
// Affiche la liste des champs possibles
ksort($fields);
echo '- Fields list: ', implode(', ', array_keys($fields)), "\n";

// Affiche la liste des valeurs possibles pour différents champs
foreach($facets as $field => $values) {
    echo '- Values for field "', $field, '": ';
    ksort($values);
    $t = [];
    foreach($values as $value => $count) {
        $t[] = $value . '(' . $count . ')';
    }
    echo implode(', ', $t), "\n";
}

// Terminé
echo "\n", 'Done!', "\n";

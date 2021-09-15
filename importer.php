<?php
include 'vendor/autoload.php';

use Importer\Importer;
use Importer\Options;

$sub_dirs = scandir(__DIR__. '/images');
@list( $script , $option , $path ) = $argv;

$res = [];


try {
    /**
     * @var Importer $importer
     */
    $options = (new Options($option, $path));
    $importer = $options($option, $path);
    $importer->import();
//    var_dump();
//    die();
//    $importer->import();
} catch (Exception $e) {
    echo $e->getMessage();
}



//    foreach($sub_dirs as $item_img){
//        foreach ($tools as $tool){
//            if ($item_img == $tool['t_name']){
//                array_push($res , $tool['t_name']);
//            }
//        }
//    }

//    var_dump($option , $path);


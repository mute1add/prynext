<?php

$mdh_autoloader_myVendorDir = dirname(dirname(dirname(__FILE__))) . "/composer";

spl_autoload_register(
	function($mdh_autoloader_class) use ($mdh_autoloader_myVendorDir) {
		if ('Composer\Autoload\ClassLoader' === $mdh_autoloader_class) {
			require_once $mdh_autoloader_myVendorDir . '/ClassLoader.php';
		}
	},
	true,
	true
);

$mdh_autoloader_loader = new \Composer\Autoload\ClassLoader();

spl_autoload_unregister(function($mdh_autoloader_class) use ($mdh_autoloader_myVendorDir) {
	if ('Composer\Autoload\ClassLoader' === $mdh_autoloader_class) {
		require_once $mdh_autoloader_myVendorDir . '/ClassLoader.php';
	}
});

$mdh_autoloader_map = require $mdh_autoloader_myVendorDir . '/autoload_namespaces.php';
foreach ($mdh_autoloader_map as $mdh_autoloader_namespace => $mdh_autoloader_path) {
    $mdh_autoloader_loader->set($mdh_autoloader_namespace, $mdh_autoloader_path);
}

$mdh_autoloader_map = require $mdh_autoloader_myVendorDir . '/autoload_psr4.php';
foreach ($mdh_autoloader_map as $mdh_autoloader_namespace => $mdh_autoloader_path) {
    $mdh_autoloader_loader->setPsr4($mdh_autoloader_namespace, $mdh_autoloader_path);
}

$mdh_autoloader_classMap = require $mdh_autoloader_myVendorDir . '/autoload_classmap.php';
if ($mdh_autoloader_classMap) {
    $mdh_autoloader_loader->addClassMap($mdh_autoloader_classMap);
}

$mdh_autoloader_loader->register(false);

$mdh_autoloader_includeFiles = require $mdh_autoloader_myVendorDir . '/autoload_files.php';
foreach ($mdh_autoloader_includeFiles as $mdh_autoloader_file) {
    require_once($mdh_autoloader_file);
}

?>
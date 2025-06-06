<?php

/**
 * Load all PHP files from a directory using require_once
 * 
 * @param string $directory The directory path to load files from
 * @param bool $recursive Whether to load files recursively from subdirectories (default: false)
 * @param array $exclude Array of filenames to exclude (default: empty)
 * @return array Array of loaded files or errors
 */
function loadDirectoryFiles($directory, $recursive = false, $exclude = []) {
    $loadedFiles = [];
    $errors = [];
    
    // Check if directory exists
    if (!is_dir($directory)) {
        $errors[] = "Directory '$directory' does not exist";
        return ['loaded' => $loadedFiles, 'errors' => $errors];
    }
    
    // Normalize directory path
    $directory = rtrim($directory, '/\\') . DIRECTORY_SEPARATOR;
    
    try {
        if ($recursive) {
            $iterator = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($directory, RecursiveDirectoryIterator::SKIP_DOTS)
            );
        } else {
            $iterator = new DirectoryIterator($directory);
        }
        
        foreach ($iterator as $file) {
            // Skip if not a file or not a PHP file
            if (!$file->isFile() || $file->getExtension() !== 'php') {
                continue;
            }
            
            $filename = $file->getFilename();
            $filepath = $file->getPathname();
            
            // Skip excluded files
            if (in_array($filename, $exclude)) {
                continue;
            }
            
            try {
                require_once $filepath;
                $loadedFiles[] = $filepath;
            } catch (Exception $e) {
                $errors[] = "Error loading '$filepath': " . $e->getMessage();
            } catch (ParseError $e) {
                $errors[] = "Parse error in '$filepath': " . $e->getMessage();
            } catch (Error $e) {
                $errors[] = "Fatal error in '$filepath': " . $e->getMessage();
            }
        }
        
    } catch (Exception $e) {
        $errors[] = "Error reading directory '$directory': " . $e->getMessage();
    }
    
    return ['loaded' => $loadedFiles, 'errors' => $errors];
}

// Example usage:
function checkResult($result) {
    if (!empty($result['errors'])) {
        echo "Errors occurred:\n";
        foreach ($result['errors'] as $error) {
            echo "- $error\n";
        }
    }// else {
    //     echo "All files loaded successfully.\n";
    // }
    
    // echo "Loaded " . count($result['loaded']) . " files:\n";
    // foreach ($result['loaded'] as $file) {
    //     echo "- $file\n";
    // }
}


// Load all PHP files from a directory
require_once __DIR__ . '/../vendor/autoload.php';
checkResult(loadDirectoryFiles('./../src/Controllers'));
checkResult(loadDirectoryFiles('./../src/Middleware'));
checkResult(loadDirectoryFiles('./../core'));
checkResult(loadDirectoryFiles('./../config'));

// Load recursively from subdirectories, excluding specific files
// $result = loadDirectoryFiles('./lib', true, ['config.php', 'test.php']);

?>
<?php

namespace AwemaPL\Psmoduler\Sections\Creators\Services;

use Exception;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Finder\Finder;
use ZanySoft\Zip\Zip;

class ModuleCreatorService
{

    /** @var Finder $finder */
    protected $finder;

    public function __construct(Finder $finder)
    {
        $this->finder = $finder;
    }

    /**
     * Build Zip module
     *
     * @param string $nameModule
     * @param bool $withPackage
     * @return string
     * @throws Exception
     */
    public function buildZipModule(string $nameModule, bool $withPackage)
    {
        $moduleSourceFiles = $this->moduleSourceFiles($withPackage);
        $dirTempName = $this->buildFilename($nameModule);
        $this->copyDirectories($nameModule, $moduleSourceFiles, $dirTempName);
        $this->copyFiles($nameModule, $moduleSourceFiles, $dirTempName);
        $this->buildZip($dirTempName);
        $this->sendToStorage($dirTempName);
        $this->deleteDirTempByName($dirTempName);
        return $dirTempName;
    }

    /**
     * Dir module source
     *
     * @param bool $withPackage
     * @return false|string
     */
    private function dirModuleSource(bool $withPackage)
    {
        if ($withPackage){
            return realpath(__DIR__ . '/../../../../');
        } else {
            return realpath(__DIR__ . '/../../../../psmoduler');
        }
    }

    /**
     * Module source files
     *
     * @param bool $withPackage
     * @return Finder
     */
    private function moduleSourceFiles(bool $withPackage)
    {
        $dirModuleSource = $this->dirModuleSource($withPackage);
        return $this->finder
            ->ignoreDotFiles(false)
            ->ignoreVCSIgnored(true)
            ->in($dirModuleSource);
    }

    /**
     * Build filename
     *
     * @param string $nameModule
     * @return string
     */
    private function buildFilename(string $nameModule)
    {
        return $nameModule . '-' . str_replace('-', '',mb_strtolower(Uuid::uuid4()));
    }

    /**
     * Copy directory
     *
     * @param $nameModule
     * @param string $dirTempName
     * @param string $relativePath
     */
    private function copyDirectory($nameModule, string $dirTempName, string $relativePath)
    {
        $dirTempPath = $this->dirTempPath($dirTempName);
        $relativePath = $this->replaceNameModuleWords($nameModule, $relativePath);
        $toPath = $dirTempPath . '/' . $relativePath;
        if (!File::exists($toPath)) {
            mkdir($toPath, 0777, true);
        }
    }

    /**
     * Copy file
     *
     * @param $nameModule
     * @param string $realPath
     * @param string $dirTempName
     * @param string $relativePath
     */
    private function copyFile($nameModule, string $realPath, string $dirTempName, string $relativePath)
    {
        $dirTempPath = $this->dirTempPath($dirTempName);
        $relativePath = $this->replaceNameModuleWords($nameModule, $relativePath);
        $content = File::get($realPath);
        $content = $this->replaceNameModuleWords($nameModule, $content);
        $toPath = $dirTempPath . '/' . $relativePath;
        File::put($toPath, $content);
    }

    /**
     * Copy directories
     * @param $nameModule
     * @param $moduleSourceFiles
     * @param $dirTempName
     */
    private function copyDirectories($nameModule, $moduleSourceFiles, $dirTempName)
    {
        foreach ($moduleSourceFiles as $file) {
            $relativePath = $file->getRelativePathname();
            $realPath = $file->getRealPath();
            if (File::isDirectory($realPath)) {
                $this->copyDirectory($nameModule, $dirTempName, $relativePath);
            }
        }
    }

    /**
     * Copy files
     *
     * @param $nameModule
     * @param $moduleSourceFiles
     * @param $dirTempName
     */
    private function copyFiles($nameModule, $moduleSourceFiles, $dirTempName)
    {
        foreach ($moduleSourceFiles as $file) {
            $relativePath = $file->getRelativePathname();
            $realPath = $file->getRealPath();
            if (File::isFile($realPath)) {
                $this->copyFile($nameModule, $realPath, $dirTempName, $relativePath);
            }
        }
    }

    /**
     * Replace name module words
     *
     * @param $nameModule
     * @param $content
     * @return string|string[]
     */
    private function replaceNameModuleWords($nameModule, $content)
    {
        return str_replace([
            'psmoduler',
            'Psmoduler',
            'PSMODULER',
        ], [
            mb_strtolower($nameModule),
            Str::ucfirst(mb_strtolower($nameModule)),
            mb_strtoupper($nameModule),
        ], $content);
    }

    /**
     * Build ZIP path
     *
     * @param $dirTempName
     * @throws Exception
     */
    private function buildZip($dirTempName)
    {
        $dirTempPath = $this->dirTempPath($dirTempName);
        $zipPath = $dirTempPath . '.zip';
        $zip = Zip::create($zipPath);
        $zip->add($dirTempPath);
        $zip->close();
    }

    /**
     * Directory temporary path
     *
     * @param string $dirTempName
     * @return string
     */
    private function dirTempPath(string $dirTempName)
    {
        return storage_path('app/temp/psmoduler/' . $dirTempName);
    }

    /**
     * Delete directory temporary by name
     *
     * @param string $dirTempName
     */
    private function deleteDirTempByName(string $dirTempName)
    {
        $dirTempPath = $this->dirTempPath($dirTempName);
        File::deleteDirectory($dirTempPath);
    }

    /**
     * Send to storage
     *
     * @param string $dirTempName
     */
    private function sendToStorage(string $dirTempName)
    {
        $zipTempPath = $this->dirTempPath($dirTempName) . '.zip';
        $zipPath = 'temp/psmoduler/' . $dirTempName  . '.zip';
        if (!Storage::exists($zipPath)){
            $content = File::get($zipTempPath);
            Storage::put($zipPath, $content);
        }
    }
}

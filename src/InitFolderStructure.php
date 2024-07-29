<?php

require __DIR__ . '/../vendor/autoload.php';

use Enumeration\FolderName;

class InitFolderStructure extends Exception
{
    public mixed $projectName = "";
    public bool $parentFolderInitializationSucceed = false;

    public string $folderWhereTheProjectIsGonnaBeCreated = "C:\\xampp\\htdocs\\";

    const WELCOME_MESSAGE = PHP_EOL . "Please enter the name of your project, spaces will be replaced by underscores and accents of any kind are not allowed, only lowercase letters will be accepted : " . PHP_EOL . PHP_EOL;

    const CONFIRM_MESSAGE = PHP_EOL . "Are you sure about the project name with (y/n) : ";

    const CONFIRM_OF_PROCEDURE_BY_USER = true;

    const DENIED_OF_PROCEDURE_BY_USER = false;

    const PARENT_FOLDER_BUILT = PHP_EOL . "Everything is all set ! Happy coding !" . PHP_EOL;

    const BAD_INPUT_ERROR =
        PHP_EOL . "Please, respect the following criteria for the name of your project :" . PHP_EOL . PHP_EOL . "- All letters on lowercase" . PHP_EOL . "- No spaces" . PHP_EOL . "- No accents" . PHP_EOL . PHP_EOL . "Try again : ";

    public function initializeAProjectName()
    {
        echo self::WELCOME_MESSAGE;

        while ($this->parentFolderInitializationSucceed == false) {
            $streamOfUserTypingTheNameOfTheProject = fopen("php://stdin", "r");

            $this->projectName = trim(fgets($streamOfUserTypingTheNameOfTheProject));

            try {
                $noErrors = $this->handleBadInputFromInitializationFolderProcess();
                $userChoice = $this->isUserConfirmNameOfTheProject();

                if ($noErrors) {
                    switch ($userChoice) {
                        case true:
                            $this->parentFolderInitializationSucceed = true;

                            if ($this->parentFolderInitializationSucceed) {
                                $this->folderWhereTheProjectIsGonnaBeCreated .= $this->projectName;
                                mkdir($this->folderWhereTheProjectIsGonnaBeCreated);
                                echo self::PARENT_FOLDER_BUILT;
                                fclose($streamOfUserTypingTheNameOfTheProject);
                            }
                            break;

                        default:
                            echo self::WELCOME_MESSAGE;
                            $this->parentFolderInitializationSucceed = false;
                            break;
                    }
                }
            } catch (Exception $e) {
                echo $e->getMessage();
            }
        }
    }

    public function isUserConfirmNameOfTheProject()
    {
        echo self::CONFIRM_MESSAGE;

        $streamOfUserConfirmingHisChoice = fopen("php://stdin", "r");
        $userChoice = trim(fgets($streamOfUserConfirmingHisChoice));

        fclose($streamOfUserConfirmingHisChoice);

        return $userChoice == 'y' ? self::CONFIRM_OF_PROCEDURE_BY_USER : self::DENIED_OF_PROCEDURE_BY_USER;
    }

    public function handleBadInputFromInitializationFolderProcess()
    {
        preg_match_all("/[^a-z]/", $this->projectName, $matches);

        if (empty(current($matches))) {
            return true;
        }
        throw new Exception(self::BAD_INPUT_ERROR);
    }

    public function createConfigFolder()
    {
        if ($this->parentFolderInitializationSucceed) {
            mkdir($this->folderWhereTheProjectIsGonnaBeCreated . "/config");
        }
    }

    public function createParentFoldersOnProjectFolder()
    {
        if ($this->parentFolderInitializationSucceed) {
            $arrays = [FolderName::DIAGRAMS, FolderName::SRC, FolderName::PUBLIC, FolderName::ASSETS, FolderName::TEMPLATES];

            foreach ($arrays as $arr) {
                foreach ($arr as $parentFolderKey => $subFolder) {
                    switch ($parentFolderKey) {
                        case "diagrams":
                            mkdir($this->folderWhereTheProjectIsGonnaBeCreated . "\\diagrams");
                            $this->createSubFolders($subFolder, $parentFolderKey);
                            break;

                        case "src":
                            mkdir($this->folderWhereTheProjectIsGonnaBeCreated . "\\src");
                            $this->createSubFolders($subFolder, $parentFolderKey);
                            break;

                        case "templates":
                            mkdir($this->folderWhereTheProjectIsGonnaBeCreated . "\\templates");
                            $this->createSubFolders($subFolder, $parentFolderKey);
                            break;
                        case "public":
                            mkdir($this->folderWhereTheProjectIsGonnaBeCreated . "\\public");
                            $filename = "index.php";
                            $file = fopen($this->folderWhereTheProjectIsGonnaBeCreated . "\\public\\" . $filename, 'w');
                            fclose($file);
                            $this->createSubFolders($subFolder, $parentFolderKey);
                            break;
                        case "assets":
                            if (!is_dir($this->folderWhereTheProjectIsGonnaBeCreated . "\\public\\assets")) {
                                mkdir($this->folderWhereTheProjectIsGonnaBeCreated . "\\public\\assets");
                            }

                            $this->createSubFolders($subFolder, $parentFolderKey);
                            break;
                    }
                }
            }
        }
    }

    public function createSubFolders($subFolderValues, $keyOfParentFolderOfSubFolder)
    {
        if (is_array($subFolderValues)) {
            foreach ($subFolderValues as $subFolderValue) {
                switch (true) {
                    case $keyOfParentFolderOfSubFolder == "assets":
                        mkdir($this->folderWhereTheProjectIsGonnaBeCreated . "\\public\\assets\\$subFolderValue");
                        break;
                    case $keyOfParentFolderOfSubFolder == "templates":
                        mkdir($this->folderWhereTheProjectIsGonnaBeCreated . "\\templates\\$subFolderValue");
                        break;
                    case is_dir($this->folderWhereTheProjectIsGonnaBeCreated . "\\$keyOfParentFolderOfSubFolder"):
                        mkdir($this->folderWhereTheProjectIsGonnaBeCreated . "\\$keyOfParentFolderOfSubFolder\\$subFolderValue");
                        break;
                }
            }
        }
    }
}

$obj = new InitFolderStructure();

try {
    $obj->initializeAProjectName();
    $obj->createConfigFolder();
    $obj->createParentFoldersOnProjectFolder();
} catch (Exception $e) {
    echo $e->getMessage();
}



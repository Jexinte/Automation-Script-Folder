<?php

require __DIR__ . '/../vendor/autoload.php';

use Exception;
use Enumeration\FolderName;

class InitFolderStructure extends Exception
{
    public mixed $projectName = "";
    public bool $parentFolderInitializationSucceed = false;

    public string $folderWhereTheProjectIsGonnaBeCreated = "C:\\xampp\\htdocs\\";

    const RULES_MESSAGES = PHP_EOL . "Please enter the name of your project, spaces will be replaced by underscores and accents of any kind are not allowed, only lowercase letters will be accepted : " . PHP_EOL . PHP_EOL;

    const CONFIRMATION_OF_USER_FOR_THE_NAME_OF_THE_PROJECT_MESSAGE = PHP_EOL . "Are you sure about the project name with (y/n) : ";

    const CONFIRM_OF_PROCEDURE_BY_USER = true;

    const DENIED_OF_PROCEDURE_BY_USER = false;

    const SET_UP_FINISH_MESSAGE = PHP_EOL . "Everything is all set ! Happy coding !" . PHP_EOL;

    const BAD_INPUT_ERROR_MESSAGE =
        PHP_EOL . "Please, respect the following criteria for the name of your project :" . PHP_EOL . PHP_EOL . "- All letters on lowercase" . PHP_EOL . "- No spaces" . PHP_EOL . "- No accents" . PHP_EOL . PHP_EOL . "Try again : ";

    public function initialisationOfTheFolderStructureBuiltAutomation()
    {
        echo self::RULES_MESSAGES;

        while ($this->parentFolderInitializationSucceed == false) {
            $streamOfUserTypingTheNameOfTheProject = fopen("php://stdin", "r");

            $this->projectName = trim(fgets($streamOfUserTypingTheNameOfTheProject));

            try {
                $noErrors = $this->handleBadInputFromInitialisationOfTheFolderStructureBuiltAutomation();
                $userChoice = $this->isUserConfirmNameOfTheProject();

                if ($noErrors) {
                    switch ($userChoice) {
                        case true:
                            $this->projectName = str_replace(" ", "_", $this->projectName);
                            $this->parentFolderInitializationSucceed = true;

                            if ($this->parentFolderInitializationSucceed) {
                                $this->folderWhereTheProjectIsGonnaBeCreated .= $this->projectName;
                                mkdir($this->folderWhereTheProjectIsGonnaBeCreated);
                                echo self::SET_UP_FINISH_MESSAGE;
                                fclose($streamOfUserTypingTheNameOfTheProject);
                            }
                            break;

                        default:
                            echo self::RULES_MESSAGES;
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
        echo self::CONFIRMATION_OF_USER_FOR_THE_NAME_OF_THE_PROJECT_MESSAGE;

        $streamOfUserConfirmingHisChoice = fopen("php://stdin", "r");
        $userChoice = trim(fgets($streamOfUserConfirmingHisChoice));

        fclose($streamOfUserConfirmingHisChoice);

        return $userChoice == 'y' ? self::CONFIRM_OF_PROCEDURE_BY_USER : self::DENIED_OF_PROCEDURE_BY_USER;
    }

    public function handleBadInputFromInitialisationOfTheFolderStructureBuiltAutomation()
    {
        preg_match_all("/[a-z\s]{1,}/", $this->projectName, $matches);

        if (!empty(current($matches))) {
            return true;
        }
        throw new Exception(self::BAD_INPUT_ERROR_MESSAGE);
    }

    public function createConfigFolder()
    {
        mkdir($this->folderWhereTheProjectIsGonnaBeCreated . "\\" . FolderName::CONFIG);
    }

    public function createPublicFolderAndSubFolders()
    {
        mkdir($this->folderWhereTheProjectIsGonnaBeCreated . "\\" . FolderName::PUBLIC);
        mkdir($this->folderWhereTheProjectIsGonnaBeCreated . "\\" . FolderName::PUBLIC . "\\" . FolderName::ASSETS);
        $this->createAssetsSubFolders();
    }

    public function createIndexFileInPublicFolder()
    {
        $filename = "index.php";

        $file = fopen($this->folderWhereTheProjectIsGonnaBeCreated . "\\" . FolderName::PUBLIC . "\\$filename", "w");

        fclose($file);
    }

    public function createAssetsSubFolders()
    {
        $subFolders = [FolderName::CSS, FolderName::IMAGES, FolderName::JS, FolderName::WIREFRAMES];

        foreach ($subFolders as $subFolder) {
            mkdir($this->folderWhereTheProjectIsGonnaBeCreated . "\\" . FolderName::PUBLIC . "\\" . FolderName::ASSETS . "\\" . $subFolder);
        }
    }

    public function createTemplateFolderAndSubFolder()
    {
        mkdir($this->folderWhereTheProjectIsGonnaBeCreated . "\\" . FolderName::TEMPLATES);
        mkdir($this->folderWhereTheProjectIsGonnaBeCreated . "\\" . FolderName::TEMPLATES . "\\" . FolderName::TEMPLATES_ADMIN);
    }

    public function createFolderAndFiles()
    {
        $arrays = [FolderName::DIAGRAMS, FolderName::SRC];

        if ($this->parentFolderInitializationSucceed) {
            $this->createConfigFolder();
            $this->createPublicFolderAndSubFolders();
            $this->createIndexFileInPublicFolder();
            $this->createTemplateFolderAndSubFolder();

            foreach ($arrays as $arr) {
                foreach ($arr as $parentFolderKey => $subFolder) {
                    mkdir($this->folderWhereTheProjectIsGonnaBeCreated . "\\$parentFolderKey");
                    $this->createSubFolders($subFolder, $parentFolderKey);
                }
            }
        }
    }

    public function createSubFolders($subFolderValues, $keyOfParentFolderOfSubFolder)
    {
        if (is_array($subFolderValues)) {
            foreach ($subFolderValues as $subFolderValue) {
                mkdir($this->folderWhereTheProjectIsGonnaBeCreated . "\\$keyOfParentFolderOfSubFolder\\$subFolderValue");
            }
        }
    }
}

$obj = new InitFolderStructure();

try {
    $obj->initialisationOfTheFolderStructureBuiltAutomation();
    $obj->createFolderAndFiles();
} catch (Exception $e) {
    echo $e->getMessage();
}

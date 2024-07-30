<?php

require __DIR__ . '/../vendor/autoload.php';



use Enumeration\FolderName;
use Enumeration\Message;

class InitFolderStructure extends Exception
{
    public mixed $projectName = "";
    public bool $parentFolderInitializationSucceed = false;

    public string $folderWhereTheProjectIsGonnaBeCreated = "C:\\xampp\\htdocs\\";

    

    public function initialisationOfTheFolderStructureBuiltAutomation()
    {
        echo Message::RULES;

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
                                echo Message::SET_UP_FINISH;
                                fclose($streamOfUserTypingTheNameOfTheProject);
                            }
                            break;

                        default:
                            echo Message::RULES;
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
        echo Message::CONFIRMATION_OF_USER_FOR_THE_NAME_OF_THE_PROJECT;

        $streamOfUserConfirmingHisChoice = fopen("php://stdin", "r");
        $userChoice = trim(fgets($streamOfUserConfirmingHisChoice));

        fclose($streamOfUserConfirmingHisChoice);

        return $userChoice == 'y' ? Message::CONFIRM_OF_PROCEDURE_BY_USER : Message::DENIED_OF_PROCEDURE_BY_USER;
    }

    public function handleBadInputFromInitialisationOfTheFolderStructureBuiltAutomation()
    {
        preg_match_all("/[a-z\s]{1,}/", $this->projectName, $matches);

        if (!empty(current($matches))) {
            return true;
        }
        throw new Exception(Message::BAD_INPUT_ERROR);
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

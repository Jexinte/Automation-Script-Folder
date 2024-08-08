<?php

require __DIR__ . '/../vendor/autoload.php';

use Enumeration\FolderName;
use Enumeration\Message;
use Enumeration\User;

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
                        case USER::CONFIRM_THE_PROCEDURE:
                            $this->projectName = str_replace(" ", "-", $this->projectName);
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

    public function isFolderNameAlreadyExist()
    {
        $foldersInHtdocs = scandir($this->folderWhereTheProjectIsGonnaBeCreated);

        foreach ($foldersInHtdocs as $folder) {
            if ($folder == $this->projectName) {
                return true;
            }
        }
        return null;
    }

    public function isUserConfirmNameOfTheProject()
    {
        echo Message::ASKING_CONFIRMATION_OF_USER_FOR_THE_NAME_OF_THE_PROJECT;

        $streamOfUserConfirmingHisChoice = fopen("php://stdin", "r");
        $userChoice = trim(fgets($streamOfUserConfirmingHisChoice));

        fclose($streamOfUserConfirmingHisChoice);

        return $userChoice == 'y' ? User::CONFIRM_THE_PROCEDURE : User::DENIED_THE_PROCEDURE;
    }

    public function handleBadInputFromInitialisationOfTheFolderStructureBuiltAutomation()
    {
        preg_match_all("/[a-z\s]{1,}/", $this->projectName, $matches);
        switch (true) {
            case $this->isFolderNameAlreadyExist():
                throw new Exception(Message::FOLDER_NAME_NOT_AVAILABLE);
            default:
                if (!empty(current($matches))) {
                    return true;
                }
                throw new Exception(Message::BAD_INPUT_ERROR);
        }
    }

    public function createConfigFolder()
    {
        mkdir($this->folderWhereTheProjectIsGonnaBeCreated . "\\" . FolderName::CONFIG);
    }

    public function createRouterFolderAndFiles()
    {
        mkdir($this->folderWhereTheProjectIsGonnaBeCreated . "\\" . FolderName::ROUTER);
        $filename = "Router.php";
        
        $file = fopen($this->folderWhereTheProjectIsGonnaBeCreated . "\\" . FolderName::ROUTER . "\\$filename", "w");

        $code = <<<'PHP'
        <?php
        
        namespace Router;
        
        use Util\RequestObject;
        
        class Router{
        
            public function __construct(private RequestObject $requestObject){}
            public function controllersAction($key): array
            {
                $controllers = [
                    "register" => [
                        "controller" => "controller_result", // $obj->exampleResult()
                        "template" => "example.twig",
                        "parameters" => [
                            "name_of_the_parameter" => "value"
                        ]
        
                    ],
        
                ];
        
                return $controllers[$key];
            }
        
            public function controllersSelection($key): array
            {
                $controllers = [
                    "register" => [
                        "controller" => "controller_result", // $obj->exampleResult()
                        "template" => "example.twig",
                        "parameters" => [
                            "name_of_the_parameter" => "value"
                        ]
        
                    ],
        
                ];
        
                return $controllers[$key];
            }
        
            /* A hidden input will be use in order to know to which methods sent the data*/
        
            public function resolveAction(): ?array
            {
                if(!empty($this->requestObject->post()["action"])){
                    return $this->controllersAction($this->requestObject->post()["action"]);
                }
            }
        
            public function resolveSelection(): ?array
            {
                if(!empty($this->requestObject->get()["selection"])){
                    return $this->controllersSelection($this->requestObject->get()["selection"]);
                }
            }
        }
        PHP;
        fwrite($file,$code);
    }

    public function createUtilFolderAndFiles(){
     
        mkdir($this->folderWhereTheProjectIsGonnaBeCreated . "\\" . FolderName::UTIL);
        $filename = "RequestObject.php";
        
        $file = fopen($this->folderWhereTheProjectIsGonnaBeCreated . "\\" . FolderName::UTIL . "\\$filename", "w");

        $code = <<< 'PHP'
        <?php

        namespace Util;

        class RequestObject
        {
                public function post(): array
                {
                        return $_POST;
                }

                public function get(): array
                {
                        return $_GET;
                }

                public function isDataHaveBeenSent(): ?bool
                {
                        if (isset($_POST['action'])) {
                                return true;
                        }
                        return null;
                }
                public function aSelectionHasBeenMade(): ?bool
                {
                        if (isset($_GET['selection'])) {
                                return true;
                        }
                        return null;
                }

                public function files(): array
                {
                        return $_FILES;
                }
        }

        PHP;
        fwrite($file,$code);
    }

    public function createManagerFolderAndFiles(){
        mkdir($this->folderWhereTheProjectIsGonnaBeCreated . "\\" . FolderName::MANAGER);
        $filename = "Session.php";
        
        $file = fopen($this->folderWhereTheProjectIsGonnaBeCreated . "\\" . FolderName::MANAGER . "\\$filename", "w");

        $code = <<< 'PHP'
            <?php


            class Session
            {

                /**
                 * Summary of startSession
                 *
                 * @return void
                 */
                public function startSession(): void
                {

                    if (session_status() != PHP_SESSION_ACTIVE) {
                        session_start();
                    }

                }

                /**
                 * Summary of destroySession
                 *
                 * @return void
                 */
                public function destroySession(): void
                {

                    if (session_status() === PHP_SESSION_ACTIVE) {
                        session_unset();
                        session_destroy();
                    }
                }



                /**
                 * Summary of initializeKeyAndValue
                 *
                 * @param string          $key
                 * @param string|null|int $value
                 *
                 * @return void
                 */
                public function initializeKeyAndValue(string $key, string|null|int $value): void
                {
                    $_SESSION[$key] = $value;
                }

                /**
                 * Summary of getSessionData
                 *
                 * @return array
                 */
                public function getSessionData(): array
                {
                    return $_SESSION;
                }
            }
        PHP;
        fwrite($file,$code);
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

    public function createComposerJsonFileInProjectFolder()
    {
        $filename = "composer.json";

        $file = fopen($this->folderWhereTheProjectIsGonnaBeCreated . "\\$filename", "w");

        fwrite($file,"{\n}");

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
            $this->createComposerJsonFileInProjectFolder();
            $this->createRouterFolderAndFiles();
            $this->createUtilFolderAndFiles();
            $this->createManagerFolderAndFiles();

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

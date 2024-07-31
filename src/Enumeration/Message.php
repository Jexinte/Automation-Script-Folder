<?php

namespace Enumeration;


enum Message {
    const RULES = PHP_EOL . "Please enter the name of your project, spaces will be replaced by underscores and accents of any kind are not allowed, only lowercase letters will be accepted : " . PHP_EOL . PHP_EOL;

    const ASKING_CONFIRMATION_OF_USER_FOR_THE_NAME_OF_THE_PROJECT = PHP_EOL . "Are you sure about the project name with (y/n) : ";

    const SET_UP_FINISH = PHP_EOL . "Everything is all set ! Happy coding !" . PHP_EOL;

    const BAD_INPUT_ERROR =
        PHP_EOL . "Please, respect the following criteria for the name of your project :" . PHP_EOL . PHP_EOL . "- All letters on lowercase" . PHP_EOL . "- No spaces" . PHP_EOL . "- No accents" . PHP_EOL . PHP_EOL . "Try again : ";

    const FOLDER_NAME_NOT_AVAILABLE = PHP_EOL."The folder name is not available, please type another : ";

    const FOLDER_NAME_ALREADY_EXIST = true;
    const FOLDER_NAME_NOT_EXIST = false;
}
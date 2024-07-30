<?php

namespace Enumeration;

enum FolderName {

    const CONFIG = "config";
    const PUBLIC = "public";
    const TEMPLATES = "templates";
    const TEMPLATES_ADMIN = "admin";
    const ASSETS = "assets";
    const CSS = "css";
    const IMAGES = "images";
    const JS = "js";
    const WIREFRAMES = "wireframes";
    const DIAGRAMS =  [
    "diagrams" => [
         "UseCases",
        "Sequences",
         "Class",
    ]
    ];
    
    const SRC = [
        "src" => [
"Repository","Controller","Enumeration","Exceptions","Manager","Entity","Util","Service"
        ]
        ];
    

}
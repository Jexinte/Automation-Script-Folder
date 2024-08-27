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

    const ROUTER = "Router";
    const MANAGER = "Manager";
    const UTIL = "Util";
    const EXCEPTIONS = "Exceptions";

    const DIAGRAMS =  [
    "diagrams" => [
         "use_cases",
        "sequences",
         "class",
    ]
    ];
    
    const SRC = [
        "src" => ["Repository","Controller","Enumeration","Entity","Service"]
        ];
    

}
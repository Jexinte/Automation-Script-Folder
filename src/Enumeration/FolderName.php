<?php

namespace Enumeration;

enum FolderName {

    const CONFIG_FOLDER = "config";
    const DIAGRAMS =  [
    "diagrams" => [
         "UseCases",
        "Sequences",
         "Class",
    ]
    ];
    
    const SRC = [
        "src" => [
"Repository","Controller","Enumeration","Exceptions","Manager","Entity","Util"
        ]
        ];
    
    const TEMPLATES = [
        "templates" => ['admin']
    ];
 
    const PUBLIC = [
    "public" => ["assets"]
    ];

    const ASSETS = [
        "assets" => [
            "css","images","js","wireframes"
        ]
    ]
    ;


}
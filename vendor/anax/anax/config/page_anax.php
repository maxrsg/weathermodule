<?php
/**
 * Configuration file for page which can create and put together web pages
 * from a collection of views. Through configuration you can add the
 * standard parts of the page, such as header, navbar, footer, stylesheets,
 * javascripts and more.
 */
return [
    // This layout view is the base for rendering the page, it decides on where
    // all the other views are rendered.
    "layout" => [
        //"template" => "anax/v2/layout/default",
        //"template" => "anax/v2/layout/dbwebb_se",
        "template" => "anax/v2/layout/anax",
        "region" => "layout",
        "sort" => null,
        "data" => [
            "baseTitle" => " | Anax",
            "favicon" => "favicon.ico",
            "bodyClass" => null,
            "htmlClass" => null,
            "lang" => "sv",
            "stylesheets" => [
                //"css/style.css",
                "css/dbwebb-se_v2.min.css",
            ],
            "javascripts" => [
                "js/main.js",
                "js/responsive-menu.js",
            ],
        ],
    ],

    // These views are always loaded into the collection of views.
    "views" => [
        [
            "region" => "header-col-1",
            "template" => "anax/v2/header/site_logo",
            "data" => [
                "class" => "large",
                "siteLogo"      => "image/theme/leaf_256x256.png",
                "siteLogoAlt"   => "Leaf",
            ],
        ],
        [
            "region" => "header-col-1",
            "template" => "anax/v2/header/site_logo_text",
            "data" => [
                "homeLink"      => "",
                "siteLogoText"  => "Anax",
                "siteLogoTextIcon" => "image/theme/leaf_40x40.png",
                "siteLogoTextIconAlt" => "Leaf",
            ],
        ],
        [
            "region" => "header-col-2",
            "template" => "anax/v2/navbar/navbar_submenus",
            "data" => [
                "navbarConfig" => file_exists(__DIR__ . "/navbar/header.php")
                    ? require __DIR__ . "/navbar/header.php"
                    : null,
            ],
        ],
        [
            "region" => "header-col-3",
            "template" => "anax/v2/navbar/responsive_submenus",
            "data" => [
                "navbarConfig" => file_exists(__DIR__ . "/navbar/responsive.php")
                    ? require __DIR__ . "/navbar/responsive.php":
                    null,
            ],
        ],
        [
            "region" => "footer",
            "template" => "anax/v2/columns/multiple_columns",
            "data" => [
                "class"  => "footer-column",
                "columns" => [
                    [
                        "template" => "anax/v2/block/default",
                        "contentRoute" => "block/footer-col-1",
                    ],
                    [
                        "template" => "anax/v2/block/default",
                        "contentRoute" => "block/footer-col-2",
                    ],
                    [
                        "template" => "anax/v2/block/default",
                        "contentRoute" => "block/footer-col-3",
                    ]
                ]
            ],
            "sort" => 1
        ],
        [
            "region" => "footer",
            "template" => "anax/v2/block/default",
            "data" => [
                "class"  => "site-footer",
                "contentRoute" => "block/footer",
            ],
            "sort" => 2
        ],
    ],
];

<?php

use Aman5537jains\AbnCms\Editor\GrapejsComponent;
use Aman5537jains\AbnCmsCRUD\Components\AddMoreComponent;
use Aman5537jains\AbnCmsCRUD\Components\CKEditorComponent;
use Aman5537jains\AbnCmsCRUD\Components\DateTimeViewComponent;
use Aman5537jains\AbnCmsCRUD\Components\EditorViewComponent;
use Aman5537jains\AbnCmsCRUD\Components\FileInputComponent;
use Aman5537jains\AbnCmsCRUD\Components\ImageComponent;
use Aman5537jains\AbnCmsCRUD\Components\InputComponent;
use Aman5537jains\AbnCmsCRUD\Components\QuillEditorComponent;
use Aman5537jains\AbnCmsCRUD\Components\SelectComponent;
use Aman5537jains\AbnCmsCRUD\Components\TextComponent;
use Aman5537jains\AbnCmsCRUD\Layouts\GalleryLayout;
use Aman5537jains\AbnDynamicContentPlugin\Components\DynamicFormComponent;
use Aman5537jains\AbnDynamicContentPlugin\Components\DynamicSelectComponent;
use Aman5537jains\AbnDynamicContentPlugin\Components\DynamicViewComponent;
use Aman5537jains\AbnDynamicContentPlugin\Components\AtrributeFilterListComponent;
use Aman5537jains\AbnDynamicContentPlugin\Components\RatingComponent;

use Aman5537jains\AbnDynamicContentPlugin\Components\ImageComponent as ComponentsImageComponent;

return [
    "layout"=>"index",
    "all_components"=>[
        "form"=>[
                    "Grapejs"=>["class"=>GrapejsComponent::class],
                    "CKEditor"=>["class"=>CKEditorComponent::class],
                    "Simple Textarea"=>["class"=>InputComponent::class,"config"=>["type"=>"textarea"]],
                    "Input"=>["class"=>InputComponent::class,"config"=>["type"=>"text","options"=>[]]],
                ],
        "view"=>[
            "Text"=>["class"=>TextComponent::class],
            "Date Time"=>["class"=>DateTimeViewComponent::class],
            "Image" =>["class"=>ComponentsImageComponent::class ],
            "Html View"=>["class"=>EditorViewComponent::class],
        ]
    ],
    "views"=>[
        "LIST"=>[
                    "Pagination"=>["class"=>DynamicViewComponent::class,"config"=>["fetch_method"=>"paginate"]],
                    "List"=>["class"=>DynamicViewComponent::class,"config"=>["fetch_method"=>"get"]],
                    "Simple Pagination"=>["class"=>DynamicViewComponent::class,"config"=>["fetch_method"=>"paginate"]],
                    "Atrribute Filter"=>["class"=>AtrributeFilterListComponent::class,"config"=>["attribute_column"=>"is_featured","attribute_column_value"=>"1","fetch_method"=>"paginate"]],
                    "Gallery View"=>["class"=>DynamicViewComponent::class,"config"=>["fetch_method"=>"get","view_type"=>"VIEW","component"=>["class"=>GalleryLayout::class,"config"=>[]]]],

                ],
        "VIEW"=>[
            "Simple View"=>["class"=>DynamicViewComponent::class,"config"=>["fetch_method"=>"get","view_type"=>"VIEW"]],


        ],
        "FORM"=>[
            "Simple Form"=>["class"=>DynamicFormComponent::class,"config"=>["success_message"=>"Thank you for contacting us, we will connect with you soon!"]]
        ]
    ],
    "components"=>[
        "text"=>[
            "view"=>["class"=>TextComponent::class],
            "form"=>["class"=>InputComponent::class],
        ],
        "email"=>[
            "view"=>["class"=>TextComponent::class],
            "form"=>["class"=>InputComponent::class,"config"=>["type"=>'email']],
        ],
        "phone_no"=>[
            "view"=>["class"=>TextComponent::class],
            "form"=>["class"=>InputComponent::class,"config"=>["type"=>'phone']],
        ],
        "date"=>[
            "view"=>["class"=>TextComponent::class],
            "form"=>["class"=>InputComponent::class,"config"=>["type"=>"date"]],
        ],
        "time"=>[
            "view"=>["class"=>TextComponent::class],
            "form"=>["class"=>InputComponent::class,"config"=>["type"=>"time"]],
        ],
        "textarea"=>[
            "view"=>["class"=>EditorViewComponent::class],
            "form"=>["class"=>GrapejsComponent::class],
        ],
        "image"=>[
            "view"=>["class"=>ComponentsImageComponent::class],
            "form"=>["class"=>FileInputComponent::class,"config"=>["isImage"=>true,"base_url"=>url("/")]],
        ],
        "multiple_image"=>[
            "view"=>["class"=>ComponentsImageComponent::class],
            "form"=>["class"=>FileInputComponent::class,"config"=>["isImage"=>true,"base_url"=>url("/"),"attr"=>["multiple"=>true]]],
        ],
        "url"=>[
            "view"=>["class"=>TextComponent::class],
            "form"=>["class"=>InputComponent::class,"config"=>["type"=>"url"]],
        ],
        "Number"=>[
            "view"=>["class"=>TextComponent::class],
            "form"=>["class"=>InputComponent::class,"config"=>["type"=>"number"]],
        ],
         "Rating"=>[
            "view"=>["class"=>RatingComponent::class],
            "form"=>["class"=>InputComponent::class,"config"=>["type"=>"select","options"=>["1"=>"1","2"=>"2","3"=>"3","4"=>"4","5"=>"5"]]],
        ],

        "dynamic_content"=>[
            "view"=>["class"=>TextComponent::class],
            "form"=>["class"=>DynamicSelectComponent::class],
        ],
         "select_yes_no"=>[
            "view"=>["class"=>TextComponent::class],
            "form"=>["class"=>InputComponent::class,"config"=>["type"=>"select","options"=>["1"=>"Yes","0"=>"No"],"value"=>"0"]],
        ],
    ],

    "fields"=>[
        "name"=>[
            "view"=>["class"=>TextComponent::class],
            "form"=>["class"=>InputComponent::class],
        ],
        "email"=>[
            "view"=>["class"=>TextComponent::class],
            "form"=>["class"=>InputComponent::class,"config"=>["type"=>'email']],
        ],
        "phone_no"=>[
            "view"=>["class"=>TextComponent::class],
            "form"=>["class"=>InputComponent::class,"config"=>["type"=>'phone']],
        ],
        "dynamic_content_data_id"=>[
            "view"=>["class"=>TextComponent::class],
            "form"=>["class"=>DynamicSelectComponent::class],
        ],
        "status"=>[
            "view"=>["class"=>TextComponent::class],
            "form"=>["class"=>SelectComponent::class,"config"=>["options"=>["1"=>"Active","0"=>"Inactive"]]],
        ],
        "start_date_time"=>[
            "view"=>["class"=>DateTimeViewComponent::class],
            "form"=>["class"=>InputComponent::class,"config"=>["type"=>"datetime"]],
        ],
        "end_date_time"=>[
            "view"=>["class"=>DateTimeViewComponent::class],
            "form"=>["class"=>InputComponent::class,"config"=>["type"=>"datetime"]],
        ],


        "icon"=>[
            "view"=>["class"=>ComponentsImageComponent::class ],
            "form"=>["class"=>FileInputComponent::class,"config"=>["isImage"=>true,"base_url"=>url("/")]],
        ],

        "title"=>[
            "view"=>["class"=>TextComponent::class],
            "form"=>["class"=>InputComponent::class],
        ],
        "description"=>[
            "view"=>["class"=>EditorViewComponent::class],
            "form"=>["class"=>CKEditorComponent::class],
        ],
        "image"=>[
            "view"=>["class"=>ComponentsImageComponent::class],
            "form"=>["class"=>FileInputComponent::class,"config"=>["isImage"=>true,"base_url"=>url("/")]],
        ],
        "url"=>[
            "view"=>["class"=>TextComponent::class],
            "form"=>["class"=>InputComponent::class,"config"=>["type"=>"url"]],
        ],

    ],
    "default"=>[
        "view"=>["class"=>TextComponent::class],
        "form"=>["class"=>InputComponent::class],
    ]
];

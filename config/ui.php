<?php

return [
    "sidebar_menu" => [
        [
            "type" => "section",
            "label" => "HOME"
        ], [
            "type" => "item",
            "label" => "Dashboard",
            "icon" => "bi bi-speedometer2",
            "route" => "dashboard"
        ], [
            "type" => "section",
            "label" => "MENU"
        ],[
            "type" => "dropdown",
            "label" => "Students",
            "icon" => "bi bi-people",
            "children" => [
                [
                    "label" => "Admission",
                    "route" => "students.admission"
                ],
                [
                    "label" => "Data Entry",
                    "route" => "students.data-entry"
                ]
            ]
        ], [
            "type" => "dropdown",
            "label" => "Finance",
            "icon" => "bi bi-cash-stack",
            "route" => "finance.index",
            "children" => [
                [
                    'label' => 'Buat Pembayaran',
                    'route' => 'finance.create',
                    'visible_on' => [
                        'finance.create',
                        'finance.create.*'
                    ]
                ],
                [
                    'label' => 'Pembayaran',
                    'route' => 'finance.payment',

                    'visible_on' => [
                        'finance.create',
                        'finance.create.*'
                    ]
                ],
            ]
        ],
    ],
];
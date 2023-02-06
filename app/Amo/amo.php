
<?php


function curl_to_amo_in_form($values)
{
    //MAIN CONFIG DATE config.php
     require_once 'access.php';
    require_once 'config_for_request.php';
    //----------REQUEST_ARRAY-----------------------------------------------------------------//


$data = [

    [
        "name" => $name,
        "created_by" => 0,
        "price" => $price,
        "pipeline_id" => (int) $pipeline_id ,
        "custom_fields_values" => [
            [
                "field_id" => (int) $id_url_for_product ,//url_tovar
                "values" => [
                    [
                        "value" => $values['url_for_product']
                    ]
                ]
            ],
            [
                "field_id" => (int) $id_status_product ,//status
                "values" => [
                    [
                        "value" => $values['status_product']
                    ]
                ]
            ],
            [
                "field_id" => (int) $id_delivery_address ,//dostavka
                "values" => [
                    [
                        "value" => $values['delivery_address']
                    ]
                ]
            ],
            [
                "field_id" => (int) $id_the_number_of_boxes ,//kol-vo korobok
                "values" => [
                    [
                        "value" => $values['the_number_of_boxes']
                    ]
                ]
            ],
            [
                "field_id" => (int) $id_telegram_contact ,//telegram
                "values" => [
                    [
                        "value" => $values['telegram_contact']
                    ]
                ]
            ],
            [
                "field_id" => (int) $id_name_user , //Name
                "values" => [
                    [
                        "value" => $values['name']
                    ]
                ]
            ],
            [
                "field_id" => (int) $id_telephone_number , //telephone_number
                "values" => [
                    [
                        "value" => $values['telegram_number']
                    ]
                ]
            ],
            [
                "field_id" => (int) $id_promocode ,//promocode
                "values" => [
                    [
                        "value" => $values['promocode']
                    ]
                ]
            ],
            [
                "field_id" => (int) $id_study_or_teacher ,//Teacher or study
                "values" => [
                    [
                        "value" => $values['study_or_teacher']
                    ]
                ]
            ],
            [
                "field_id" => (int) $id_address_of_the_recipient ,//adress poluchenia
                "values" => [
                    [
                        "value" => $values['address_of_the_recipient']
                    ]
                ]
            ],
            [
                "field_id" => (int) $id_commission_percent , //comis, %
                "values" => [
                    [
                        "value" => $values['commission_percent']
                    ]
                ]
            ],
            [
                "field_id" => (int) $id_commission_rub , //comis rub
                "values" => [
                    [
                        "value" => $values['commission_rub']
                    ]
                ]
            ],
            [
                "field_id" => (int) $id_price_order ,//Stoimost
                "values" => [
                    [
                        "value" => $values['price']
                    ]
                ]
            ],
            [
                "field_id" => (int) $id_translation_course ,//Kurs
                "values" => [
                    [
                        "value" => &$values['translation_course']
                    ]
                ]
            ],
            [
                "field_id" => (int) $id_size_box ,//sizebox
                "values" => [
                    [
                        "value" => $values['size_box']
                    ]
                ]
            ],
            [
                "field_id" => (int) $id_channel ,//channel
                "values" => [
                    [
                        "value" => $values['channel']
                    ]
                ]
            ]
    ]
]

];


        //---------------------------------------------------------------------------------------------------------------------//
    $method = "/api/v4/leads/complex";

$headers = [
    'Content-Type: application/json',
    'Authorization: Bearer ' . $access_token,
];

$curl = curl_init();
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_USERAGENT, 'amoCRM-API-client/1.0');
curl_setopt($curl, CURLOPT_URL, "https://$subdomain.amocrm.ru".$method);
curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
curl_setopt($curl, CURLOPT_HEADER, false);
curl_setopt($curl, CURLOPT_COOKIEFILE, 'amo/cookie.txt');
curl_setopt($curl, CURLOPT_COOKIEJAR, 'amo/cookie.txt');
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
$out = curl_exec($curl);
$code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
$code = (int) $code;
$errors = [
    301 => 'Moved permanently.',
    400 => 'Wrong structure of the array of transmitted data, or invalid identifiers of custom fields.',
    401 => 'Not Authorized. There is no account information on the server. You need to make a request to another server on the transmitted IP.',
    403 => 'The account is blocked, for repeatedly exceeding the number of requests per second.',
    404 => 'Not found.',
    500 => 'Internal server error.',
    502 => 'Bad gateway.',
    503 => 'Service unavailable.'
];

if ($code < 200 || $code > 204) die( "Error $code. " . (isset($errors[$code]) ? $errors[$code] : 'Undefined error') );


$Response = json_decode($out, true);
$Response = $Response['_embedded']['items'];
$output = 'ID добавленных элементов списков:' . PHP_EOL;
foreach ($Response as $v)
    if (is_array($v))
        $output .= $v['id'] . PHP_EOL;
return $output;

}

?>






<?php
return [

    'visits_fields'=>["ym:s:counterID",
        "ym:s:dateTime",
        "ym:s:date",
        "ym:s:firstPartyCookie"],
    "hits_fields"=> [ // список параметров хитов
        "ym:pv:counterID",
        "ym:pv:dateTime",
        "ym:pv:date",
        "ym:pv:firstPartyCookie"
    ],
    "clickhouse"=>['host'=>'x','port'=>'8123','username'=>'x','password'=>'x'],
    'counter_id'=>'23413123123',
    'cluster'=>'<NAME CLUSTER OR FALSE>'
];
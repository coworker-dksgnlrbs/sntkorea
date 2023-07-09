<?php

use Illuminate\Support\Str;

// 카카오톡 알림톡 발송 API
return [
    'key' => env('HELLO_MESSAGE_KEY', 'NCSHVLCQAPPZ0F2O'),
    'secret' => env('HELLO_MESSAGE_SECRET', 'IERPB1J7JCZ3WYT7HWEHQA2EPUQC0FVW'),
    'pf_id' => env('HELLO_MESSAGE_PF_ID', 'KA01PF220124084123045pJK2vFhCIC2'),
    'from' => env('HELLO_MESSAGE_FROM', '01030217486'),
];

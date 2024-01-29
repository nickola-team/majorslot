<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted'             => ': 속성이 허용되어야합니다.',
    'active_url'           => ': 유효한 URL이 아닙니다.',
    'after'                => ': 이후의 날짜 여야합니다.',
    'alpha'                => ': 속성은 문자 만 포함 할 수 있습니다.',
    'alpha_dash'           => ': 속성은 문자, 숫자, 대시 만 포함 할 수 있습니다.',
    'alpha_num'            => ': 속성은 문자와 숫자 만 포함 할 수 있습니다.',
    'array'                => ': 속성은 배열이어야합니다.',
    'before'               => ': 이전의 날짜 여야합니다.',
    'between'              => [
        'numeric' => ': 최대범위와 최소범위 사이에 있어야합니다.',
        'file'    => ': 최대범위와 최소범위 사이에 용량이어야 합니다.',
        'string'  => ': 최대범위와 최소범위 사이에 있어야합니다.',
        'array'   => ': 최대범위와 최소범위 사이의 항목에 있어야합니다.',
    ],
    'boolean'              => ': 필드는 true 또는 false 여야합니다.',
    'confirmed'            => 'The :attribute  확인이 일치하지 않습니다.',
    'date'                 => ': 유효한 날짜가 아닙니다.',
    'date_format'          => 'The :attribute does not match the format :format.',
    'different'            => 'The :attribute and :other must be different.',
    'digits'               => 'The :attribute must be :digits digits.',
    'digits_between'       => 'The :attribute must be between :min and :max digits.',
    'email'                => 'The :attribute must be a valid email address.',
    'exists'               => 'The selected :attribute is invalid.',
    'filled'               => '필드는 필수입니다.',
    'image'                => 'The :attribute must be an image.',
    'in'                   => 'The selected :attribute is invalid.',
    'integer'              => 'The :attribute must be an integer.',
    'ip'                   => 'The :attribute must be a valid IP address.',
    'json'                 => 'The :attribute must be a valid JSON string.',
    'max'                  => [
        'numeric' => 'The :attribute may not be greater than :max.',
        'file'    => 'The :attribute may not be greater than :max kilobytes.',
        'string'  => 'The :attribute may not be greater than :max characters.',
        'array'   => 'The :attribute may not have more than :max items.',
    ],
    'mimes'                => 'The :attribute must be a file of type: :values.',
    'min'                  => [
        'numeric' => 'The :attribute must be at least :min.',
        'file'    => 'The :attribute must be at least :min kilobytes.',
        'string'  => 'The :attribute 는 최소  :min 문자 이상이여야 합니다.',
        'array'   => 'The :attribute must have at least :min items.',
    ],
    'not_in'               => '선택한 : 속성이 잘못되었습니다.',
    'numeric'              => ': 속성은 숫자 여야합니다.',
    'regex'                => ':형식이 잘못되었습니다. 유효한 것은 a-z0-9입니다.',
    'required'             => '필수항목을 입력하세요.',
    'required_if'          => ':다음과 같은 경우 속성 필드가 필요합니다.',
    'required_with'        => ':다음과 같은 경우 속성 필드가 필요합니다.',
    'required_with_all'    => ':다음과 같은 경우 속성 필드가 필요합니다.',
    'required_without'     => ':다음과 같은 경우 속성 필드가 필요합니다.:값이 없습니다.',
    'required_without_all' => ': 값이없는 경우 필드가 필요합니다.',
    'same'                 => ': 두 필드값이 일치해야합니다.',
    'size'                 => [
        'numeric' => ': size 여야합니다.',
        'file'    => ': KB 여야합니다.',
        'string'  => ': size 문자 여야합니다.',
        'array'   => ': size 항목이 포함되어야합니다.',
    ],
    'string'               => ': 문자열이어야합니다.',
    'timezone'             => ': 유효한 영역이어야합니다.',
    'unique'               => '그 :attribute 은 이미 사용되고있습니다.',
    'url'                  => ': 형식이 잘못되었습니다.',
    'captcha'              => 'reCAPTCHA 값이 잘못되었습니다.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => '맞춤 메시지',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

    'attributes' => [],

];

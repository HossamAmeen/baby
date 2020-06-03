<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | هذا following language lines contain هذا default error messages used by
    | هذا validator class. Some of هذاse rules have multiple versions such
    | as هذا size rules. Feel free to tweak each of هذاse messages here.
    |
    */

    'accepted'             => 'هذه :attribute يجب ان تقبل.',
    'active_url'           => 'هذا :attribute ليس رابط صالح.',
    'after'                => 'هذا :attribute التاريخ يجب ان يكون بعد :date.',
    'alpha'                => 'هذا :attribute يجب ان يحتوى على احرف فقط.',
    'alpha_dash'           => 'هذا :attribute يمكن ان يحتوى على أحرف ,أرقام, شرطة.',
    'alpha_num'            => 'هذا :attribute يجب ان يحتوى على أحرف وأرقام فقط.',
    'array'                => 'هذا :attribute يجب ان يكون على شكل مصفوفة.',
    'before'               => 'هذا :attribute التاريخ يجب أن يكون قبل :date.',
    'between'              => [
        'numeric' => 'هذا :attribute يجب ان يكون بين :min و :max.',
        'file'    => 'هذا :attribute يجب ان يكون بين :min and :max kilobytes.',
        'string'  => 'هذا :attribute يجب ان يكون بين :min and :max أحرف.',
        'array'   => 'هذا :attribute يجب ان يكون بين :min and :max عناصر.',
    ],
    'boolean'              => 'هذا :attribute الحقل يجب ان يكون true or false.',
    'confirmed'            => 'هذا :attribute التأكيد غير متطابق.',
    'date'                 => 'هذا :attribute تاريخ غير صحيح.',
    'date_format'          => 'هذا :attribute لا يتوافق مع الصيغة :format.',
    'different'            => 'هذا :attribute و :oهذاr يجب ان يكونوا مختلفين.',
    'digits'               => 'هذا :attribute يجب ان يكون be :digits رقمى.',
    'digits_between'       => 'هذا :attribute يجب ان يكون بين :min و :max الارقام.',
    'distinct'             => 'هذا :attribute الحقل له قيمة مكررة.',
    'email'                => 'هذا :attribute يجب ان يكون بريد الكترونى.',
    'exists'               => 'هذا الاختيار :attribute غير صالح.',
    'filled'               => 'هذا :attribute الحق مطلوب.',
    'image'                => 'هذا :attribute يجب ان يكون صورة.',
    'in'                   => 'هذا الاختيار :attribute غير صالح.',
    'in_array'             => 'هذا :attribute الحقل لا يوجد فى :oهذاr.',
    'integer'              => 'هذا :attribute يجب ان يكون صحيحاً.',
    'ip'                   => 'هذا :attribute يجب ان يكون عنوانIP صحيح.',
    'json'                 => 'هذا :attribute يجب ان يكون valid JSON string.',
    'max'                  => [
        'numeric' => 'هذا :attribute قد لا تكون أكبر من :max.',
        'file'    => 'هذا :attribute قد لا تكون أكبر من :max kilobytes.',
        'string'  => 'هذا :attribute قد لا تكون أكبر من :max احرف.',
        'array'   => 'هذا :attribute قد لا تحتوى على عدد اكبر من :max عناصر.',
    ],
    'mimes'                => 'هذا :attribute يجب ان يكون من نوع :values.',
    'min'                  => [
        'numeric' => 'هذا :attribute لا بد أن يكون على الأقل :min.',
        'file'    => 'هذا :attribute لا بد أن يكون على الأقل :min kilobytes.',
        'string'  => 'هذا :attribute لا بد أن يكون على الأقل :min احرف.',
        'array'   => 'هذا :attribute لابد ان يكون على الأقل :min عناصر.',
    ],
    'not_in'               => 'هذا الاختيار :attribute غير صالح.',
    'numeric'              => 'هذا :attribute يجب ان يكون رقم.',
    'present'              => 'هذا :attribute الحقل يجب ان يكون موجود.',
    'regex'                => 'هذا :attribute الصيغة غير صالحة.',
    'required'             => 'هذا :attribute الحقل مطلوب.',
    'required_if'          => 'هذا :attribute الحقل مطلوب عندما :other يكون :value.',
    'required_unless'      => 'هذا :attribute الحقل مطلوب على الاقل :other  يكون فى :values.',
    'required_with'        => 'هذا :attribute الحقل مطلوب عندما :values موجود.',
    'required_with_all'    => 'هذا :attribute الحقل مطلوب عندما :values موجود.',
    'required_without'     => 'هذا :attribute الحقل مطلوب عندما :values غير موجود.',
    'required_without_all' => 'هذا :attribute الحقل مطلوب عندما لا احد من :values موجود',
    'same'                 => 'هذا :attribute و :other يجب ان يكونوا متطابقين.',
    'size'                 => [
        'numeric' => 'هذا :attribute يجب ان يكون :size.',
        'file'    => 'هذا :attribute يجب ان يكون :size kilobytes.',
        'string'  => 'هذا :attribute يجب ان يكون :size احرف.',
        'array'   => 'هذا :attribute يجب ان يكون :size عناصر.',
    ],
    'string'               => 'هذا :attribute string ان يكون احرف.',
    'timezone'             => 'هذا :attribute يجب ان يكون منطقة.',
    'unique'               => 'هذا :attribute محجوزة بالفعل.',
    'url'                  => 'هذا :attribute الصيغة غير صالحة.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using هذا
    | convention "attribute.rule" to name هذا lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'رسالة مخصصة',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | هذا following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

    'attributes' => [],

];

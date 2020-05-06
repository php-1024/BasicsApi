<?php

namespace App\Http\Requests\Merchant;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class EditMobile extends FormRequest
{
    /**
     * 确定用户是否有权提出此请求。
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * 请求失败返回json数据
     * @param Validator $validator
     */
    protected function failedValidation(Validator $validator)
    {
        throw (new HttpResponseException(response()->json([
            'code' => 50000,
            'message' => $validator->errors()->first()
        ], 200)));
    }

    /**
     * 获取应用于请求的验证规则。
     *
     * @return array
     */
    public function rules()
    {
        return [
            'old_mobile' => 'required|numeric|digits_between:11,11',
            'old_code' => 'required|numeric',
            'new_mobile' => 'required|numeric|digits_between:11,11',
            'new_code' => 'required|numeric'
        ];
    }


    /**
     * 获取已定义的验证规则的错误消息。
     * @return array
     */
    public function messages()
    {
        return [
            'old_mobile.required' => '原手机号码不能为空！',
            'old_mobile.numeric' => '原手机号码格式不正确！',
            'old_mobile.digits_between' => '原手机号码格式不正确，必须为11位！',
            'old_code.required' => '原手机验证码不能为空！',
            'old_code.numeric' => '原手机验证码格式不正确！',
            'new_mobile.required' => '新手机号码不能为空！',
            'new_mobile.numeric' => '新手机号码格式不正确！',
            'new_mobile.digits_between' => '新手机号码格式不正确，必须为11位！',
            'new_code.required' => '新手机验证码不能为空！',
            'new_code.numeric' => '新手机验证码格式不正确！'
        ];
    }
}

<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class Merchant extends FormRequest
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
            'company' => 'required',
            'mobile' => 'required|numeric|digits_between:11,11',
            'password' => 'required',
            'username' => 'required',
        ];
    }


    /**
     * 获取已定义的验证规则的错误消息。
     * @return array
     */
    public function messages()
    {
        return [
            'company.required' => '请输入商户名称！',
            'username.required' => '请输入商户账号！',
            'mobile.required' => '手机号码不能为空！',
            'mobile.numeric' => '手机号码格式不正确！',
            'mobile.digits_between' => '手机号码格式不正确，必须为11位！',
            'password.required' => '请输入登录密码！'
        ];
    }
}

<?php

namespace App\Http\Requests\Merchant;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class GetSignature extends FormRequest
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
            'code'    => 50000,
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
            'develop_appid' => 'required',
            'timestamp'     => 'required',
            'nonce'         => 'required',
            'signature'     => 'required',
        ];
    }


    /**
     * 获取已定义的验证规则的错误消息。
     * @return array
     */
    public function messages()
    {
        return [
            'develop_appid.required' => '开发APPID不能为空！',
            'timestamp.required'     => '时间戳不能为空',
            'nonce.required'         => '随机字符串不能为空',
            'signature.required'     => '签名不能为空！',
        ];
    }
}

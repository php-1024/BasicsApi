<?php

namespace App\Http\Requests\Merchant;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class GetQrCode extends FormRequest
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
            'develop_appid' => 'required',
            'key' => 'required',
            'auth_open_id' => 'required',
            'nickname' => 'required',
            'timestamp' => 'required',
            'nonce' => 'required',
            'signature' => 'required',
            'ip' => 'required|ip'
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
            'key.required' => '设备key值不能为空！',
            'auth_open_id.required' => '用户授权的open_id不能为空',
            'nickname.required' => '用户授权的昵称不能为空',
            'timestamp.required' => '时间戳不能为空',
            'nonce.required' => '随机字符串不能为空',
            'signature.required' => '签名不能为空！',
            'ip.required' => '请传入粉丝的IP地址！',
            'ip.ip' => 'IP地址不正确',
        ];
    }
}

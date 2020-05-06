<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class Coupons extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
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
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required',
            'price' => 'required|numeric',
            'full_price' => 'required',
            'expire' => 'required',
            'num' => 'required',
        ];
    }

    /**
     * 获取已定义的验证规则的错误消息。
     * @return array
     */
    public function messages()
    {
        return [
            'name.required' => '请输入优惠券名称！',
            'price.required' => '请输入优惠券金额！',
            'price.numeric' => '优惠券金额格式不正确！',
            'full_price.required' => '请设置消费条件！',
            'full_price.numeric' => '消费条件填写有误，请重新设置！',
            'expire.required' => '请设置过期时间！',
            'num.required' => '请设置优惠券数量！',
        ];
    }
}

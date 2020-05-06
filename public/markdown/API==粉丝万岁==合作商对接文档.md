#### 接口目录
###### <a href="#签名">签名</a>
###### <a href="#获取公众号二维码">1. 获取公众号二维码</a>
###### <a href="#获取所有场景">2. 获取所有场景</a>
###### <a href="#获取所有城市">3. 获取所有城市</a>
###### <a href="#消息推送">4.消息推送</a>
###### <a href="#返回码说明">4.返回码说明</a>


###### <a href="https://note.youdao.com/ynoteshare1/index.html?id=7baaa118ee964b88a1b6d3ab1f0b450b&type=note" target="view_window">在有道云中查看</a>


---


#####  签名

###### 介绍
> 校验签名signature(必须)，例如如下操作

```php
    $array = [
      'timestamp',  //时间戳
      'nonce', //随机字符串
      'app_secret' //app_secret
    ];
    sort($array,SORT_STRING);
    $str = implode($arr);
    $signature = md5($str);
```


#####  获取公众号二维码

###### 接口功能
> 二维码是根据当前合作商机器粉丝的属性，进行定向筛选的，如果没有获取的到二维码可以自行显示其他的二维码。

###### URL
> 接口地址：https://api.fensiwansui.com/open/commercial/get_qrcode


###### 请求方式
> post | get


###### 请求参数
> | 字段名 | 变量名 | 必填 | 类型 | 默认值 | 实例值 | 描述 |
> | :---: | :---: | :---: | :---: | :---: | :---: | :---: |
> | 设备key值         | key           | 是 | string  | 无 | 3e3add4a720c11e9ab2100163e0488d6  | 设备的对应key |
> | 开发者APPID       | develop_appid | 是 | string  | 无 | jbh200453e0488d6                  | 九瓣花合作商开发者的appid |
> | 时间戳            | timestamp     | 是 | number  | 无 | 1557200022                        | 用于签名的时间戳 |
> | 随机字符串        | nonce         | 是 | string  | 无 | jbh12345                          | 用于签名的随机字符串 |
> | 签名              | signature     | 是 | string  | 无 | e06416850b0439ec3bf57443b6db92fd  | 签名后的字符串，获取方式详见接口权限文档 |
> | 用户授权的open_id | auth_open_id  | 是 | varchar | 无 | oleiaYv6i50I7ADTlmIGN4WO-2RGM     | 用户授权后的open_id |
> | 用户昵称          | nickname      | 是 | varchar | 无 | 追梦小窝                          | 用户昵称 |
> | 用户性别          | sex           | 是 | int     | 无 | 0                                 | 传入性别，匹配相应的公众号任务0：不限，1：男粉，2：女粉 |
> | 用户的IP          | ip            | 是 | varchar | 无 | 183.13.188.125                    | 粉丝的IP地址 |
> | 场景ID          | label         | 否 | int   | 无 | 1                           | 场景id,可以通过获取场景接口匹配出id值然后传递过来 |


######   请求实例：：
```json
{
  "key":"3e3add4a720c11e9ab2100163e0488d6",
  "develop_appid":"jbh200453e0488d6",
  "timestamp":1557200022,
  "nonce":"jbh12345",
  "signature":"e06416850b0439ec3bf57443b6db92fd",
  "auth_open_id":"oleiaYv6i50I7ADTlmIGN4WO-2RGM",
  "nickname":"追梦小窝",
  "sex": 0,
  "ip":"183.13.188.125",
  "label":1
}
```
###### 响应参数：
> |字段名|变量名|类型|实例值|描述|
>|:---:|:---:|:---:|:---:|:---:|
>|返回码            | code          | int    | 20000                            | 接口返回状态     |
>|设备key           | key           | string | 3e3add4a720c11e9ab2100163e0488d6 | 接口返回状态说明 |
>|开发者appid       | develop_appid | string | jbh200163e0488d6                 | 开发者传入的develop_appid |
>|用户昵称          | nickname      | string | 追梦小窝                         | 返回传入的用户昵称 |
>|城市id            | city_code     | int    | null                             | 返回城市id，根据城市列表接口可以查找城市信息 |
>|用户授权的open_id | auth_open_id  | string | oleiaYv6i50I7ADTlmIGN4WO-2RGM    | 传入的授权用户openid |
>|出价              | bidding       | int    | 3                                | 当前公众号的吸粉价格，单位为分 |
>|公众号appid       | appid         | string | wx3cc413efaf3b1922               | 当前公众号的appid |
>|公众号名称        | appname       | string | 七彩东莞                         | 当前公众号的名称 |
>|验证码        | code       | string | null                         | 关注公众号需要回复验证码才可以触发模板消息，一般是未认证的公众号需要，返回为空时则关注后是可以直接触发模板消息的 |
>|公众号二维码      | qrcode_url    | string |                                  | 当前公众号的二维码地址 |
>|过期时间          | expire        | string |                                  | 过期时间 |
>|返回码含义 | message | varchar | ok       | 接口返回状态说明 |

######   响应实例：：
```json
{
    "code": 20000,
    "data": {
        "key": "3e3add4a720c11e9ab2100163e0488d6",
        "develop_appid": "jbh200163e0488d6",
        "nickname": "追梦小窝",
        "city_code": null,
        "auth_open_id": "oleiaYv6i50I7ADTlmIGN4WO-2RGM",
        "bidding": 3,
        "appid": "wx3cc413efaf3b1922",
        "appname": "七彩东莞",
        "code": null,
        "expire": 120,
        "qrcode_url": "http://mmbiz.qpic.cn/mmbiz_jpg/2Uw5rqyC73bhPJNPtxnoP9rVhJmbM4ib7VQXKt7rbLicfGHfsibHeHZiaIqDBiae3LTtfBl2BYaWOsGS4aibAVrR5g2Q/0"
    },
    "message": "ok"
}
```

---


#####  获取所有场景

###### 接口功能
> 获取的场景，是要跟合作商的场景做同步，获取公众号二维码的时候需要携带场景ID可以获取更精准的用户，避免用户属性偏差，导致粉丝利用率低。

###### URL
> 接口地址：https://api.fensiwansui.com/open/commercial/get_scene


###### 请求方式
> post | get

###### 请求参数
> | 字段名 | 变量名 | 必填 | 类型 | 默认值 | 实例值 | 描述 |
> | :---: | :---: | :---: | :---: | :---: | :---: | :---: |
> | 开发者APPID       | develop_appid | 是 | string  | 无 | jbh200163e0488d6                  | 九瓣花合作商开发者的appid |
> | 时间戳            | timestamp     | 是 | number  | 无 | 1557200022                        | 用于签名的时间戳 |
> | 随机字符串        | nonce         | 是 | string  | 无 | jbh12345                          | 用于签名的随机字符串 |
> | 签名              | signature     | 是 | string  | 无 | e06416850b0439ec3bf57443b6db92fd  | 签名后的字符串，获取方式详见接口权限文档 |


######   请求实例：：
```json
{
  "develop_appid": "wx3cc413efaf3b1922",
  "timestamp":1547256419,
  "nonce":"jbh12345",
  "signature":"e06416850b0439ec3bf57443b6db92fd"
}
```
###### 响应参数：
> |字段名|变量名|类型|实例值|描述|
>|:---:|:---:|:---:|:---:|:---:|
>|返回码            | code    | int     | 20000                            | 接口返回状态     |
>|返回数据          | data    | array   | [] | 接口返回状态说明 |
>|返回id            | id      | int     | 1                                | 场景id,可以用来请求二维码时使用 |
>|场景名称          | name    | string  | 学校                             | 返回场景名称 |
>|返回码含义        | message | varchar | ok                               | 接口返回状态说明 |

######   响应实例：
```json
{
    "code": 20000,
    "data": [
        {
            "id": 1,
            "name": "学校"
        },
        {
            "id": 2,
            "name": "药店"
        },
        {
            "id": 3,
            "name": "餐饮"
        },
        {
            "id": 4,
            "name": "影院"
        },
        {
            "id": 5,
            "name": "商场"
        },
        {
            "id": 6,
            "name": "超市"
        },
        {
            "id": 7,
            "name": "医疗机构"
        },
        {
            "id": 8,
            "name": "母婴店"
        },
        {
            "id": 9,
            "name": "其他"
        }
    ],
    "message": "ok"
}
```




---


#####  获取所有城市

###### 接口功能
> 获取的城市，是要跟合作商的城市做同步，获取公众号二维码的时候需要携带城市ID可以获取更精准的用户，避免用户属性偏差，导致粉丝利用率低。

###### URL
> 接口地址：https://api.fensiwansui.com/open/commercial/get_city


###### 请求方式
> post | get

###### 请求参数
> | 字段名 | 变量名 | 必填 | 类型 | 默认值 | 实例值 | 描述 |
> | :---: | :---: | :---: | :---: | :---: | :---: | :---: |
> | 开发者APPID       | develop_appid | 是 | string  | 无 | jbh200163e0488d6                  | 九瓣花合作商开发者的appid |
> | 时间戳            | timestamp     | 是 | number  | 无 | 1557200022                        | 用于签名的时间戳 |
> | 随机字符串        | nonce         | 是 | string  | 无 | jbh12345                          | 用于签名的随机字符串 |
> | 签名              | signature     | 是 | string  | 无 | e06416850b0439ec3bf57443b6db92fd  | 签名后的字符串，获取方式详见接口权限文档 |


######   请求实例：：
```json
{
  "develop_appid": "wx3cc413efaf3b1922",
  "timestamp":1547256419,
  "nonce":"jbh12345",
  "signature":"e06416850b0439ec3bf57443b6db92fd"
}
```
###### 响应参数：
> |字段名|变量名|类型|实例值|描述|
>|:---:|:---:|:---:|:---:|:---:|
>|返回码            | code    | int     | 20000 | 接口返回状态     |
>|返回数据          | data    | array   | []    | 接口返回状态说明 |
>|返回id            | id      | int     | 1     | 场景id,可以用来请求二维码时使用 |
>|场景名称          | name    | string  | 学校  | 返回场景名称 |
>|返回码含义        | message | varchar | ok    | 接口返回状态说明 |

######   响应实例：：
```json
{
    "code": 20000,
    "data": [
        {
            "id": 1,
            "name": "北京市"
        },
        {
            "id": 2,
            "name": "天津市"
        },
        {
            "id": 3,
            "name": "石家庄市"
        },
        {
            "id": 4,
            "name": "唐山市"
        }
        .......
    ],
    "message": "ok"
}
```


---


#####  消息推送

###### 接口功能
> 当用户关注的时候，平台将按照设备所填写的模板消息进行回复用户，同时会推送一条json数据包到开发权限=》基本配置=》服务器配置的url上。


###### 消息推送
> 平台将向合作商所填写的URL推送一个json数据包

```json
{
  "server_url":"https://api.fensiwansui.com",  
  "key":"3e3add4a720c11e9ab2100163e0488d6",  
  "develop_appid":"jbh200453e0488d6",
  "auth_open_id":"oevvk1OpIfu26jDsdfdkksfsdtosJnVBVMolwk",
  "bidding": 3, 
  "appid":"wx3cc413efaf3b1922", 
  "appname":"七彩东莞",
  "openid":"oleiaYv6i50I7ADTlmIGN4WO-2RGM",
  "nickname":"",
}
```
###### 推送参数说明：
> |字段名|描述|类型|
>|:---:|:---:|:---:|
>|server_url    | 开发者服务器url     | string |
>|key           | 设备key值           | string |
>|develop_appid | 开发者develop_appid | string |
>|auth_open_id  | 用户授权的openid    | string |
>|nickname      | 粉丝昵称            | string |
>|bidding       | 粉丝价格            | int    |
>|appid         | 公众号appid         | string |
>|appname       | 公众号名称          | string |
>|openid        | 用户关注的openid    | string |


#####  返回码说明

> |状态码|状态码说明|
>|:---:|:---:|
>|20000 | 成功 | 
>|50000 | 暂无匹配的公众号！ |
>|50001 | 对不起，您当前匹配到的公众号吸粉客户的钱包余额不足！ |
>|50002 | 生成二维码失败请稍后再试！ |
>|50003 | 对不起您的app_secret不正确，请您进入后台重新初始化 |
>|50004 | signature校验错误 |

###### <a href="#接口目录">跳回顶部</a>

---
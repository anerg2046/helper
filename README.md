# 我自己常用的一些扩展类库

> 更新完善中
> 以下类库都在`\\anerg\\helper`命名空间下

## Snowflake
> twitter的分布式全局唯一ID生成器PHP实现

```
//两个方法均可携带2个参数，第一个是机器编号，如果为0，则生成的id会忽略机器标识段
//第二个是数组，可用键值epoch，maxMachineBit，maxRandomBit
//具体请查询代码

// 获取一个10进制的唯一ID
Snowflake::nextId(1)

// 获取一个36进制的唯一ID
Snowflake::nextHash(1)

```

## Http
> CURL相关操作

```
// 发起请求,get/post
Http::request($url, $data = null, $method = 'get', $params = null)

// get方式请求
Http::get($url, $data = null, $params = null)

// post方式请求
Http::post($url, $data = null, $params = null)

// post方式发送原始数据
Http::postRaw($url, $raw, $params = null)

// 带ssl证书请求原始数据
// $params数组必须包含cert_path,key_path,ca_path三个证书地址
Http::postRawSsl($url, $raw, $params)

//下载图片到指定位置
Http::saveImage($url, $path, $filename = null, $params = null)

```

## Xml
> XML与Array相关操作

```
// xml转为数组
Xml::xmlToArr($xml)

// 数组转为xml
Xml::arrToXml($array)

```

## Encrypt
> 可逆加解密类,默认仅支持TP5,如果要支持其他,请修改getKey方法

```
// 加密字符串/数组
Encrypt::encrypt($data, $expire = 0, $key = '')

// 解密字符串
Encrypt::decrypt($data, $key = '')

```

## Time
> 时间相关操作

```
// 输出优化后的时间显示格式
Time::optimization($time)

```

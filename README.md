# 思智捷管理系统工具类
##### 思智捷管理系统文章模块(版本要求2.3+)
### Arcitle类(szj\utils\Arcitle)
	文章搜索
	seachArcitle($keywords = '',$options = [])
	
|  参数名称 |  类型 | 默认值  | 描述  |
| ------------ | ------------ | ------------ | ------------ |
|  keywords | string  |  '' | 文章标题关键字  |
|  options | array  |  空 | 其它参数  |
|  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;where | array  |  空 | 其它条件  |
|  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;order | string  |  sort_order,id desc | 排序  |
|  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;field | string  |  true | 需要的字段，默认所有字段  |
|  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;p | int  |  1 | 当前页  |
|  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;pagelist | int  |  15 | 每页显示条数  |

	默认分页样式搜索
	seachDefaultArcitle($keywords = '',$options = [])
	
|  参数名称 |  类型 | 默认值  | 描述  |
| ------------ | ------------ | ------------ | ------------ |
|  keywords | string  |  '' | 文章标题关键字  |
|  options | array  |  空 | 其它参数  |
|  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;where | array  |  空 | 其它条件  |
|  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;order | string  |  sort_order,id desc | 排序  |
|  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;field | string  |  true | 需要的字段，默认所有字段  |
|  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;pagelist | int  |  15 | 每页显示条数  |

	获取分类下的所有文章列表
	getCateArcitleList($cid = 0,$options = [])
	
|  参数名称 |  类型 | 默认值  | 描述  |
| ------------ | ------------ | ------------ | ------------ |
|  cid | int  |  0 | 文章分类cid  |
|  options | array  |  空 | 其它参数  |
|  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;where | array  |  空 | 其它条件  |
|  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;order | string  |  sort_order,id desc | 排序  |
|  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;field | string  |  true | 需要的字段，默认所有字段  |
|  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;p | int  |  1 | 当前页  |
|  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;pagelist | int  |  15 | 每页显示条件  |

	获取分类下的所有文章列表(自带思智捷管理系统分页功能)
	getDefaultCateArcitleList($cid = 0,$options = [])
	
|  参数名称 |  类型 | 默认值  | 描述  |
| ------------ | ------------ | ------------ | ------------ |
|  cid | int  |  0 | 文章分类cid  |
|  options | array  |  空 | 其它参数  |
|  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;where | array  |  空 | 其它条件  |
|  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;order | string  |  sort_order,id desc | 排序  |
|  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;field | string  |  true | 需要的字段，默认所有字段  |
|  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;pagelist | int  |  15 | 每页显示条件  |

	获取文章分类的是单页的文章详情
	getSingleCidArcitle($cid = 0,$options = [])
	
|  参数名称 |  类型 | 默认值  | 描述  |
| ------------ | ------------ | ------------ | ------------ |
|  cid | int  |  0 | 文章分类cid  |
|  options | array  |  空 | 其它参数  |
|  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;where | array  |  空 | 其它条件  |
|  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;order | string  |  sort_order,id desc | 排序  |
|  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;field | string  |  true | 需要的字段，默认所有字段  |

	获取文章详情
	getArcitleInfo($id = 0,$options = [])
	
|  参数名称 |  类型 | 默认值  | 描述  |
| ------------ | ------------ | ------------ | ------------ |
|  id | int  |  0 | 文章id  |
|  options | array  |  空 | 其它参数  |
|  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;field | string  |  true | 需要的字段，默认所有字段  |

	获取所有需要首页显示的文章
	getHomeAllArcitle($options = [])
	
|  参数名称 |  类型 | 默认值  | 描述  |
| ------------ | ------------ | ------------ | ------------ |
|  options | array  |  空 | 其它参数  |
|  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;where | array  |  空 | 其它条件  |
|  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;order | string  |  sort_order,id desc | 排序  |
|  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;field | string  |  true | 需要的字段，默认所有字段  |
|  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;limit | mixed  |  [0,10] | 获取条数  |

	获取当前文章的一篇和下一篇
	getArcitleNextPrev($id = 0,$options = [])
	
|  参数名称 |  类型 | 默认值  | 描述  |
| ------------ | ------------ | ------------ | ------------ |
|  id | int  |  0 | 文章id  |
|  options | array  |  空 | 其它参数  |
|  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;where | array  |  空 | 其它条件  |
|  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;field | string  |  true | 需要的字段，默认所有字段  |

### ArcitleCategory类(szj\utils\ArcitleCategory)

	获取当前文章分类的所有父节点列表
	getRecursionParentCategory($cid = 0,$options = [])
	
|  参数名称 |  类型 | 默认值  | 描述  |
| ------------ | ------------ | ------------ | ------------ |
|  cid | int  |  0 | 文章分类cid  |
|  options | array  |  空 | 其它参数  |
|  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;field | string  |  true | 需要的字段，默认所有字段  |

	递归获取所有的文章分类下的某分类下所有子节点
	getRecursionChildCategory($pid = 0,$options = [])
	
|  参数名称 |  类型 | 默认值  | 描述  |
| ------------ | ------------ | ------------ | ------------ |
|  pid | int  |  0 | 父级分类ID  |
|  options | array  |  空 | 其它参数  |
|  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;field | string  |  true | 需要的字段，默认所有字段  |

	获取文章分类单个分类的详情
	getCategoryInfo($cid = 0,$options = [])
	
|  参数名称 |  类型 | 默认值  | 描述  |
| ------------ | ------------ | ------------ | ------------ |
|  cid | int  |  0 | 文章分类cid  |
|  options | array  |  空 | 其它参数  |
|  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;field | string  |  true | 需要的字段，默认所有字段  |

	获取文章所有分类列表
	getCategory($options = [])
	
|  参数名称 |  类型 | 默认值  | 描述  |
| ------------ | ------------ | ------------ | ------------ |
|  options | array  |  空 | 其它参数  |
|  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;where | array  |  空 | 其它条件  |
|  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;field | string  |  true | 需要的字段，默认所有字段  |
|  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;limit | mixed  |  [0,15] | 获取条数  |
|  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;order | string  |  sort_order| 排序  |

	获取所有导航文章分类
	getNavCategory($options = [])
	
|  参数名称 |  类型 | 默认值  | 描述  |
| ------------ | ------------ | ------------ | ------------ |
|  options | array  |  空 | 其它参数  |
|  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;where | array  |  ['is_nav'=>1,'is_show'=>1] | 其它条件  |
|  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;field | string  |  true | 需要的字段，默认所有字段  |
|  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;limit | mixed  |  [0,15] | 获取条数  |
|  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;order | string  |  sort_order| 排序  |

	获取文章分类是单页的分类列表
	getSingleCategory($options = [])
	
|  参数名称 |  类型 | 默认值  | 描述  |
| ------------ | ------------ | ------------ | ------------ |
|  options | array  |  空 | 其它参数  |
|  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;where | array  |  ['cate_type'=>1] | 其它条件  |
|  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;field | string  |  true | 需要的字段，默认所有字段  |
|  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;limit | mixed  |  [0,15] | 获取条数  |
|  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;order | string  |  sort_order| 排序  |

	获取文章分类是列表的分类列表
	getListCategory($options = [])
	
|  参数名称 |  类型 | 默认值  | 描述  |
| ------------ | ------------ | ------------ | ------------ |
|  options | array  |  空 | 其它参数  |
|  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;where | array  |  ['cate_type'=>0] | 其它条件  |
|  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;field | string  |  true | 需要的字段，默认所有字段  |
|  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;limit | mixed  |  [0,15] | 获取条数  |
|  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;order | string  |  sort_order| 排序  |

### Baidu类（szj\utils\Baidu）
	构造函数
	construct($config = array())
|  参数名称 | 类型  | 默认值  |  说明 |
| ------------ | ------------ | ------------ | ------------ |
|  $config | array  | 空  | 传入配置  |
|  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;set_userimg_name | string  |  时间戳.jpg | 用户头像保存名称  |
|  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;set_userimg_path | string  |  ./ | 用户头像保存路径  |
|  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;appid | string  |  '' | 百度appid  |
|  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;secret | string  |  '' | 百度secret  |

	获取身份证识别后的结果
	IdCard($imgurl = '',$userimg = false,$detect = true,$isside = true)
|  参数名称 | 类型  | 默认值  |  说明 |
| ------------ | ------------ | ------------ | ------------ |
|  $imgurl | string  | 空  | 身份证图片路径  |
|  $userimg | bool  | false  | 是否需要用户头像  |
|  $detect | bool  | true  | 是否判断身份证真伪  |
|  $isside | bool  | true  | 正面true 反面false  |

	通用文字识别器
	CommonOrc($imgurl = '')
|  参数名称 | 类型  | 默认值  |  说明 |
| ------------ | ------------ | ------------ | ------------ |
|  $imgurl | string  | 空  | 图片路径  |

	户口本个人页识别器
	HouseReg($imgurl = '',$options = [])
|  参数名称 | 类型  | 默认值  |  说明 |
| ------------ | ------------ | ------------ | ------------ |
|  $imgurl | string  | 空  | 户口本个人页图片路径  |
|  $options | array  | 空  | 其它参数  |
|  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;tid | string  |  232a744776294938469b3c442dcecac0 |   无 |
|  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;cid | int  |  1 |   无 |

	户口本首页信息识别
	HouseIndex($imgurl = '',$options = [])
|  参数名称 | 类型  | 默认值  |  说明 |
| ------------ | ------------ | ------------ | ------------ |
|  $imgurl | string  | 空  | 户口本首页图片路径  |
|  $options | array  | 空  | 其它参数  |
|  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;tid | string  |  1bc64f846180ac6d768a8a6dfca1151c |   无 |
|  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;cid | int  |  0 |   无 |

	广东省居住证信息识别
	gdResideCard($imgurl = '',$options = [])
|  参数名称 | 类型  | 默认值  |  说明 |
| ------------ | ------------ | ------------ | ------------ |
|  $imgurl | string  | 空  | 居住证图片路径  |
|  $options | array  | 空  | 其它参数  |
|  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;tid | string  |  e678de49ef660977cd536cfd4522cc43 |   无 |
|  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;cid | int  |  0 |   无 |

	广东省河源市社保卡识别
	hyEnsure($imgurl = '',$options = [])
|  参数名称 | 类型  | 默认值  |  说明 |
| ------------ | ------------ | ------------ | ------------ |
|  $imgurl | string  | 空  | 社保卡图片路径  |
|  $options | array  | 空  | 其它参数  |
|  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;tid | string  |  168e2d1e1f28528ae550e40191324de8 |   无 |
|  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;cid | int  |  0 |   无 |

### Excel类（szj\utils\Excel）
	构造函数
	construct($conf = array())
|  参数名称 | 类型  | 默认值  |  说明 |
| ------------ | ------------ | ------------ | ------------ |
|  $conf | array  | 空  | 传入配置  |
|  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;horizontal | int  |  \PHPExcel_Style_Alignment::HORIZONTAL_CENTER | 默认水平居中  |
|  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;vertical | int  |  \PHPExcel_Style_Alignment::VERTICAL_CENTER| 默认垂直居中  |
|  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;fontName | string  |  宋体 | 字体名称  |
|  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;fontSize | int  |  12 | 字体大小  |
|  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;beforeExport | function  |  null | 导出前回调函数  |
|  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;defaultSheetTitle | string  |  demo | 默认导出excel表名称  |

	导出excel数据
	export($data = [],$dataTitle = [],$save = '',$type = 'Excel5')
|  参数名称 | 类型  | 默认值  |  说明 |
| ------------ | ------------ | ------------ | ------------ |
|  $data | array  | 空  | 需要导出的数据  |
|  $dataTitle | array  | 空  | 表头数组  |
|  $save | string  | 空  | 保存的文件(如果只是浏览器导出,无需设置)  |
|  $type | string  | Excel5  | 导出格式设置  |

	从excel中导入数据
	import($fileName = '',$defaultIndex = 0)
|  参数名称 | 类型  | 默认值  |  说明 |
| ------------ | ------------ | ------------ | ------------ |
|  $fileName | string  | 空  | 需要导入的excel文件名称  |
|  $defaultIndex | int  | 0  | 默认导入是第一张表  |

### Mailer类 （szj\utils\Mailer）
### 一、简单示例
```php
$Mailer = new \szj\utils\Mailer;
$title    = '测试发送';
$content  = '<h1>这是一封测试邮件</h1>';
$username = 'xxxxxx';//邮箱账号
$password = 'xxxxxx';//邮箱密码
$tomail = 'xxxxxxxx';//收件人账号 可以是数组或字符串(,)号分割
$result = $Mailer->send($title,$content,$username,$password,$tomail);
var_dump($result);
```

### 二、链式调用
```php
$Mailer = new \szj\utils\Mailer;
$title    = '测试发送';
$content  = '<h1>这是一封测试邮件</h1>';
$username = 'xxxxxx';//邮箱账号
$password = 'xxxxxx';//邮箱密码```php
$tomail   = 'xxxxxxxx';//收件人账号 可以是数组或字符串(,)号分割
$result = $Mailer->setTitle($title)->setContent($content)->setAccount($username,$password)->setAddress($tomail)->send();
var_dump($result);
```

### 三、构造函数传参调用
```php
$title    = '测试发送';
$content  = '<h1>这是一封测试邮件</h1>';
$username = 'xxxxxx';//邮箱账号
$password = 'xxxxxx';//邮箱密码
$tomail   = 'xxxxxxxx';//收件人账号 可以是数组或字符串(,)号分割
$config = [
	'Subject'=>$title,
	'Body'=>$content,
	'Username'=>$username,
	'Password'=>$password,
	'addAddress'=>$tomail,
	'FromName'=>'como'//发件人昵称
];
$Mailer = new \szj\utils\Mailer($config);
$result = $Mailer->send();
var_dump($result);
```

### Map类 （szj\utils\Map）
### 构造函数
#### 1、无参的构造函数
```php
$map = new \szj\utils\Map;
```
#### 2、有参数的构造函数
```php
$config = ['ak'=>'xxxxxxxxxxx'];
$map = new \szj\utils\Map($config);
```
### 一、地址转换成坐标点 
```php
$result = $map->GetLngLat('广东省河源市源城区永福农贸中心市场');
print_r($result);
```
##### GetLngLat($address,$city = '',$ak = '')

|  参数 | 类型  |  是否必须  |说明  |
| ------------ |  ------------ | ------------ | ------------ |
| $address  |  string |   是 |需要转换的地址  |
|  $city |  string  |  否 | 地址所在的城市  |
|  $ak |  string  |   否 |百度地图的ak值,如果构造函数传了ak参数，这里可以不传  |

### 二、判断一个坐标点是否在一个区域内
```php
$data = ['lng'=>114.686561,'lat'=>23.767418];
$points = [
	['lng'=>114.686561,'lat'=>23.767418],
	['lng'=>114.686561,'lat'=>23.767418],
	['lng'=>114.686561,'lat'=>23.767418],
	['lng'=>114.686561,'lat'=>23.767418]
];
$bool = $map->IsPoint($data,$points);
var_dump($bool);//如何在区域内 true/false
```
##### IsPoint($point, $pts)

|  参数 | 类型  | 是否必须  | 说明  |
| ------------ | ------------ | ------------ | ------------ |
|  $point | array  |  是 | 待查询的坐标点  |
|  $pts | array  | 是  |  区域坐标点 |

### 二、计算两点或多点之间的距离（可以是一对多或多对多）
```php
$orgStr = '23.766225,114.69726|23.743624,114.69854';
$destStr = '23.765124,114.690214|23.650109,114.666172|23.743624,114.69854';
$result = $map->Distance($orgStr,$destStr);
print_r($result);
```
##### Distance($org,$dest,$ak = '')

|  参数 | 类型  | 是否必须  | 说明  |
| ------------ | ------------ | ------------ | ------------ |
|  $org |  string/array | 是  | 起始坐标点（建议用string）  |
|  $dest | string/array  | 是  |  结束坐标点(建议用string) |
|  $ak |  string  |   否 |百度地图的ak值,如果构造函数传了ak参数，这里可以不传  |

### 微信功能类
#### 一、微信公众号接口类（szj\utils\wxSdk\WechatSignal）

> |  方法名称 |  参数个数 | 功能描述  |
| ------------ | ------------ | ------------ |
| checkSignature  | 1  |  微信开发者接口通信验证 |

> |  参数 | 类型  | 是否必须  | 说明  |
| ------------ | ------------ | ------------ | ------------ |
|  $token |  string | 是  | 微信验证的token  |

#### 二、微信支付接口类(szj\utils\wxSdk\WechatPay)

> |  方法名称 |  参数个数 | 功能描述  |
| ------------ | ------------ | ------------ |
| UnifiedOrder  | 1 |  支付统一下单接口 |

> |  参数 | 类型  | 是否必须  | 说明  |
| ------------ | ------------ | ------------ | ------------ |
|  $data |  array | 是  | 参数，具体信息查看下方  |
|  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;appid|  string | 是  | appid  |
|  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;body|  string | 是  | 商品信息描述  |
|  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;mch_id|  string | 是  | 商户ID号  |
|  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;openid|  string | 是  | 微信用户openid  |
|  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;out_trade_no|  string | 是  | 订单号  |
|  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;total_fee|  int | 是  | 订单金额（分）  |
|  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;trade_type|  string | 是  | 选值(JSAPI/NATIVE)  |
|  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;key|  string | 是  | 支付密钥 |
|  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;scene_info|  array | 否  | 支付场景说明(自定义数组) |
|  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;notify_url|  string | 否  | 默认值 (域名+index.php/index/Wechat/wxcallback |
|  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;time_start|  string | 否  | 当前时间 |
|  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;time_expire|  string | 否  | 当前时间 + 2小时 |


------------


> |  方法名称 |  参数个数 | 功能描述  |
| ------------ | ------------ | ------------ |
| CloseOrder  | 1 |  关闭订单接口 |

> |  参数 | 类型  | 是否必须  | 说明  |
| ------------ | ------------ | ------------ | ------------ |
|  $data |  array | 是  | 参数，具体信息查看下方  |
|  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;appid|  string | 是  | appid  |
|  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;mch_id|  string | 是  | 商户ID号  |
|  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;out_trade_no|  string | 是  | 订单号  |


------------


> |  方法名称 |  参数个数 | 功能描述  |
| ------------ | ------------ | ------------ |
| QueryOrder  | 1 |  订单查询接口 |

> |  参数 | 类型  | 是否必须  | 说明  |
| ------------ | ------------ | ------------ | ------------ |
|  $data |  array | 是  | 参数，具体信息查看下方  |
|  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;appid|  string | 是  | appid  |
|  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;mch_id|  string | 是  | 商户ID号  |
|  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;out_trade_no|  string | 是  | 订单号  |


------------


> |  方法名称 |  参数个数 | 功能描述  |
| ------------ | ------------ | ------------ |
| BillOrder  | 1 |  下载对账单(该功能不稳定 微信有时有 有时无的) |

> |  参数 | 类型  | 是否必须  | 说明  |
| ------------ | ------------ | ------------ | ------------ |
|  $data |  array | 是  | 参数，具体信息查看下方  |
|  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;appid|  string | 是  | appid  |
|  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;mch_id|  string | 是  | 商户ID号  |
|  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;bill_date|  string | 是  | 具体日期(20xx-xx-xx)  |


------------
> |  方法名称 |  参数个数 | 功能描述  |
| ------------ | ------------ | ------------ |
| RefundOrder  | 3 |  申请退款 |

> |  参数 | 类型  | 是否必须  | 说明  |
| ------------ | ------------ | ------------ | ------------ |
|  $data |  array | 是  | 参数，具体信息查看下方  |
|  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;appid|  string | 是  | appid  |
|  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;mch_id|  string | 是  | 商户ID号  |
|  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;out_trade_no|  string | 是  | 订单号  |
|  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;total_fee|  int | 是  | 订单金额(分)  |
|  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;refund_fee|  int | 是  | 退款金额(分)  |
|  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;notify_url|  string | 否  | 默认不接收回调  |
|  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;out_refund_no|  string | 否  | 退款单号  |
|  $cert |  string | 是  | 商户api证书  |
|  $key |  string | 是  | 商户api证书  |


------------
> |  方法名称 |  参数个数 | 功能描述  |
| ------------ | ------------ | ------------ |
| RefundQuery  | 1 |  退款信息查询 |

> |  参数 | 类型  | 是否必须  | 说明  |
| ------------ | ------------ | ------------ | ------------ |
|  $data |  array | 是  | 参数，具体信息查看下方  |
|  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;appid|  string | 是  | appid  |
|  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;mch_id|  string | 是  | 商户ID号  |
|  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;out_trade_no|  string | 是  | 订单号  |


------------


<?php
/**
 * |-----------------------------------------------------------------------------------
 * @Copyright (c) 2014-2018, http://www.sizhijie.com. All Rights Reserved.
 * @Website: www.sizhijie.com
 * @Version: 思智捷管理系统 1.5.0
 * @Author : como 
 * 版权申明：szjshop网上管理系统不是一个自由软件，是思智捷科技官方推出的商业源码，严禁在未经许可的情况下
 * 拷贝、复制、传播、使用szjshop网店管理系统的任意代码，如有违反，请立即删除，否则您将面临承担相应
 * 法律责任的风险。如果需要取得官方授权，请联系官方http://www.sizhijie.com
 * |-----------------------------------------------------------------------------------
 */
namespace szj\utils;
/**
 * 身份证类库
 */
Class Idcard {

	/**
	 * [__construct 构造函数]
	 * @Author    como
	 * @DateTime  2019-01-10
	 * @copyright 思智捷管理系统
	 * @version   [1.5.0]
	 */
	Public function __construct(){

	}
	/**
	 * [getSex 获取男女]
	 * @Author    como
	 * @DateTime  2019-01-10
	 * @copyright 思智捷管理系统
	 * @version   [1.5.0]
	 * @param     [type]     $idCardNo [description]
	 * @return    [type]               [description]
	 */
	Public static function getSex($idCardNo){
        if(empty($idCardNo)) return null; 
        $sexint = (int) substr($idCardNo, 16, 1);
        return $sexint % 2 === 0 ? '女' : '男';
	}
	/**
	 * [getBorn 获取身份证号生日]
	 * @Author    como
	 * @DateTime  2019-01-10
	 * @copyright 思智捷管理系统
	 * @version   [1.5.0]
	 * @param     [type]     $idCardNo [description]
	 * @return    [type]               [description]
	 */
	Public static function getBorn($idCardNo){
        if(empty($idCardNo)) return null; 
        $bir = substr($idCardNo, 6, 8);
        $year = (int) substr($bir, 0, 4);
        $month = (int) substr($bir, 4, 2);
        $day = (int) substr($bir, 6, 2);
        return $year . "-" . ($month < 10?'0'.$month:$month) . "-" . ($day < 10?'0'.$day:$day);
	}

    /**
     * [checkIdCard 检测身份证号是否正确并补全]
     * @Author    como
     * @DateTime  2019-01-10
     * @copyright 思智捷管理系统
     * @version   [1.5.0]
     * @param     [type]     $idc [description]
     * @return    [type]          [description]
     */
    Public static function checkIdCard($idc){
        if (strlen($idc) == 17) {
            $str = str_split($idc);
            $sum = 0;
            $xs = [7,9,10,5,8,4,2,1,6,3,7,9,10,5,8,4,2];
            foreach ($str as $k => $v) {
                $sum+=$v*$xs[$k];
            }
            $dy = [1,0,'X',9,8,7,6,5,4,3,2];
            $idc = $idc.$dy[$sum%11];
        }
        $rule = '/(^[1-9]\d{5}(18|19|([23]\d))\d{2}((0[1-9])|(10|11|12))(([0-2][1-9])|10|20|30|31)\d{3}[0-9Xx]$)|(^[1-9]\d{5}\d{2}((0[1-9])|(10|11|12))(([0-2][1-9])|10|20|30|31)\d{2}$)/';
        if (1 === preg_match($rule, (string) $idc)) {
            return $idc;
        } else {
            return false;
        }
    }

	/**
	 *  根据身份证号，返回对应的生肖
	 *  author:xiaochuan
	 *  @param string $idcard    身份证号码
	 */
	Public static function getZodiac($idcard){ //
	    if(empty($idcard)) return null;
	    $start = 1901;
	    $end = (int)substr($idcard, 6, 4);
	    $x = ($start - $end) % 12;
	    $val = '';
	    if ($x == 1 || $x == -11) $val = '鼠';
	    if ($x == 0)              $val = '牛';
	    if ($x == 11 || $x == -1) $val = '虎';
	    if ($x == 10 || $x == -2) $val = '兔';
	    if ($x == 9 || $x == -3)  $val = '龙';
	    if ($x == 8 || $x == -4)  $val = '蛇';
	    if ($x == 7 || $x == -5)  $val = '马';
	    if ($x == 6 || $x == -6)  $val = '羊';
	    if ($x == 5 || $x == -7)  $val = '猴';
	    if ($x == 4 || $x == -8)  $val = '鸡';
	    if ($x == 3 || $x == -9)  $val = '狗';
	    if ($x == 2 || $x == -10) $val = '猪';
	    return $val;
	}

	/**
	 *  根据身份证号，返回对应的星座
	 *  author:xiaochuan
	 *  @param string $idcard    身份证号码
	 */
	Public static function getStarsign($idcard){
	    if(empty($idcard)) return null;
	    $b = substr($idcard, 10, 4);
	    $m = (int)substr($b, 0, 2);
	    $d = (int)substr($b, 2);
	    $val = '';
	    if(($m == 1 && $d <= 21) || ($m == 2 && $d <= 19)){
	        $val = "水瓶座";
	    }else if (($m == 2 && $d > 20) || ($m == 3 && $d <= 20)){
	        $val = "双鱼座";
	    }else if (($m == 3 && $d > 20) || ($m == 4 && $d <= 20)){
	        $val = "白羊座";
	    }else if (($m == 4 && $d > 20) || ($m == 5 && $d <= 21)){
	        $val = "金牛座";
	    }else if (($m == 5 && $d > 21) || ($m == 6 && $d <= 21)){
	        $val = "双子座";
	    }else if (($m == 6 && $d > 21) || ($m == 7 && $d <= 22)){
	        $val = "巨蟹座";
	    }else if (($m == 7 && $d > 22) || ($m == 8 && $d <= 23)){
	        $val = "狮子座";
	    }else if (($m == 8 && $d > 23) || ($m == 9 && $d <= 23)){
	        $val = "处女座";
	    }else if (($m == 9 && $d > 23) || ($m == 10 && $d <= 23)){
	        $val = "天秤座";
	    }else if (($m == 10 && $d > 23) || ($m == 11 && $d <= 22)){
	        $val = "天蝎座";
	    }else if (($m == 11 && $d > 22) || ($m == 12 && $d <= 21)){
	        $val = "射手座";
	    }else if (($m == 12 && $d > 21) || ($m == 1 && $d <= 20)){
	        $val = "魔羯座";
	    }
	    return $val;
	}

}
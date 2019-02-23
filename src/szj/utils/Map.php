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
 * 地图处理库
 */
Class Map {

	Private $apiUrl = [
		'address'=>'http://api.map.baidu.com/geocoder/v2/?address=%s&output=json&ak=%s&city=%s',
		'distance'=>'http://api.map.baidu.com/routematrix/v2/walking?output=json&ak=%s&origins=%s&destinations=%s',
	];
	/**
	 * [$config 百度的ak值]
	 * @var [type]
	 */
	Private $config = [
		'ak'=>''
	];
	/**
	 * [$error 错误信息配置]
	 * @var array
	 */
	Private $error = [
		0=>'地址解析成功',
		1=>'服务器内部错误',
		2=>'请求参数非法',
		3=>'权限校验失败',
		4=>'今日已经用完免费额度的调用次数',
		5=>'接口ak值不存在或者非法',
		101=>'当前服务被禁用',
		102=>'不通过白名单或者安全码不对',
		999=>'网络请求失败,请联系管理员',
		998=>'系统出错了,请联系管理员'
	];


	/**
	 * [__construct 百度地图处理库]
	 * @Author    como
	 * @DateTime  2019-01-11
	 * @copyright 思智捷管理系统
	 * @version   [1.5.0]
	 */
	Public function __construct($config = []){
		$this->config = array_merge($this->config,$config);
	}
	/**
	 * [GetLngLat 通过地址获取经纬度]
	 * @Author    como
	 * @DateTime  2019-01-11
	 * @copyright 思智捷管理系统
	 * @version   [1.5.0]
	 * @param     string     $address [description]
	 */
	Public function GetLngLat($address,$city = '',$ak = ''){
		if(!empty($ak))
			$this->config['ak'] = $ak;
		$apiurl = sprintf($this->apiUrl['address'],$address,$this->config['ak'],$city);
		try{
			$res = curl_get($apiurl);
			$data = json_decode($res,true);
			if(empty($data)){
				$result = appResult($this->error[998]);
			} else {
				$status = $data['status'];
				if(empty($status)){
					$result = appResult($this->error[$status],$this->HanadleLngLat($data),false);
				} else {
					if(isset($this->error[$status]))
						$result = appResult($this->error[$status]);
					else
						$result = appResult('无调用权限或超出调用次数');
				}
			}			
		} catch(\Exception $err){
			$result = appResult($this->error[999]);
		}
		return $result;
	}
	/**
	 * [HanadleLngLat 处理返回结果]
	 * @Author    como
	 * @DateTime  2019-01-11
	 * @copyright 思智捷管理系统
	 * @version   [1.5.0]
	 * @param     array      $data [description]
	 */
	Private function HanadleLngLat(&$data = []){
		$tmp = [];
		if(isset($data['result']['location'])){
			$tmp = $data['result']['location'];
			$comprehension = intval($data['result']['comprehension']);
			if($comprehension == 100){
				$tmp['reliability'] = 0.91;
			} elseif($comprehension >= 90){
				$tmp['reliability'] = 0.89;
			} elseif($comprehension >= 80){
				$tmp['reliability'] = 0.88;
			} elseif($comprehension >= 70){
				$tmp['reliability'] = 0.84;
			} elseif($comprehension >= 60){
				$tmp['reliability'] = 0.81;
			} elseif($comprehension >= 50){
				$tmp['reliability'] = 0.79;
			} else {
				$tmp['reliability'] = 0;
			}
		}
		return $tmp;
	}
	/**
	 * [IsPoint 判断一个当前坐标点是否在区域内]
	 * @Author    como
	 * @DateTime  2019-01-11
	 * @copyright 思智捷管理系统
	 * 基本思想是利用射线法，计算射线与多边形各边的交点，如果是偶数，则点在多边形外，否则
	 * 在多边形内。还会考虑一些特殊情况，如点在多边形顶点上，点在多边形边上等特殊情况。
	 * 传值举例
	 * $point['lng'] = '116.453101';
	 * $point['lat'] = '39.966293';
	 * $arr[0]['lng'] = '116.319181';
	 * $arr[0]['lat'] = '39.969369';
	 * $arr[1]['lng'] = '116.453712';
	 * $arr[1]['lat'] = '39.967157';
	 * $arr[2]['lng'] = '116.456586';
	 * $arr[2]['lat'] = '39.868433';
	 * $arr[3]['lng'] = '116.326655';
	 * $arr[3]['lat'] = '39.86223';
	 * @version   [1.5.0]
	 * @param     [type]     $point [description]
	 * @param     [type]     $pts   [description]
	 */
	Public function IsPoint($point, $pts) {
	    $N 				= count($pts);
	    $boundOrVertex 	= true; 		//如果点位于多边形的顶点或边上，也算做点在多边形内，直接返回true
	    $intersectCount = 0;			//cross points count of x 
	    $precision 		= 2e-10; 		//浮点类型计算时候与0比较时候的容差
	    $p1 			= 0;			//neighbour bound vertices
	    $p2 			= 0;
	    $p 				= $point; 		//测试点
	    $p1 			= $pts[0];		//left vertex        
	    for ($i = 1; $i <= $N; ++$i) {	//check all rays
	        if ($p['lng'] == $p1['lng'] && $p['lat'] == $p1['lat']) {
	            return $boundOrVertex;
	        }
	        $p2 = $pts[$i % $N];        
	        if ($p['lat'] < min($p1['lat'], $p2['lat']) || $p['lat'] > max($p1['lat'], $p2['lat'])) {
	            $p1 = $p2; 
	            continue;
	        }
	        if ($p['lat'] > min($p1['lat'], $p2['lat']) && $p['lat'] < max($p1['lat'], $p2['lat'])) {
	            if($p['lng'] <= max($p1['lng'], $p2['lng'])){
	                if ($p1['lat'] == $p2['lat'] && $p['lng'] >= min($p1['lng'], $p2['lng'])) {
	                    return $boundOrVertex;
	                }
	                if ($p1['lng'] == $p2['lng']) {                       
	                    if ($p1['lng'] == $p['lng']) {
	                        return $boundOrVertex;
	                    } else {
	                        ++$intersectCount;
	                    }
	                } else {
	                    $xinters = ($p['lat'] - $p1['lat']) * ($p2['lng'] - $p1['lng']) / ($p2['lat'] - $p1['lat']) + $p1['lng'];
	                    if (abs($p['lng'] - $xinters) < $precision) {
	                        return $boundOrVertex;
	                    }
	                    if ($p['lng'] < $xinters) {
	                        ++$intersectCount;
	                    } 
	                }
	            }
	        } else {
	            if ($p['lat'] == $p2['lat'] && $p['lng'] <= $p2['lng']) {
	                $p3 = $pts[($i+1) % $N];
	                if ($p['lat'] >= min($p1['lat'], $p3['lat']) && $p['lat'] <= max($p1['lat'], $p3['lat'])) {
	                    ++$intersectCount;
	                } else {
	                    $intersectCount += 2;
	                }
	            }
	        }
	        $p1 = $p2;
	    }
	    return !($intersectCount % 2 == 0);
	}
	/**
	 * [Distance 计算坐标点之间的距离]
	 * @Author    como
	 * @DateTime  2019-01-12
	 * @copyright 思智捷管理系统
	 * @version   [1.5.0]
	 * @param     [type]     $org  [description]
	 * @param     [type]     $dest [description]
	 * @param     string     $ak   [description]
	 */
	Public function Distance($org,$dest,$ak = ''){
		if(!empty($ak))
			$this->config['ak'] = $ak;
		$orgstr = '';$deststr = '';
		if(is_array($org))
			$orgstr = implode('|', $org);
		else
			$orgstr = $org;
		if(is_array($dest))
			$deststr = implode('|',$dest);
		else
			$deststr = $dest;
		$apiurl = sprintf($this->apiUrl['distance'],$this->config['ak'],$orgstr,$deststr);
		try{
			$res = curl_get($apiurl);
			$data = json_decode($res,true);
			if(empty($data)){
				$result = appResult($this->error[998]);
			} else {
				$status = intval($data['status']);
				if(!empty($status)){
					if(isset($this->error[$status]))
						$result = appResult($this->error[$status]);
					else
						$result = appResult('无调用权限或超出调用次数');
				} else {
					$result = appResult($this->error[$status],$this->HanadleDistance($data,$orgstr,$deststr),false);
				}
			}			
		} catch(\Exception $err){
			$result = appResult($this->error[999]);
		}
		return $result;
	}
	/**
	 * [HanadleDistance 处理计算后的数据]
	 * @Author    como
	 * @DateTime  2019-01-12
	 * @copyright 思智捷管理系统
	 * @version   [1.5.0]
	 * @param     array      &$data [description]
	 */
	Private function HanadleDistance(&$data = [],&$orgstr,&$deststr){
		$orgarr = explode('|', $orgstr);
		$destarr = explode('|',$deststr);
		$result = [];
		$tmpData = [];
		$callback = function($val,$key) use(&$tmpData){
			if(isset($val['distance']))
				$tmpData[$key] = $val['distance']['value'];
			else
				$tmpData[$key] = -1;
		};
		if(isset($data['result']) && !empty($data['result'])){
			array_walk($data['result'],$callback);
			if(count($orgarr) > 1){
				$result = array_chunk($tmpData, count($destarr));
			} else {
				$result = $tmpData;
			}
		}
		return $result;
	}

}
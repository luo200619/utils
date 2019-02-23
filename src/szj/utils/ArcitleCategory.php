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
use app\common\model\Base;
/**
 * 思智捷文章分类管理模块
 */
Class ArcitleCategory extends Base {
	/**
	 * [getAllCategory 获取文章所有分类列表]
	 * @Author    como
	 * @DateTime  2019-02-22
	 * @copyright 思智捷管理系统
	 * @version   [1.5.0]
	 * @return    [type]     [description]
	 */
	Public function getCategory($options = []){
		$defaultParams = ['where'=>[],'field'=>true,'limit'=>[],'order'=>'sort_order'];
		$map = array_merge($defaultParams,$options);
		$limit = $this->limitHanadle($map['limit']);
		$list = $this->where($map['where'])->field($map['field'])->limit($limit[0],$limit[1])->select();
		return $list;
	}
	/**
	 * [CategoryLimitHanadle 获取文章分类limit处理函数]
	 * @Author    como
	 * @DateTime  2019-02-22
	 * @copyright 思智捷管理系统
	 * @version   [1.5.0]
	 * @param     array      $limit [description]
	 */
	Public function limitHanadle($limit = []){
		try{
			if(empty($limit)){
				return [0,15];
			}
			if(is_array($limit)){
				if(count($limit) == 1){
					return [0,intval($limit[0])];
				} elseif(count($limit) == 2){
					return $limit;
				} else {
					return [$limit[0],$limit(count($limit) - 1)];
				}
			}
			if(is_numeric($limit)){
				return [0,$limit];
			}
			$arr = explode(',',$limit);
			return $arr;			
		} catch(\Exception $err){
			return [0,15];
		}
	}
	/**
	 * [getNavCategory 获取所有导航文章分类]
	 * @Author    como
	 * @DateTime  2019-02-22
	 * @copyright 思智捷管理系统
	 * @version   [1.5.0]
	 * @return    [type]     [description]
	 */
	Public function getNavCategory($options = []){
		$defaultParams = [
			'where'=>['is_nav'=>1,'is_show'=>1]
		];
		$list = $this->getCategory(array_merge($defaultParams,$options));
		return $list;
	}
	/**
	 * [getOneCategory 获取文章分类是单页的分类列表]
	 * @Author    como
	 * @DateTime  2019-02-22
	 * @copyright 思智捷管理系统
	 * @version   [1.5.0]
	 * @param     array      $params [description]
	 * @return    [type]             [description]
	 */
	Public function getSingleCategory($options = []){
		$defaultParams = [
			'where'=>['cate_type'=>1]
		];
		$list = $this->getCategory(array_merge($defaultParams,$options));
		return $list;
	}
	/**
	 * [getListCategory 获取文章分类是列表的分类列表]
	 * @Author    como
	 * @DateTime  2019-02-22
	 * @copyright 思智捷管理系统
	 * @version   [1.5.0]
	 * @param     array      $params [description]
	 * @return    [type]             [description]
	 */
	Public function getListCategory($options = []){
		$defaultParams = ['where'=>['cate_type'=>0]];
		$list = $this->getCategory(array_merge($defaultParams,$options));
		return $list;
	}
	/**
	 * [getCategoryInfo 获取文章分类单个分类的详情]
	 * @Author    como
	 * @DateTime  2019-02-22
	 * @copyright 思智捷管理系统
	 * @version   [1.5.0]
	 * @param     integer    $cid [description]
	 * @return    [type]          [description]
	 */
	Public function getCategoryInfo($cid = 0,$options = []){
		$defaultParams = [
			'field'=>true
		];
		if(empty($cid)) return false;
		$map = array_merge($defaultParams,$options);
		$info = $this->where(['cid'=>$cid])->field($map['field'])->find();
		return $info;
	}
	/**
	 * [getRecursionChildCategory 递归获取所有的文章分类下的某分类下所有子节点]
	 * @Author    como
	 * @DateTime  2019-02-22
	 * @copyright 思智捷管理系统
	 * @version   [1.5.0]
	 * @param     integer    $pid     [description]
	 * @param     array      $options [description]
	 * @return    [type]              [description]
	 */
	Public function getRecursionChildCategory($pid = 0,$options = []){
		$defaultParams = ['field'=>true,'limit'=>'0,3000'];
		$list = $this->getCategory(array_merge($defaultParams,$options));
		if(empty($list)) return [];
		$recursionData = nodeAddRecursion($list->toArray(),$pid,'cid');
		$info = $this->getCategoryInfo($pid,array_merge($defaultParams,$options));
		if(empty($info)) return [];
		$tmpData = $info->toArray();
		$tmpData['child'] = $recursionData;
		return nodeRecursionToOneArray([$tmpData]);		
	}
	/**
	 * [getRecursionParentCategory 获取当前文章分类的所有父节点列表]
	 * @Author    como
	 * @DateTime  2019-02-22
	 * @copyright 思智捷管理系统
	 * @version   [1.5.0]
	 * @param     integer    $cid         [description]
	 * @param     array      $options     [description]
	 * @param     boolean    $toOnce      [description]
	 * @param     boolean    $includeSelf [description]
	 * @return    [type]                  [description]
	 */
	Public function getRecursionParentCategory($cid = 0,$options = []){
		$defaultParams = ['field'=>true,'limit'=>'0,3000'];
		$list = $this->getCategory(array_merge($defaultParams,$options));
		if(empty($list)) return [];
		$tmpData = nodeParentsRecurtion($list->toArray(),$cid,'cid');
		return $tmpData;
	}


}
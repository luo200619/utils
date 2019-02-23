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
use szj\utils\ArcitleCategory;
/**
 * 思智捷管理系统文章模块
 */
Class Arcitle extends Base {
	/**
	 * [getCateArcitleList 获取分类下的所有文章列表]
	 * @Author    como
	 * @DateTime  2019-02-22
	 * @copyright 思智捷管理系统
	 * @version   [1.5.0]
	 * @param     integer    $cid     [description]
	 * @param     array      $options [description]
	 * @return    [type]              [description]
	 */
	Public function getCateArcitleList($cid = 0,$options = []){
		$allChildCid = (new ArcitleCategory)->getRecursionChildCategory($cid,['field'=>'cid,pid']);
		$childarr = [];
		if(empty($allChildCid)) return [];
		foreach($allChildCid as $key=>$val){
			$childarr[] = $val['cid'];
		}
		$defaultParams = ['field'=>true,'p'=>1,'pagelist'=>15,'order'=>'sort_order,id desc','where'=>[]];
		$map = array_merge($defaultParams,$options);
		$condition = [];
		$condition[] = ['cid','in',$childarr];
		$condition[] = ['is_show','=',1];
		$list = $this->where($condition)->where($map['where'])->field($map['field'])->page($map['p'],$map['pagelist'])->order($map['order'])->select();
		$total = $this->where($condition)->where($map['where'])->count();
		if(empty($list)) return['list'=>[],'total'=>$total];
		return ['list'=>$list->toArray(),'total'=>$total];
	}
	/**
	 * [getDefaultCateArcitleList 系统默认的文章分类 自带分页功能]
	 * @Author    como
	 * @DateTime  2019-02-22
	 * @copyright 思智捷管理系统
	 * @version   [1.5.0]
	 * @param     integer    $cid     [description]
	 * @param     array      $options [description]
	 * @return    [type]              [description]
	 */
	Public function getDefaultCateArcitleList($cid = 0,$options = []){
		$allChildCid = (new ArcitleCategory)->getRecursionChildCategory($cid,['field'=>'cid,pid']);
		$childarr = [];
		if(empty($allChildCid)) return [];
		foreach($allChildCid as $key=>$val){
			$childarr[] = $val['cid'];
		}
		$defaultParams = ['field'=>true,'p'=>1,'pagelist'=>15,'order'=>'sort_order,id desc','where'=>[]];
		$map = array_merge($defaultParams,$options);
		$condition = [];
		$condition[] = ['cid','in',$childarr];
		$condition[] = ['is_show','=',1];
		$list = $this->where($condition)->where($map['where'])->field($map['field'])->order($map['order'])->paginate($map['pagelist'],false,$this->pageType);
		return $list;
	}
	/**
	 * [getSingleCidArcitle 获取文章分类的是单页的文章]
	 * @Author    como
	 * @DateTime  2019-02-22
	 * @copyright 思智捷管理系统
	 * @version   [1.5.0]
	 * @param     integer    $cid     [description]
	 * @param     array      $options [description]
	 * @return    [type]              [description]
	 */
	Public function getSingleCidArcitle($cid = 0,$options = []){
		$cate_type = (new ArcitleCategory)->where(['cid'=>$cid])->value('cate_type');
		if(empty($cate_type)) return false;
		$defaultParams = ['field'=>true,'where'=>[],'order'=>'sort_order,id desc'];
		$map = array_merge($defaultParams,$options);
		$condition = ['cid'=>$cid,'is_show'=>1];
		$info = $this->where($condition)->where($map['where'])->order($map['order'])->field($map['field'])->find();
		return $info;
	}
	/**
	 * [getArcitleInfo 获取文章详情]
	 * @Author    como
	 * @DateTime  2019-02-22
	 * @copyright 思智捷管理系统
	 * @version   [1.5.0]
	 * @param     integer    $id      [description]
	 * @param     array      $options [description]
	 * @return    [type]              [description]
	 */
	Public function getArcitleInfo($id = 0,$options = []){
		if(empty($id)) return false;
		$defaultParams = ['field'=>true];
		$map = array_merge($defaultParams,$options);
		$info = $this->where(['id'=>$id])->field($map['field'])->find();
		return $info;
	}
	/**
	 * [getHomeAllArcitle 获取所有需要首页显示的文章]
	 * @Author    como
	 * @DateTime  2019-02-22
	 * @copyright 思智捷管理系统
	 * @version   [1.5.0]
	 * @return    [type]     [description]
	 */
	Public function getHomeAllArcitle($options = []){
		$defaultParams = ['where'=>[],'field'=>true,'order'=>'sort_order,id desc','limit'=>[0,10]];
		$map = array_merge($defaultParams,$options);
		$condition = ['is_home'=>1,'is_show'=>1];
		$limit = (new ArcitleCategory)->limitHanadle($map['limit']);
		$list = $this->where($condition)->where($map['where'])->field($map['field'])->limit($limit[0],$limit[1])->order($map['order'])->select();
		if(empty($list)) return [];
		return $list->toArray();
	}
	/**
	 * [getArcitleNextPrev 获取文章的一篇和下一篇]
	 * @Author    como
	 * @DateTime  2019-02-22
	 * @copyright 思智捷管理系统
	 * @version   [1.5.0]
	 * @param     integer    $id      [description]
	 * @param     array      $options [description]
	 * @return    [type]              [description]
	 */
	Public function getArcitleNextPrev($id = 0,$options = []){
		$cid = $this->where(['id'=>$id,'is_show'=>1])->value('cid');
		if(empty($cid)) return false;
		$defaultParams = ['field'=>true,'where'=>[]];
		$map = array_merge($defaultParams,$options);
		$nextWhere = [['cid','=',$cid],['id','>',$id]];
		$prevWhere = [['cid','=',$cid],['id','<',$id]];
		$nextInfo = $this->where($nextWhere)->where($map['where'])->order('id,sort_order')->field($map['field'])->find();
		$prevInfo = $this->where($prevWhere)->where($map['where'])->order('id desc,sort_order')->field($map['field'])->find();
		return ['next'=>$nextInfo,'prev'=>$prevInfo];
	}
	/**
	 * [seachArcitle 文章搜索]
	 * @Author    como
	 * @DateTime  2019-02-23
	 * @copyright 思智捷管理系统
	 * @version   [1.5.0]
	 * @param     string     $keywords [description]
	 * @param     array      $options  [description]
	 * @return    [type]               [description]
	 */
	Public function seachArcitle($keywords = '',$options = []){
		if(empty($keywords)) return false;
		$defaultParams = ['where'=>[],'field'=>true,'p'=>1,'pagelist'=>15,'order'=>'sort_order,id desc'];
		$map = array_merge($defaultParams,$options);
		$condition = [['title','like','%'.$keywords.'%'],['is_show','=','1']];
		$list = $this->where($condition)->where($map['where'])->field($map['field'])->order($map['order'])->page($map['p'],$map['pagelist'])->select();
		$total = $this->where($condition)->where($map['where'])->count();
		if(empty($list)) return ['list'=>[],'total'=>$total];
		return ['list'=>$list->toArray(),'total'=>$total];
	}
	/**
	 * [seachDefaultArcitle 默认分页样式搜索]
	 * @Author    como
	 * @DateTime  2019-02-23
	 * @copyright 思智捷管理系统
	 * @version   [1.5.0]
	 * @param     string     $keywords [description]
	 * @param     array      $options  [description]
	 * @return    [type]               [description]
	 */
	Public function seachDefaultArcitle($keywords = '',$options = []){
		if(empty($keywords)) return false;
		$defaultParams = ['where'=>[],'field'=>true,'p'=>1,'pagelist'=>15,'order'=>'sort_order,id desc'];
		$map = array_merge($defaultParams,$options);
		$condition = [['title','like','%'.$keywords.'%'],['is_show','=','1']];
		$list = $this->where($condition)->where($map['where'])->field($map['field'])->order($map['order'])->paginate($map['pagelist'],false,$this->pageType);
		return $list;
	}
}
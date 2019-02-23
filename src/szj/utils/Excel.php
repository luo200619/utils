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
 * phpexcel类的封装使用
 */
Class Excel {
	/**
	 * [$dataTitle 表头信息]
	 * @var array
	 */
	Private $dataTitle = [];
	/**
	 * [$defaultSheetTitle 默认sheet表名称]
	 * @var string
	 */
	Private $defaultSheetTitle = '';
	/**
	 * [$config 全局默认配置]
	 * @var array
	 */
	Private $config = [
		'fontName'=>'宋体',
		'fontSize'=>12,
		'horizontal'=>'',
		'vertical'=>'',
		'beforeExport'=>'',
		'defaultSheetTitle'=>'demo'
	];

	/**
	 * [__construct 构造函数]
	 * @Author    como
	 * @DateTime  2018-12-28
	 * @copyright 思智捷管理系统
	 * @version   [1.5.0]
	 */
	Public function __construct($conf = []){
		$this->config['horizontal'] = \PHPExcel_Style_Alignment::HORIZONTAL_CENTER;
		$this->config['vertical'] = \PHPExcel_Style_Alignment::VERTICAL_CENTER;
		$this->config = array_merge($this->config,$conf);
		$this->setDefaultSheetTitle($this->config['defaultSheetTitle']);
	}
	/**
	 * [getObjExcel 获取PHPExcel对象]
	 * @Author    como
	 * @DateTime  2018-12-28
	 * @copyright 思智捷管理系统
	 * @version   [1.5.0]
	 * @return    [type]     [description]
	 */
	Public function getObjPHPExcel(){
		return new \PHPExcel();
	}

	/**
	 * [export 导出数据]
	 * @Author    como
	 * @DateTime  2018-12-28
	 * @copyright 思智捷管理系统
	 * @version   [1.5.0]
	 * @param     array      $dataTitle [description]
	 * @param     array      $data      [description]
	 * @return    [type]                [description]
	 */
	Public function export(&$data = [],$dataTitle = [],$save = '',$type = 'Excel5'){
		if(!empty($dataTitle)){
			$this->setSheetHead($dataTitle);
		}
		$headResult = $this->getCellTitleFields($data);
		if(empty($headResult)){
			return appResult('请输入需要写入的数据');
		}
        $objPHPExcel = $this->getObjPHPExcel();
        $objSheet = $objPHPExcel->getActiveSheet();
        $this->setDefaultStyle($objSheet);
       	if(!empty($this->defaultSheetTitle)){
       		$objSheet->setTitle($this->defaultSheetTitle);
       	}
       	if(!empty($this->config['beforeExport'])){
       		call_user_func($this->config['beforeExport'],$objSheet);
       	}
       	$this->writeSheetData($objSheet,$data,$headResult);
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel,$type);
        if(empty($save)){
	        header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
	        header("Content-Type:application/force-download");
	        header("Content-Type:application/vnd.ms-execl");
	        header("Content-Type:application/octet-stream");
	        header("Content-Type:application/download");;
	        if($type == 'Excel5'){
	        	header('Content-Disposition:attachment;filename='.time().'.xls');
	        } else {
	        	header('Content-Disposition:attachment;filename='.time().'.xlsx');
	        }
	        header("Content-Transfer-Encoding:binary");
			$objWriter->save('php://output');
        } else {
         	$objWriter->save($save);  
         	return file_exists($save); 	
        }
	}

	/**
	 * [setDefaultStyle 设置当前默认样式]
	 * @Author    como
	 * @DateTime  2018-12-28
	 * @copyright 思智捷管理系统
	 * @version   [1.5.0]
	 */
	Private function setDefaultStyle(&$objSheet){
		//设置默认字体大小
		$default = $objSheet->getDefaultStyle();
		$default->getFont()->setName($this->config['fontName'])->setSize($this->config['fontSize']); 
		//设置垂直和水平方向上居中
		$default->getAlignment()->setVertical($this->config['vertical'])->setHorizontal($this->config['horizontal']);
	}

	/**
	 * [writeSheetData 写入当前sheet数据]
	 * @Author    como
	 * @DateTime  2018-12-28
	 * @copyright 思智捷管理系统
	 * @version   [1.5.0]
	 * @param     [type]     &$objSheet [description]
	 * @return    [type]                [description]
	 */
	Private function writeSheetData(&$objSheet = null,&$data = [],&$headResult = []){
       	if(!empty($this->dataTitle)){
       		$callback = function($val,$key) use(&$objSheet){
       			$objSheet->setCellValue($key,$val['name']);
       		};
       		array_walk($this->dataTitle,$callback);
       	}
       	foreach($data as $key=>$item){
       		if(empty($this->dataTitle)){
       			$curIndex = 0;
           		foreach($item as $k=>$v){
           			$objSheet->setCellValue($headResult[$curIndex]['column'].($headResult[$curIndex]['index']+$key),$v);
           			$curIndex++;
           		}
       		} else {
       			foreach($headResult as $k=>$v){
       				$tmp = $this->dataTitle[$v['column'].$v['index']];
       				$field = $tmp['field'];
       				$callback = empty($tmp['callback'])?false:$tmp['callback'];
       				$curCell = $v['column'].($v['index']+ $key + 1);
       				$curType = empty($tmp['type'])?false:$tmp['type'];
       				if($callback === false){
       					$value = empty($item[$field])?'':$item[$field];
       				} else {
       					$value = call_user_func($callback,$item[$field],$item,$objSheet,$curCell);
       				}
       				switch($curType){
       					case 'float':
       						$objSheet->getStyle($curCell)->getNumberFormat()->setFormatCode("0.00");
       						break;
       				}
       				$objSheet->setCellValue($curCell,$value);
       			}
       		}
       	}
	}

	/**
	 * [getCellTitleFields 获取字段的列名]
	 * @Author    como
	 * @DateTime  2018-12-28
	 * @copyright 思智捷管理系统
	 * @version   [1.5.0]
	 * @param     array      $dataTitle [description]
	 * @return    [type]                [description]
	 */
	Private function getCellTitleFields(&$data = []){
		$newKeys = [];
		if(empty($this->dataTitle)){
			if(!empty($data) && !empty($data[0])){
				$columnCount = count($data[0]);
				$arr = range('A', 'Z');
				$tmparr = range(1,$columnCount);
				$callback = function($val,$key) use(&$newKeys,&$arr){
					$newKeys[] = ['index'=>1,'column'=>$arr[$key]];
				};
				array_walk($tmparr,$callback);
			}
		} else {
			$keys = array_keys($this->dataTitle);
			$callback = function($val,$key) use (&$newKeys){
				$number = $this->findNum($val);
				$column = str_replace($number,'',$val);
				$newKeys[] = ['index'=>$number,'column'=>$column];
			};
			array_walk($keys, $callback);			
		}
		return $newKeys;
	}



	/**
	 * [findNum 提取头部数字]
	 * @Author    como
	 * @DateTime  2018-12-28
	 * @copyright 思智捷管理系统
	 * @version   [1.5.0]
	 * @param     string     $str [description]
	 * @return    [type]          [description]
	 */
	Private function findNum($str = ''){
	    $str = trim($str);
	    if(empty($str)){
	    	return '';
	    }
	    $result = '';
	    for($i=0;$i<strlen($str);$i++){
	        if(is_numeric($str[$i])){
	            $result .= $str[$i];
	        }
	    }
	    return $result;
	}
    /**
     * [setExcelHeader 设置excel表头]
     * @Author    como
     * @DateTime  2018-12-28
     * @copyright 思智捷管理系统
     * @version   [1.5.0]
     * @param     array      $dataTitle [description]
     */
    Public function setSheetHead(&$dataTitle = []){
    	$this->dataTitle = $dataTitle;
    	return $this;
    }  
    /**
     * [setSheetTitle 设置表的名称]
     * @Author    como
     * @DateTime  2018-12-28
     * @copyright 思智捷管理系统
     * @version   [1.5.0]
     * @param     integer    $index [description]
     * @param     string     $title [description]
     */
    Public function setSheetTitle($index = 0,$title = ''){
    	return $this;
    }
    /**
     * [setDefaultSheetTitle 设置默认的单元格名称]
     * @Author    como
     * @DateTime  2018-12-28
     * @copyright 思智捷管理系统
     * @version   [1.5.0]
     * @param     string     $title [description]
     */
    Public function setDefaultSheetTitle($title = ''){
    	$this->defaultSheetTitle = $title;
    	return $this;
    }

        
	/**
	 * [import 导入数据]
	 * @Author    como
	 * @DateTime  2018-12-28
	 * @copyright 思智捷管理系统
	 * @version   [1.5.0]
	 * @param     string     $fileName [description]
	 * @return    [type]               [description]
	 */
	Public function import($fileName = '',$defaultIndex = 0){
        $reader = $this->getReaderObj($fileName);
        if (empty($reader)){
            return appResult("不是有效的excel文件");
        }
        $excelObj = $reader->load($fileName);
        $sheet = $excelObj->getSheet($defaultIndex);
        $result = $this->getExcelData($sheet);
        return appResult("数据读取成功",$result,false);
	}
    /**
     * 获取表格里面的内容
     * @param type $sheet
     */
    Private function getExcelData(&$sheet){
        //获取行数与列数,注意列数需要转换
        $highestRowNum = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();
        $highestColumnNum = \PHPExcel_Cell::columnIndexFromString($highestColumn);
        $usefullColumnNum = $highestColumnNum;
        //开始取出数据并存入数组
        $data = [];
        for( $i=1; $i <= $highestRowNum ;$i++ ){//ignore row 1
            $row = array();
            for( $j = 0; $j < $usefullColumnNum;$j++ ){
                $cellName = \PHPExcel_Cell::stringFromColumnIndex($j).$i;
                $cellVal = $sheet->getCell($cellName)->getValue();
                if($cellVal instanceof \PHPExcel_RichText){ //富文本转换字符串
                    $cellVal = $cellVal->__toString();
                }
                $row[ ] = $cellVal;
            }
            $data []= $row;
        }
        return $data;
    }
    
    /**
     * 获取读取器对象
     * @param type $fileName
     */
    Private function getReaderObj($fileName = ''){
        $PHPExcel = $this->getObjPHPExcel();
        $PHPReader = new \PHPExcel_Reader_Excel2007();
        if(!$PHPReader->canRead($fileName)){
            $PHPReader = new \PHPExcel_Reader_Excel5();
            if(!$PHPReader->canRead($fileName)){
                return false;
            }
        }
        return $PHPReader;
    }

}
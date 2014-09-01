<?php

/**
* 这是一个查询 大学英语四六级成绩的类
* 抓取自 http://www.chsi.com.cn/cet/
* 2014年8月31日 19:02:40
* lovefree13
*/
error_reporting(0);
	class getCetScore{
		
		/**
		* $zkzh numeric 准考证号 （长度：15位）
		* $xm string 姓名 （长度：至少两个汉字）
		* return $url string 请求的网址
		*/
		static private function getUrl($zkzh, $xm){
		
			(is_numeric($zkzh) && (strlen($zkzh) === 15) && is_string($xm) && (strlen($xm) > 8)) ? $url = 'http://www.chsi.com.cn/cet/query?zkzh='.$zkzh.'&xm='.urlencode($xm) : $url = NULL;
			return $url;
		}
		
		/**
		* $url string 请求网址
		* return $webPage string 抓取到的未处理的网页源码
		*/
		static private function getWebPage($url){
		
			if(!is_null($url)){

				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_REFERER, 'http://www.chsi.com.cn/cet/');
				curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:24.0) Gecko/20100101 Firefox/24.0');
				$webPage = curl_exec($ch);
				curl_close($ch);
				return $webPage;
			}
			return NULL; // $url 是 NULL 则返回 NULL
		}
		
		/**
		* $webPage string 网页源码
		* return $arrayData string 个人和四六级成绩等信息的数组（该数组尚不完美）
		*/
		static private function getArrayData($webPage){
		
			if(isset($webPage)){
			
				preg_match_all('/<table(.|\s)*?<\/table>/', $webPage, $matches);
				preg_match_all('/(>)(.|\s)*?(<)/', $matches[0][1], $matches);
				
				$search = array('<','>','：',chr(13).chr(10));
				$result = str_replace($search, '', $matches[0]);
				
				$content = array();
				foreach($result as $value){ //去除 数组value 前后的空格
					$content[] = trim($value);
				}
				
				$content = array_filter($content); // 删除数组空元素
				
				$arrayData = array();
				foreach($content as $value){ // 数组key重新排序
					$arrayData[] = $value;
				}
				
				isset($arrayData[11]) ? $arrayData = $arrayData : $arrayData = NULL; // $arrayData[11]是总分，总分不存在即准考证号或姓名错误
				return $arrayData;
			}
			return NULL; // $webPage 不存在 则返回 NULL
		}
		
		/**
		* $arrayData string 个人和四六级成绩等信息的数组
		* return string JSON格式数据
		*/
		static private function getJsonData($arrayData){
			
			if(isset($arrayData)){
				return json_encode(array('msg'=>'success','code'=>'200','content'=>$arrayData));
			}
			return json_encode(array('msg'=>'error','code'=>'400','content'=>''));
		}
		
		/**
		* 查询四六级成绩唯一入口函数
		* $zkzh numeric 准考证号
		* $xm string 姓名
		* return $result string JSON格式查询结果
		*/
		static public function returnCetScore($zkzh, $xm){
		
			$url = self::getUrl($zkzh, $xm);
			$webPage = self::getWebPage($url);
			$arrayData = self::getArrayData($webPage);
			$result = self::getJsonData($arrayData);
			return $result;
		}
	}

	isset($_GET['num']) ? $zkzh = $_GET['num'] : die(json_encode(array('msg'=>'error','code'=>'400','content'=>'请输入准考证号')));
	isset($_GET['name']) ? $xm = $_GET['name'] : die(json_encode(array('msg'=>'error','code'=>'400','content'=>'请输入姓名')));
	$result = getCetScore::returnCetScore($zkzh, $xm);
	echo $result;
?>
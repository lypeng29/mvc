<?php
/**
* 文件上传辅助类
* @author lan
*/
namespace fastphp\helper;

/**
* 文件上传辅助类，多文件上传和单文件上传均可
* 类中方法均为静态方法
* @api 调用示例
*
*      use Common\Api\UploadHelper;
*      $upload = UploadHelper::upload('public'); //上传文件
*      $thumb = UploadHelper::thumb("/Upload/public/img/origin/3c/f8/96/3cf8968946e1864bc6d221e7577291eb.png", '/Upload/public/img/large/3c/f8/96/3cf8968946e1864bc6d221e7577291eb.png'); //生成缩略图
*	   UploadHelper::batchThumb($filePath); //批量生成缩略图（大中小三个尺寸）
*      UploadHelper::getError(); //上传失败、生成缩略图失败原因
*/
class UploadHelper
{
	/**
	 * 错误信息，通过getError方法可获取
	 */
	private static $error="";

	/**
	 * 上传文件的根目录路径
	 */
	private static $rootPath = './Upload/';

	/**
	 * 允许的子目录名
	 */
	private static $allowDir = array('public','private');

	/**
	 * 允许上传的文件类型后缀
	 */
	private static $allowFileType = array(
		'image'		=>	array('jpg', 'gif', 'png', 'jpeg'),
		'document'	=>	array('pdf', 'doc', 'xls', 'docx', 'xlsx', 'ppt', 'pptx'),
		'video'		=>	array('mp4'),
	);

	/**
	 * resourceType
	 */
	private static $resourceType = array(
		'image'		=>	'img',
		'document'	=>	'pdf',
		'video'		=>	'video',
	);

	/**
	 * upload 单文件、多文件上传
	 * 保存的文件名为随机字符串
	 * @param $dir 文件上传子目录，如用户头像headimg；默认为空，即uploads/Ymd
	 * @param $filetype 文件类型，默认image图片，支持jpg,png,gif格式
	 * @param $maxSize 允许上传的最大大小，默认2M
	 * @param $onlyReturnName 是否仅返回文件名，默认为false，返回文件信息数组
	 * @return Array|String|false 上传多文件返回路径数组，单文件返回路径字符串，失败返回false
	 */
	public static function upload($dir='', $filetype='image', $maxSize=2, $onlyReturnName=false){
		if($dir && !in_array($dir, self::$allowDir)){
			self::$error = "子目录错误";
			return false;
		}
		$isSecurity = ($dir == 'private') ? 1 : 0;
		if(!isset(self::$allowFileType[$filetype])){
			self::$error = "文件类型错误";
			return false;
		}
		if(!isset(self::$resourceType[$filetype])){
			self::$error = "资源类型未定义";
			return false;
		}

		if($dir){
			if(function_exists('getAttachmentNo')){
				$saveName = getAttachmentNo();
			}else{
				$saveName = uniqid('');
			}

			$attachmentNo = $saveName;
			
			$resourceType = self::$resourceType[$filetype];

			$sizeType = 'origin';
			$dir .= '/' .$resourceType . '/' . $sizeType .'/';
			$dir .= substr($saveName, 0, 2) . '/' . substr($saveName, 2, 2) .'/'. substr($saveName, 4, 2) .'/';
		}

		// 判断是否超过上传大小限制
		$maxSizeConfig = intval(ini_get('post_max_size')) * 1024 * 1024;  // M为单位 2M=>2*1024*1024
		// 获取提交的表单头信息
		$postContentLength = getallheaders();
		$postContentLength = intval($postContentLength['Content-Length']);
		if($postContentLength > $maxSizeConfig){
			// 超出限制 php会报错(warning)
			self::$error = "文件大小超过限制";
			return false;
		}

		$fileMaxSize = intval($maxSize) > 0 ? intval($maxSize)*1024*1024 : 2097152;
		if($fileMaxSize > $maxSizeConfig){
			$fileMaxSize = $maxSizeConfig; // 不能超过ini设置值
		}

		$config = array(
		    'maxSize'    =>    $fileMaxSize,
		    'rootPath'   =>    self::$rootPath,
		    'savePath'   =>    $dir,
		    #'saveName'   =>    array('uniqid',''),
		    'saveName'   =>    $saveName,
		    'exts'       =>    self::$allowFileType[$filetype],
		    'autoSub'    =>    true,
		    'subName'    =>    array('date','Ymd'), //如果没有设置$dir，则按日期分子目录
		);

		if($dir !== ''){
			// 子目录文件夹不需要按日期再分子文件夹
			$config['autoSub'] = false;
			$config['subName'] = '';
		}

		// 图片资源始终保存为png格式
		if($filetype == 'image'){
			$config['saveExt'] = 'png';
		}

		$upload = new \Think\Upload($config);// 实例化上传类
		$info = $upload->upload();
		if(!$info){
			// 上传失败
			self::$error = $upload->getError() ? $upload->getError() : "未知错误";
			return false;
		}else{
			$files = array();
			foreach ($info as $key => $value) {
				$tempSaveName = ltrim(self::$rootPath, '.') . $value['savepath'] . $value['savename'];
				if($onlyReturnName == false){
					$files[] = array(
						'fullName'	=>	$tempSaveName,
						'attachmentNo'	=>	$attachmentNo,
						'isSecurity'	=>	$isSecurity,
						'pathTypeName'	=>	$resourceType,
						'createTime'	=>	date("Y-m-d H:i:s"),
						'fileType'	=>	$value['type'],
						'fileSuffix'=>	($filetype == 'image') ? "png" : $value['ext'],
					);
				}else{
					$files[] = $tempSaveName;
				}
			}
			return count($files) == 1 ? $files[0] : $files;
		}
		return false;
	}

	/**
	 * 生成缩略图
	 * @param $filePath 需要生成缩略图的原图文件路径
	 * @param $savePath 生成缩略图存放路径（文件夹或文件路径），传入文件夹时，生成的缩略图以thumb_xxx命名
	 * @param $imageW 缩略图宽度
	 * @param $imageH 缩略图高度
	 * @return String | throw exception 失败时返回空字符串，成功时返回生成的缩略图路径
	 */
	public static function thumb($filePath, $savePath, $imageW=160, $imageH=160){
		$filePath = '.'.ltrim($filePath, '.'); //fix path
		if(!file_exists($filePath) || !is_file($filePath)){
			self::$error = "图像文件不存在";
			return '';
		}
		$savePath = '.'.ltrim($savePath, '.'); //fix path
		if(is_dir($savePath) && !file_exists($savePath)){
			self::$error = "缩略图保存文件夹不存在";
			return '';
		}
		if(!is_dir($savePath)){
			//传入的不是文件夹
			$saveDir = dirname($savePath);
			if(!is_dir($saveDir)){
				// self::$error = "缩略图保存文件夹不存在";
				// return '';

				// 修改：如果文件夹不存在，自动创建
				@mkdir($saveDir, 0775, true);
			}
		}
		
		$imageW = intval($imageW) > 0 ? intval($imageW) : 160;
		$imageH = intval($imageH) > 0 ? intval($imageH) : 160;
		if(is_dir($savePath)){
			// 传入的是文件夹
			$savePath = rtrim(rtrim($savePath, '/'), '\\');
			$savePath .= '/thumb_'.uniqid() . '.' . $image->type();
		}

		try {
			$image = new \Think\Image();
			$image->open($filePath);

			// 生成固定尺寸缩略图，比例不符时填充白色
			$image->thumb($imageW, $imageH, \Think\Image::IMAGE_THUMB_FILLED)->save($savePath);

			return ltrim($savePath, '.');

		}catch (\Think\Exception $e){
			self::$error = "缩略图生成失败";
			return '';
		}
		
	}

	/**
	 * 批量生成缩略图
	 * @param $filePath 原图地址
	 * @param $width1 大尺寸缩略图宽度
	 * @param $height1 大尺寸缩略图高度
	 * @param $width2 中尺寸缩略图宽度
	 * @param $height2 中尺寸缩略图高度
	 * @param $width3 小尺寸缩略图宽度
	 * @param $height4 小尺寸缩略图高度
	 * @return bool
	 */
	public static function batchThumb($filePath='', $width1=800, $height1=800, $width2=400, $height2=400, $width3=100, $height3=100){
		try {
			$filePath = '.'.ltrim($filePath, '.'); //fix path

			if(!file_exists($filePath) || !is_file($filePath)){
				die("图像文件不存在");
			}

			$width1 = intval($width1) > 0 ? intval($width1) : 800;
			$height1 = intval($height1) > 0 ? intval($height1) : 800;
			$width2 = intval($width2) > 0 ? intval($width2) : 400;
			$height2 = intval($height2) > 0 ? intval($height2) : 400;
			$width3 = intval($width3) > 0 ? intval($width3) : 100;
			$height3 = intval($height3) > 0 ? intval($height3) : 100;

			$large = str_replace('origin/', 'large/', $filePath);
			$medium = str_replace('origin/', 'medium/', $filePath);
			$small = str_replace('origin/', 'small/', $filePath);

			$image = new \Think\Image();
			
			@mkdir(dirname($large), 0775, true);
			@mkdir(dirname($medium), 0775, true);
			@mkdir(dirname($small), 0775, true);

			// 生成固定尺寸缩略图，比例不符时填充白色
			
			// fix: 上传小图片时小图缩略图有问题
			// 调用thumb和save方法后会将图片资源指向新生成的图片文件而不是原始图片，需要重新打开
			$image->open($filePath);
			$image->thumb($width1, $height1, \Think\Image::IMAGE_THUMB_FILLED)->save($large);

			$image->open($filePath);
			$image->thumb($width2, $height2, \Think\Image::IMAGE_THUMB_FILLED)->save($medium);

			$image->open($filePath);
			$image->thumb($width3, $height3, \Think\Image::IMAGE_THUMB_FILLED)->save($small);

			return true;

		}catch (\Think\Exception $e){
			self::$error = "缩略图生成失败";
			return false;
		}
	}

	/**
	 * 获取错误信息
	 */
	public static function getError(){
		return self::$error;
	}
}
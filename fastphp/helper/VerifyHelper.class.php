<?php
/**
 * 验证码辅助类
 * @author lan
 */
namespace Common\Api;

/**
* 验证码辅助类，用于获取验证码图片和验证验证码
* 类中方法均为静态方法
* @api 调用示例
*
*      use Common\Api\VerifyHelper;
*      $verify = VerifyHelper::get(); //生成二维码图片
*      dump(VerifyHelper::check('tker')); //验证用户输入的验证码是否正确
*/
class VerifyHelper
{
	/**
	 * 默认配置信息
	 */
	private static $config = array(
		'imageW'	=>	128,  //验证码宽度 设置为0为自动计算
		'imageH'	=>	40,
		'length'	=>	4,   //验证码位数
		'expire'	=>	180, //验证码过期时间（s）
		'seKey'		=>	'FINMall.YiTian.com',  //验证码的加密密钥
		'fontSize'	=>	18,
		'version'	=>	1,  //页面中需要多个二维码时使用此参数
		'useCurve'	=>	false,
		'codeSet'	=>	'23456789abcdefghmnpqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ'
	);

	/**
	 * 获取、生成验证码
	 * 生成的验证码为若干个大小写字母、数字组成
	 * 直接生成图片，没有返回值
	 * @param $v 页面中需要多个二维码时使用此参数，默认为1
	 * @param $length 验证码位数
	 * @param $imageW 验证码图片宽度，设置为0为自动计算
	 * @param $imageH 验证码图片高度，设置为0为自动计算
	 * @param $expire 验证码过期时间（s）
	 */
	public static function get($v=0, $length=0, $imageW=0, $imageH=0, $expire=0){
		$v = intval($v) > 0 ? intval($v) : self::$config['version'];
		$length = intval($length) > 0 ? intval($length) : self::$config['length'];
		$imageW = intval($imageW) > 0 ? intval($imageW) : self::$config['imageW'];
		$imageH = intval($imageH) > 0 ? intval($imageH) : self::$config['imageH'];
		$expire = intval($expire) > 0 ? intval($expire) : self::$config['expire'];

		self::$config['length'] = $length;
		self::$config['imageW'] = $imageW;
		self::$config['imageH'] = $imageH;
		self::$config['expire'] = $expire;

		$Verify = new \Think\Verify(self::$config);

		$Verify->entry($v);
	}

	/**
	 * 检查验证码是否正确，忽略字母的大小写
	 * @param $code 用户输入的验证码
	 * @param $v 获取验证码时设置的$v参数，默认为1
	 * @return Bool 正确返回true 错误返回false
	 */
	public static function check($code='', $v=0){
		if(!preg_match("/^[0-9a-zA-Z]{1,}$/", $code)){
			return false;
		}
		$v = intval($v) > 0 ? intval($v) : self::$config['version'];

		// 如果开启了调试且传递了测试参数，直接返回true
		if(APP_DEBUG == true && isset($_SERVER['HTTP_AUTO_TEST']) && $_SERVER['HTTP_AUTO_TEST'] == C('AUTO_TEST_AUTH_KEY')){
			return true;
		}

		$config = array(
			'seKey'		=>	self::$config['seKey'],
			'expire'	=>	self::$config['expire'],
		);

		$Verify = new \Think\Verify($config);

		return $Verify->check($code, $v);
	}
}

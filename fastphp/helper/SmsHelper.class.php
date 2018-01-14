<?php
/**
* 短信验证码辅助类
* @author lan
*/
namespace Common\Api;
use Common\Api\SmsRestSdk;
use Common\User\Action\UserAction;
use Common\Common\Action\Log_notifyAction;

/**
* 短信验证码辅助类
*/
class SmsHelper
{
	private static $token = 'RLULLKVZ';
	public static $timeout = 120; // 短信验证码过期时间

	/**
	 * 根据手机号验证短信验证码
	 */
	public static function verify($mobile='', $code=''){
		// 检查是否正常流程发送短信或超时
		$expire = intval(C('SMS_TIMEOUT_SECONDS')) > 0 ? intval(C('SMS_TIMEOUT_SECONDS')) : self::$timeout;
		$cache = S(array('type'=>'db','prefix'=>'Verify_sms_','expire'=>$expire));
		$verify_cache = $cache->$mobile;
		if(!$verify_cache){
			// 不存在或已过期
			return false;
		}

		if($verify_cache == $code){
			// 验证成功之后，销毁缓存
			unset($cache->$mobile);
			return true;
		}

		return false;
	}

	/**
	 * 发送短信验证码
	 */
	public static function send($mobile='', $notifyTypeName = ''){
		if(!isMobile($mobile)){
			return false;
		}
		$smscode = self::getCode();

		$expire = intval(C('SMS_TIMEOUT_SECONDS')) > 0 ? intval(C('SMS_TIMEOUT_SECONDS')) : self::$timeout;

		// send sms
		// 是否开启短信发送
		if(C('SMS_AVAILABLE') === true){
			// 短信模板
			// $notifyTypeName = "VerifySms";
			// 验证码模板中都是分钟
			$sendSmsResult = self::sendTemplateSMS($mobile, array($smscode, intval($expire / 60) ), $notifyTypeName);
			if(!$sendSmsResult){
				// 失败
				return false;
			}
		}

		// 存到数据库中
		$cache = S(array('type'=>'db','prefix'=>'Verify_sms_','expire'=>$expire));
		$cache->$mobile = $smscode;

		return true;
	}

	/**
	 * 获取短信验证码，用于测试阶段输出
	 */
	public static function getSmsCodeOnDebug($mobile = ''){
		if(C('SMS_AVAILABLE') === false){
			$expire = intval(C('SMS_TIMEOUT_SECONDS')) > 0 ? intval(C('SMS_TIMEOUT_SECONDS')) : self::$timeout;
			$cache = S(array('type'=>'db','prefix'=>'Verify_sms_','expire'=>$expire));
			$verify_cache = $cache->$mobile;
			return $verify_cache;
		}else{
			return false;
		}
	}

	/**
	 * 检查是否有未过期的验证码缓存
	 */
	public static function checkExpired($mobile = ''){
		$expire = intval(C('SMS_TIMEOUT_SECONDS')) > 0 ? intval(C('SMS_TIMEOUT_SECONDS')) : self::$timeout;
		$cache = S(array('type'=>'db','prefix'=>'Verify_sms_','expire'=>$expire));
		$verify_cache = $cache->$mobile;
		if($verify_cache){
			return true;
		}else{
			return false;
		}
	}

	/**
	 * 获取定长的随机数字字符串
	 * @param $length 随机数长度，默认6位
	 * @return String
	 */
	private static function getCode($length=6){
		$length = intval($length);
		if($length <= 0){
			$length = 6;
		}
		// php7中不能使用String关键字作为类名
		// $string = new \Org\Util\String();
		// return $string->randString($length, 1);
		$min = str_pad(1, $length, 0, STR_PAD_RIGHT);
		$max = str_pad(9, $length, 9, STR_PAD_RIGHT);
		return mt_rand($min, $max);
	}

	/**
	  * 发送模板短信
	  * @demo SmsHelper::sendTemplateSMS("13800000000" ,array('6532','5'),"1");
	  * @param to 手机号码集合,用英文逗号分开
	  * @param datas 内容数据 格式为数组 例如：array('Marry','Alon')，如不需替换请填 null
	  * @param $notifyTypeName notifyType表中数据
	  * @param $tempId 模板Id,测试应用和未上线应用使用测试模板请填写1，正式应用上线后填写已申请审核通过的模板ID
	  */
	public static function sendTemplateSMS($to='', $datas=array(), $notifyTypeName='')
	{
		$actionId = 0;
		$actionLogTypeName = "SendTemplateSMS";
		try {
			$UserAction = new UserAction();
			$userid = $UserAction->getUserId();

			// add action
			$params = array(
				'actionTypeId'	=>	getActionTypeId($actionLogTypeName, 'api'),
				'uid'			=>	$userid,
			);
			$actionId = B('Common\Common\Behavior\Action', '', $params);

			$requestBody = array(
				'to'		=>	$to,
				'datas'		=>	$datas,
				'notifyTypeName'	=>	$notifyTypeName,
			);
			$params = array(
				'actionId'		=>	$actionId,
				'requestUrl'	=>	__SELF__,
				'requestHeaders'=>	json_encode(getallheaders()),
				'requestBody'	=>	json_encode($requestBody),
			);
			B('Common\Common\Behavior\ActionDescription', '', $params);

			$log_body = array(
				'logTypeId'			=>	getLogTypeId($actionLogTypeName, 'api'),
				'toUid'				=>	$userid,
				'relatedActionId'	=>	$actionId,
				'body'				=>	$datas ? $datas : array(),
			);
			$params = array(
				'logTypeId'			=>	getLogTypeId($actionLogTypeName, 'api'),
				'toUid'				=>	$userid,
				'relatedActionId'	=>	$actionId,
				'body'				=>	$log_body,
			);
			B('Common\Common\Behavior\Log', '', $params);

			// 获取tempId
			$notifyTypeName = 'Notify_Api_'.$notifyTypeName.'_SMS';
			$Log_notifyAction = new Log_notifyAction();
			$notifyTypeInfo = $Log_notifyAction->getNotifyTypeInfoByTypeName($notifyTypeName, true);
			if(!$notifyTypeInfo){
				throwError(60003, "短信模板未找到或不可用"); //isValid=0或无记录
			}else{
				$tempId = $notifyTypeInfo['notifyTemplateId'];
				$notifyTypeId = $notifyTypeInfo['notifyTypeId'];

				// 检查短信是否可以发送
				if(!$tempId){
					// 没有模板id
					throwError(60003, "短信模板未找到或不可用");
				}
			}

			/**
			 * ============================================================
			 * process 发送短信 start
			 * ============================================================
			 */

			// 初始化REST SDK
		    // global $accountSid,$accountToken,$appId,$serverIP,$serverPort,$softVersion;
			$accountSid = C('SMS_CONFIG.SmsAccountSid');
			$accountToken = C('SMS_CONFIG.SmsAccountToken');
			$appId = C('SMS_CONFIG.SmsAppId');
			$serverIP = C('SMS_CONFIG.SmsServerIP');
			$serverPort = C('SMS_CONFIG.SmsServerPort');
			$softVersion = C('SMS_CONFIG.SmsSoftVersion');

			$rest = new SmsRestSdk($serverIP,$serverPort,$softVersion);
			$rest->setAccount($accountSid,$accountToken);
			$rest->setAppId($appId);

			// 发送模板短信
			\Think\Log::record("Sending TemplateSMS to ".$to);
			$result = $rest->sendTemplateSMS($to, $datas, $tempId);
			if($result == NULL ) {
				\Think\Log::record("result error!");
				throwError(60001, "接口返回空");
			}
			if($result->statusCode!=0) {
				\Think\Log::record("error code :" . $result->statusCode);
				\Think\Log::record("error msg :" . $result->statusMsg);
				//TODO 添加错误处理逻辑
				throwError(60002, "短信发送失败(". "error code :" . $result->statusCode . ", error msg :" . $result->statusMsg .")");
			}else{
				\Think\Log::record("Sending TemplateSMS success!");
				// 获取返回信息
				$smsmessage = $result->TemplateSMS;
				\Think\Log::record("dateCreated:".$smsmessage->dateCreated);
				\Think\Log::record("smsMessageSid:".$smsmessage->smsMessageSid);
				//TODO 添加成功处理逻辑

				$actionCallbackData = array(
					'dateCreated'	=>	$smsmessage->dateCreated,
					'smsMessageSid'	=>	$smsmessage->smsMessageSid,
				);

				// update action
		        $params = array(
		            'actionId'      =>  $actionId,
		            'code'          =>  0,
		            'uid'           =>  $userid,
		        );
				$params['callbackTime'] = true;
		        B('Common\Common\Behavior\Action', '', $params);

				// update action description
				$params = array(
		            'actionId'          =>  $actionId,
		        );
				$params['callbackResponse'] = json_encode( $actionCallbackData );
				B('Common\Common\Behavior\ActionDescription', '', $params);

				// success
				return true;
			}

		} catch(\Think\Exception $e) {
			$error_code = $e->getCode();
			$error_msg = $e->getMessage().";tempId:".$tempId;

			// update action
	        $params = array(
	            'actionId'      =>  $actionId,
	            'code'          =>  $error_code,
	        );
			$params['callbackTime'] = true;
			B('Common\Common\Behavior\Action', '', $params);

			// update action description
			$output_data_array = ApiHelper::output($error_code, $error_msg, 'array');
			$params = array(
	            'actionId'          =>  $actionId,
	        );
			$params['callbackResponse'] = json_encode( $output_data_array );
			B('Common\Common\Behavior\ActionDescription', '', $params);

    		return false;

        }

	}

}

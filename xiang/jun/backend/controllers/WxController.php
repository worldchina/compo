<?php
/**
 * Created by PhpStorm.
 * User: ly
 * Date: 16-5-5
 * Time: 下午2:59
 */
namespace backend\controllers;

use backend\models\WeiXin;
use Yii;
use yii\web\Controller;

/**
 * Class SiteController
 * @package backend\controllers
 */
class WxController extends Controller
{
    public function actionIndex(){
        $WxObj = new WeiXin();
        if (!isset($_GET['echostr'])) {
            return $WxObj->responseMsg();
        }else{
            return $WxObj->valid();
        }
    }
}

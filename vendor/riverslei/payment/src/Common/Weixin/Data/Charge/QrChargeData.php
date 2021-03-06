<?php
/**
 * Created by PhpStorm.
 * User: helei
 * Date: 16/7/31
 * Time: 上午8:49
 */

namespace Payment\Common\Weixin\Data\Charge;

use Payment\Common\PayException;
use Payment\Utils\ArrayUtil;

/**
 * Class WebChargeData
 *
 * @inheritdoc
 * @property string $product_id  扫码支付时,必须设置该参数
 * @property string $openid  trade_type=JSAPI，此参数必传，用户在商户appid下的唯一标识
 * @property string $sub_appid 微信分配的子商户公众账号ID
 * @property string $sub_mch_id 	微信支付分配的子商户号
 *
 * @package Payment\Common\Weixin\Data\Charge
 */
class QrChargeData extends ChargeBaseData
{

    /**
     * 生成下单的数据
     */
    protected function buildData()
    {
        $signData = [
            // 基本数据
            'appid' => trim($this->appId),
            'mch_id'    => trim($this->mchId),
            'nonce_str' => $this->nonceStr,
            'sign_type' => $this->signType,
            'fee_type'  => $this->feeType,
            'notify_url'    => $this->notifyUrl,
            'trade_type'    => $this->tradeType, //设置APP支付
            'limit_pay' => $this->limitPay,  // 指定不使用信用卡

            // 业务数据
            'device_info'   => $this->terminal_id,
            'body'  => trim($this->subject),
            //'detail' => json_encode($this->body, JSON_UNESCAPED_UNICODE);
            'attach'    => trim($this->return_param),
            'out_trade_no'  => trim($this->order_no),
            'total_fee' => $this->amount,
            'spbill_create_ip'  => trim($this->client_ip),
            'time_start'    => $this->timeStart,
            'time_expire'   => $this->timeout_express,
            'openid' => $this->openid,
            'product_id'    => $this->product_id,

            // 服务商
            'sub_appid' => $this->sub_appid,
            'sub_mch_id' => $this->sub_mch_id,
        ];

        // 移除数组中的空值
        $this->retData = ArrayUtil::paraFilter($signData);
    }

    protected function checkDataParam()
    {
        parent::checkDataParam(); // TODO: Change the autogenerated stub

        // 扫码支付,必须设置product_id  参数
        $productId = $this->product_id;
        if (empty($productId)) {
            throw new PayException('扫码支付,必须设置商品ID.');
        }
    }
}

<?php
namespace Crabstudio\BaoKim\Controller\Component;

use Cake\Core\Configure;
use Cake\Controller\Component;
use Cake\Network\Request;

class BaoKimComponent extends Component {

    static public $baokim_url = 'https://www.baokim.vn/payment/customize_payment/order';

    protected $_defaultConfig = [
        'merchant_id' => null,
        'secure_pass' => null,
        'business' => null
    ];

     public function initialize(array $config) {
        parent::initialize($config);
        $this->_defaultConfig = array_merge($this->_defaultConfig, Configure::read('BaoKim'));
    }

    /**
     * Hàm xây dựng url chuyển đến BaoKim.vn thực hiện thanh toán, trong đó có tham số mã hóa (còn gọi là public key)
     * @param $order_id             Mã đơn hàng
     * @param $business             Email tài khoản người bán
     * @param $total_amount         Giá trị đơn hàng
     * @param $shipping_fee         Phí vận chuyển
     * @param $tax_fee              Thuế
     * @param $order_description    Mô tả đơn hàng
     * @param $url_success          Url trả về khi thanh toán thành công
     * @param $url_cancel           Url trả về khi hủy thanh toán
     * @param $url_detail           Url chi tiết đơn hàng
     * @return string url
     */
    public function createRequestUrl($order_id, $total_amount, $shipping_fee, $tax_fee, $order_description, $url_success, $url_cancel, $url_detail) {

        // Mảng các tham số chuyển tới baokim.vn
        $params = array(
            'merchant_id'       =>  strval($this->_defaultConfig['merchant_id']),
            'order_id'          =>  strval($order_id),
            'business'          =>  strval($this->_defaultConfig['business']),
            'total_amount'      =>  strval($total_amount),
            'shipping_fee'      =>  strval($shipping_fee),
            'tax_fee'           =>  strval($tax_fee),
            'order_description' =>  strval($order_description),
            'url_success'       =>  strtolower($url_success),
            'url_cancel'        =>  strtolower($url_cancel),
            'url_detail'        =>  strtolower($url_detail)
        );
        ksort($params);

        $params['checksum'] = $this->__hash($params);

        //Kiểm tra  biến $redirect_url xem có '?' không, nếu không có thì bổ sung vào
        $redirect_url = self::$baokim_url;
        if (strpos($redirect_url, '?') === false) {
            $redirect_url .= '?';
        } else if (substr($redirect_url, strlen($redirect_url)-1, 1) != '?' && strpos($redirect_url, '&') === false) {
            // Nếu biến $redirect_url có '?' nhưng không kết thúc bằng '?' và có chứa dấu '&' thì bổ sung vào cuối
            $redirect_url .= '&';
        }

        // Tạo đoạn url chứa tham số
        $url_params = '';
        foreach ($params as $key => $value) {
            if ($url_params == '')
                $url_params .= $key . '=' . urlencode($value);
            else
                $url_params .= '&' . $key . '=' . urlencode($value);
        }
        return $redirect_url . $url_params;
    }
    
    /**
     * Hàm thực hiện xác minh tính chính xác thông tin trả về từ BaoKim.vn
     * @param Request chứa tham số trả về trên url
     * @return boolean
     */
    public function verifyResponseUrl(Request $request = null) {
        // If request null or checksum doesn't exist
        if(!$request || !isset($request->query['checksum'])) {
            return false;
        }
        $checksum = $request->query['checksum'];
        unset($request->query['checksum']);
        ksort($request->query);

        // Xác thực mã của chủ web với mã trả về từ baokim.vn
        return ($this->__hash($request->query) === $checksum);
    }

    /**
     * 
     * @param array $params
     * @return string
     */
    private function __hash(array $params = []) {
        return strtoupper(md5($this->_defaultConfig['secure_pass'] . implode('', $params)));
    }
}

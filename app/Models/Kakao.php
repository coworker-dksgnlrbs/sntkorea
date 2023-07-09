<?php

namespace App\Models;

use App\Enums\KakaoTemplate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kakao extends Model
{
    use HasFactory;

    protected $config;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->config = [
            "apiKey" => config("hello-message.key"),
            "apiSecret" => config("hello-message.secret"),
            "protocol" => "https",
            "domain" => "api.solapi.com",
            "prefix" => ""
        ];
    }

    public function send($to, $data, $template)
    {
        if(!$template)
            return false;

        $messages = [];

        $messages[] = $this->getMessage($to, $data, $template);

        $params = array(
            "agent" => array(
                "sdkVersion" => "PHP-SDK v4.0",
                "osPlatform" => PHP_OS . ", PHP Version " . phpversion()
            ),
            "messages" => $messages
        );

        $this->request("POST", "/messages/v4/send-many", $params);
    }

    public function getMessage($to, $data, $template)
    {
        $templateId = KakaoTemplate::getTemplateId($template);

        $message = [];

        if($template == KakaoTemplate::VERIFY_NUMBER){
            $message = [
                "#{number}" => $data["number"]
            ];
        }

        if($template == KakaoTemplate::START_DELIVERY){
            $message = [
                "#{order_id}" => $data["order_id"],
                "#{product_title}" => $data["product_title"],
                "#{delivery_number}" => $data["delivery_number"],
            ];
        }

        if($template == KakaoTemplate::SUCCESS_ORDER){
            $message = array(
                "#{id}" => $data["id"], // order id
                "#{name}" => $data["name"],
                "#{merchant_id}" => $data["merchant_id"],
                "#{price_real}" => $data["price_real"],
                "#{updated_at}" => $data["updated_at"],
                "#{pay_method_name}" => $data["pay_method_name"]
            );
        }

        return [
            "to" => $to,
            "from" => config("hello-message.from"),
            "kakaoOptions" => [
                "pfId" => config("hello-message.pf_id"),
                "templateId" => $templateId,
                "variables" => $message
            ]
        ];
    }

    public function get_header() {
        $apiKey = $this->config["apiKey"];
        $apiSecret = $this->config["apiSecret"];
        date_default_timezone_set('Asia/Seoul');
        $date = date('Y-m-d\TH:i:s.Z\Z', time());
        $salt = uniqid();
        $signature = hash_hmac('sha256', $date.$salt, $apiSecret);
        return "Authorization: HMAC-SHA256 apiKey={$apiKey}, date={$date}, salt={$salt}, signature={$signature}";
    }

    public function request($method, $resource, $data = false, $headers = null) {
        $url = "{$this->config['protocol']}://{$this->config['domain']}";

        if ($this->config['prefix']) $url .= $this->config['prefix'];
        $url .= $resource;

        try {
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
            switch ($method) {
                case "POST":
                case "PUT":
                case "DELETE":
                    if ($data) curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
                    break;
                default: // GET
                    if ($data) $url = sprintf("%s?%s", $url, http_build_query($data));
            }
            $http_headers = array($this->get_header(), "Content-Type: application/json");
            if (is_array($headers)) $http_headers = array_merge($http_headers, $headers);
            curl_setopt($curl, CURLOPT_HTTPHEADER, $http_headers);
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            if (curl_error($curl)) {
                print curl_error($curl);
            }
            $result = curl_exec($curl);
            curl_close($curl);
            return json_decode($result);
        } catch (Exception $err) {

            return $err;
        }
    }

    public function create_group_params($params) {
        return request("POST", "/messages/v4/groups", $params);
    }

    public function create_group() {
        $params = new stdClass();
        $params->sdkVersion = 'PHP-SDK v4.0';
        $params->osPlatform = PHP_OS . ', PHP Version ' . phpversion();
        $result = request("POST", "/messages/v4/groups", $params);
        return $result->groupId;
    }

    public function get_group_info($groupId) {
        return request("GET", "/messages/v4/groups/{$groupId}");
    }

    public function get_group_list($limit = 20) {
        $data = new stdClass();
        $data->limit = $limit;
        return request("GET", "/messages/v4/groups", $data);
    }

    public function delete_group($groupId) {
        return request("DELETE", "/messages/v4/groups/{$groupId}");
    }

    public function add_message_params($groupId, $params) {
        return request("PUT", "/messages/v4/groups/{$groupId}/messages", $params);
    }

    public function add_alimtalk($groupId, $pfId, $templateId, $to, $from, $text, $subject = null) {
        $kakaoOptions = new stdClass();
        $kakaoOptions->pfId = $pfId;
        $kakaoOptions->templateId = $templateId;
        $params = new stdClass();
        $message = new stdClass();
        $message->to = $to;
        $message->from = $from;
        $message->text = $text;
        $message->subject = $subject;
        $message->kakaoOptions = $kakaoOptions;
        $params->messages = json_encode(array($message));
        return add_message_params($groupId, $params);
    }

    public function add_chingutalk($groupId, $pfId, $to, $from, $text, $subject = null) {
        $kakaoOptions = new stdClass();
        $kakaoOptions->pfId = $pfId;
        $params = new stdClass();
        $message = new stdClass();
        $message->to = $to;
        $message->from = $from;
        $message->text = $text;
        $message->subject = $subject;
        $message->kakaoOptions = $kakaoOptions;
        $params->messages = json_encode(array($message));
        return add_message_params($groupId, $params);
    }

    public function add_message($groupId, $to, $from, $text, $subject = null, $imageId = null) {
        $params = new stdClass();
        $message = new stdClass();
        $message->text = $text;
        $message->to = $to;
        $message->from = $from;
        if ($subject) $message->subject = $subject;
        if ($imageId) $message->imageId = $imageId;
        $params->messages = json_encode(array($message));
        return add_message_params($groupId, $params);
    }

    public function create_image_params($params) {
        return request("POST", "/storage/v1/files", $params);
    }

// MMS | RCS
    public function create_image_type($path, $type) {
        // $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $imageData = base64_encode($data);
        $params = new stdClass();
        $params->type = $type;
        $params->file = $imageData;
        $image_info = create_image_params($params);
        return $image_info->fileId;
    }

    public function create_image($path) {
        return create_image_type($path, 'MMS');
    }

    public function create_rcs_image($path) {
        return create_image_type($path, 'RCS');
    }

// 친구톡 이미지
    public function create_kakao_image($path, $link) {
        // $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $imageData = base64_encode($data);
        $params = new stdClass();
        $params->type = "KAKAO";
        $params->file = $imageData;
        $params->link = $link;
        $image_info = create_image_params($params);
        return $image_info->fileId;
    }

    public function get_group_messages($groupId) {
        return  request("GET", "/messages/v4/groups/{$groupId}/messages");
    }

    public function send_group($groupId) {
        return request("POST", "/messages/v4/groups/{$groupId}/send");
    }

    public function delete_messages($groupId, $messageIds = array()) {
        if (!is_array($messageIds)) $messageIds = array($messageIds);
        $params = array(
            "messageIds" => $messageIds
        );
        return request("DELETE", "/messages/v4/groups/{$groupId}/messages", $params);
    }

    public function send_one_alimtalk($pfId, $templateId, $to, $from, $text, $buttons = array()) {
        $kakaoOptions = new stdClass();
        $kakaoOptions->pfId = $pfId;
        $kakaoOptions->templateId = $templateId;
        if (count($buttons) > 0) $kakaoOptions->buttons = $buttons;
        $params = new stdClass();
        $message = new stdClass();
        $message->type = "ATA";
        $message->to = $to;
        $message->from = $from;
        $message->text = $text;
        $message->kakaoOptions = $kakaoOptions;
        $params->message = $message;

        return request("POST", "/messages/v4/send", $params);
    }

    public function send_one_chingutalk($pfId, $to, $from, $text, $buttons = array()) {
        $kakaoOptions = new stdClass();
        $kakaoOptions->pfId = $pfId;
        if (count($buttons) > 0) $kakaoOptions->buttons = $buttons;
        $params = new stdClass();
        $message = new stdClass();
        $message->type = "CTA";
        $message->to = $to;
        $message->from = $from;
        $message->text = $text;
        $message->kakaoOptions = $kakaoOptions;
        $params->message = $message;

        return request("POST", "/messages/v4/send", $params);
    }

    public function send_one_message_params($params) {
        return request("POST", "/messages/v4/send", $params);
    }

    public function send_one_message($to, $from, $text, $subject = null, $imageId = null) {
        $params = new stdClass();
        $message = new stdClass();
        $message->text = $text;
        $message->to = $to;
        $message->from = $from;
        if ($subject) $message->subject = $subject;
        if ($imageId) $message->imageId = $imageId;
        $params->message = $message;
        return send_one_message_params($params);
    }

    public function send_messages($messages) {
        $params = array(
            "agent" => array(
                "sdkVersion" => "PHP-SDK v4.0",
                "osPlatform" => PHP_OS . ", PHP Version " . phpversion()
            ),
            "messages" => $messages
        );
        return request("POST", "/messages/v4/send-many", $params);
    }

    public function add_messages($groupId, $messages) {
        $params = array(
            "messages" => $messages
        );
        return add_message_params($groupId, $params);
    }

    public function get_balance() {
        return request("GET", "/cash/v1/balance");
    }

    public function get_messages($params = null) {
        // if (!$params) $params = new stdClass();
        return request("GET", "/messages/v4/list", $params);
    }
}

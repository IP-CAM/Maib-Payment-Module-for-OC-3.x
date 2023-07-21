<?php

// Include required Maib API SDK classes
require_once DIR_SYSTEM . "library/maib/src/MaibAuthRequest.php";
require_once DIR_SYSTEM . "library/maib/src/MaibApiRequest.php";
require_once DIR_SYSTEM . "library/maib/src/MaibSdk.php";

// Create aliases for classes
class_alias("MaibEcomm\MaibSdk\MaibAuthRequest", "MaibAuthRequest");
class_alias("MaibEcomm\MaibSdk\MaibApiRequest", "MaibApiRequest");

class ControllerExtensionPaymentMaib extends Controller
{
    private $log;

    public function __construct($registry)
    {
        parent::__construct($registry);
        $this->log = new Log("maib.log");
    }

    // Render the maib payment method on the checkout page
    public function index()
    {
        $this->load->language("extension/payment/maib");
        $this->load->model("checkout/order");
        $this->load->model("localisation/currency");

        $data["button_confirm"] = $this->language->get("button_confirm");
        $data["action"] = $this->url->link(
            "extension/payment/maib/pay",
            "",
            true
        );

        // Check if the order currency is supported
        $currency_codes = ["USD", "EUR", "MDL"];
        $order_id = $this->session->data["order_id"];
        $order_info = $this->model_checkout_order->getOrder($order_id);

        if (!in_array($order_info["currency_code"], $currency_codes)) {
            $error_message = $this->language->get("error_no_currency");
            $this->logIfDebug($error_message, "error");
            return '<div class="alert alert-danger danger">' .
                $error_message .
                "</div>";
        }

        return $this->load->view("extension/payment/maib", $data);
    }

    // Initiate payment process
    public function pay()
    {
        $this->load->model("checkout/order");
        $this->load->model("localisation/currency");
        $this->load->model("extension/payment/maib");
      
        $order_id = $this->session->data["order_id"];
        $order_info = $this->model_checkout_order->getOrder($order_id);
        $lang = substr($this->session->data["language"], 0, 2);

        $ok_url = html_entity_decode(
            $this->url->link("extension/payment/maib/ok", "payId=", true)
        );
        $fail_url = html_entity_decode(
            $this->url->link("extension/payment/maib/fail", "payId=", true)
        );
        $callback_url = $this->url->link("extension/payment/maib/callback");

        $amount = $order_info["total"] * $order_info['currency_value'];
        $currency = $order_info["currency_code"];
        $description = [];
        $product_items = [];

        foreach ($this->cart->getProducts() as $product) {
            $description[] = $product["quantity"] . " x " . $product["name"];

            $product_items[] = [
                "id" => $product["product_id"],
                "name" => $product["name"],
                "price" => $product["price"],
                "quantity" => (float) number_format(
                    $product["quantity"],
                    1,
                    ".",
                    ""
                ),
            ];
        }

        $descr = implode(", ", $description);

        $client_name =
            $order_info["payment_firstname"] .
            " " .
            $order_info["payment_lastname"];
        $email = $order_info["email"];
        $phone = $order_info["telephone"];

        $shipping_cost = $this->cart->getSubTotal() - $this->cart->getTotal();
        $delivery = $this->session->data["shipping_method"]["cost"];

        $params = [
            "amount" => (float) number_format($amount, 2, ".", ""),
            "currency" => $currency,
            "clientIp" => $this->getClientIp(),
            "language" => $lang,
            "description" => substr($descr, 0, 124),
            "orderId" => strval($order_id),
            "clientName" => $client_name,
            "email" => $email,
            "phone" => substr($phone, 0, 40),
            "delivery" => (float) number_format($delivery, 2, ".", ""),
            "okUrl" => $ok_url,
            "failUrl" => $fail_url,
            "callbackUrl" => $callback_url,
            "items" => $product_items,
        ];

        $this->logIfDebug(
            "Order params: " .
                json_encode($params, JSON_PRETTY_PRINT) .
                ", order_id: " .
                $order_id,
            "info"
        );

        try {
            // Initiate Direct Payment Request to maib API
            $response = MaibApiRequest::create()->pay(
                $params,
                $this->getAccessToken()
            );
            if (!isset($response->payId)) {
                $this->logIfDebug(
                    "No valid response from maib API, order_id: " . $order_id,
                    "error"
                );
                $this->response->redirect(
                    $this->url->link("checkout/failure", "", true)
                );
            } else {
                $this->logIfDebug(
                    "Pay endpoint response: " .
                        json_encode($response, JSON_PRETTY_PRINT) .
                        ", order_id: " .
                        $order_id,
                    "info"
                );
                $this->model_checkout_order->addOrderHistory(
                    $order_id,
                    $this->config->get("payment_maib_order_pending_status_id"),
                    "Payment_ID: {$response->payId}"
                );
                $this->response->redirect($response->payUrl);
            }
        } catch (Exception $ex) {
            $this->logIfDebug("Payment error: " . $ex->getMessage(), "error");
            $this->response->redirect(
                $this->url->link("checkout/failure", "", true)
            );
        }
    }

    // Get Access Token
    public function getAccessToken()
    {
        $project_id = $this->config->get("payment_maib_project_id");
        $project_secret = $this->config->get("payment_maib_project_secret");
        $signature_key = $this->config->get("payment_maib_signature_key");

        // Check if access token exists in cache and is not expired
        if (
            $this->cache->get("access_token") &&
            $this->cache->get("access_token_expires") > time()
        ) {
            $access_token = $this->cache->get("access_token");
            $this->logIfDebug(
                "Access token from cache: " . $access_token,
                "info"
            );
            return $access_token;
        }

        try {
            // Initiate Get Access Token Request to maib API
            $response = MaibAuthRequest::create()->generateToken(
                $project_id,
                $project_secret
            );
            $logMessage = sprintf(
                "Response from Access Token: %s",
                json_encode($response, JSON_PRETTY_PRINT)
            );
            $this->logIfDebug($logMessage, "info");
            $access_token = $response->accessToken;

            // Store the access token and its expiration time in cache
            $this->cache->set("access_token", $access_token);
            $this->cache->set(
                "access_token_expires",
                time() + $response->expiresIn
            );
        } catch (Exception $ex) {
            $this->logIfDebug(
                "Access token error: " . $ex->getMessage(),
                "error"
            );
            $this->response->redirect(
                $this->url->link("checkout/failure", "", true)
            );
        }

        return $access_token;
    }

    // Notification on Callback URL from maib gateway
    public function callback()
    {
        $this->load->model("checkout/order");
        $this->load->language("extension/payment/maib");

        if ($_SERVER["REQUEST_METHOD"] === "GET") {
            $this->session->data["error"] = $this->language->get(
                "error_callback_url"
            );
            $this->response->redirect($this->url->link("checkout/cart"));
            return;
        }

        $json = file_get_contents("php://input");
        $data = json_decode($json, true);

        if (!isset($data["signature"]) || !isset($data["result"])) {
            $this->logIfDebug(
                "Callback URL - Signature or Payment data not found in notification.",
                "error"
            );
            exit();
        }

        $this->logIfDebug(
            sprintf(
                "Notification on Callback URL: %s",
                json_encode($data, JSON_PRETTY_PRINT)
            ),
            "info"
        );
        $data_result = $data["result"]; // Data from "result" object
        $sortedDataByKeys = $this->sortByKeyRecursive($data_result); // Sort an array by key recursively
        $key = $this->config->get("payment_maib_signature_key"); // Signature Key from Project settings
        $sortedDataByKeys[] = $key; // Add checkout Secret Key to the end of data array
        $signString = implode(":", $sortedDataByKeys); // Implode array recursively
        $sign = base64_encode(hash("sha256", $signString, true)); // Result Hash

        $pay_id = isset($data_result["payId"]) ? $data_result["payId"] : false;
        $order_id = isset($data_result["orderId"])
            ? (int) $data_result["orderId"]
            : false;
        $status = isset($data_result["status"])
            ? $data_result["status"]
            : false;

        if ($sign !== $data["signature"]) {
            echo "ERROR";
            $this->logIfDebug(
                sprintf("Signature is invalid: %s", $sign),
                "info"
            );
            exit();
        }

        echo "OK";
        $this->logIfDebug(sprintf("Signature is valid: %s", $sign), "info");

        if (!$order_id || !$status) {
            $this->logIfDebug(
                "Callback URL - Order ID or Status not found in notification.",
                "error"
            );
            exit();
        }

        $order_info = $this->model_checkout_order->getOrder($order_id);

        if (!$order_info) {
            $this->logIfDebug(
                "Callback URL - Order ID not found in OpenCart Orders.",
                "error"
            );
            exit();
        }

        if ($status === "OK") {
            // Payment success logic
            $order_status_id = $this->config->get(
                "payment_maib_order_success_status_id"
            );
            $order_note = sprintf(
                "Payment_Info: %s",
                json_encode($data_result, JSON_PRETTY_PRINT)
            );
            $this->model_checkout_order->addOrderHistory(
                $order_id,
                $order_status_id,
                $order_note,
                $notify = true
            );
        } else {
            // Payment failure logic
            $order_status_id = $this->config->get(
                "payment_maib_order_fail_status_id"
            );
            $order_note = sprintf(
                "Payment_Info: %s",
                json_encode($data_result, JSON_PRETTY_PRINT)
            );
            $this->model_checkout_order->addOrderHistory(
                $order_id,
                $order_status_id,
                $order_note,
                $notify = true
            );
        }

        exit();
    }

    // Helper function: Sort an array by key recursively
    private function sortByKeyRecursive(array $array)
    {
        ksort($array, SORT_STRING);
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $array[$key] = $this->sortByKeyRecursive($value);
            }
        }
        return $array;
    }

    // Helper function: Implode array recursively
    private function implodeRecursive($separator, $array)
    {
        $result = "";
        foreach ($array as $item) {
            $result .=
                (is_array($item)
                    ? $this->implodeRecursive($separator, $item)
                    : (string) $item) . $separator;
        }
        return substr($result, 0, -1);
    }

    // Redirect back from maib gateway if fail payment
    public function fail()
    {
        $this->load->model("checkout/order");
        $this->load->language("extension/payment/maib");

        if (
            isset($this->request->get["payId"]) &&
            isset($this->request->get["orderId"])
        ) {
            $payId = substr(
                $this->request->get["payId"],
                strpos($this->request->get["payId"], "=") + 1
            );
            $orderId = (int) $this->request->get["orderId"];

            $this->logIfDebug(
                "Return to Fail URL. Pay ID: " .
                    $payId .
                    ", Order ID: " .
                    $orderId
            );

            $order_info = $this->model_checkout_order->getOrder($orderId);

            if ($order_info) {
                if (
                    $order_info["order_status_id"] ==
                    $this->config->get("payment_maib_order_fail_status_id")
                ) {
                    $this->response->redirect(
                        $this->url->link("checkout/failure", "", true)
                    );
                } else {
                    $this->send_payment_info_request(
                        $payId,
                        $this->getAccessToken(),
                        $orderId
                    );
                }
            } else {
                $this->logIfDebug("Fail URL: Order not found.", "error");
                $this->session->data["error"] = $this->language->get(
                    "error_no_payment"
                );
                $this->response->redirect(
                    $this->url->link("checkout/checkout", "", true)
                );
            }
        } else {
            $this->logIfDebug(
                "Fail URL: Invalid or missing payId/orderId.",
                "error"
            );
            $this->session->data["error"] = $this->language->get(
                "error_no_payment"
            );
            $this->response->redirect(
                $this->url->link("checkout/checkout", "", true)
            );
        }
    }

    // Redirect back from maib gateway if successful payment
    public function ok()
    {
        $this->load->model("checkout/order");
        $this->load->language("extension/payment/maib");

        if (
            isset($this->request->get["payId"]) &&
            isset($this->request->get["orderId"])
        ) {
            $payId = substr(
                $this->request->get["payId"],
                strpos($this->request->get["payId"], "=") + 1
            );
            $orderId = (int) $this->request->get["orderId"];

            $this->logIfDebug(
                "Return to Ok URL. Pay ID: " .
                    $payId .
                    ", Order ID: " .
                    $orderId
            );

            $order_info = $this->model_checkout_order->getOrder($orderId);

            if ($order_info) {
                if (
                    $order_info["order_status_id"] ==
                    $this->config->get("payment_maib_order_success_status_id")
                ) {
                    $this->response->redirect(
                        $this->url->link("checkout/success", "", true)
                    );
                } else {
                    $this->send_payment_info_request(
                        $payId,
                        $this->getAccessToken(),
                        $orderId
                    );
                }
            } else {
                $this->logIfDebug("Ok URL: Order not found.", "error");
                $this->session->data["error"] = $this->language->get(
                    "error_no_payment"
                );
                $this->response->redirect(
                    $this->url->link("checkout/checkout", "", true)
                );
            }
        } else {
            $this->logIfDebug(
                "Ok URL: Invalid or missing payId/orderId.",
                "error"
            );
            $this->session->data["error"] = $this->language->get(
                "error_no_payment"
            );
            $this->response->redirect(
                $this->url->link("checkout/checkout", "", true)
            );
        }
    }

    // Send payment info request to maib API
    private function send_payment_info_request($pay_id, $token, $order_id)
    {
        $order_info = $this->model_checkout_order->getOrder($order_id);
        $this->logIfDebug(
            sprintf(
                "Request to pay-info, pay_id: %s, order_id: %d",
                $pay_id,
                $order_id
            ),
            "info"
        );

        try {
            // Initiate Payment Info Request
            $response = MaibApiRequest::create()->payInfo($pay_id, $token);
            $this->logIfDebug(
                sprintf(
                    "Response from pay-info: %s, order_id: %d",
                    json_encode($response, JSON_PRETTY_PRINT),
                    $order_id
                ),
                "info"
            );
        } catch (Exception $ex) {
            $this->logIfDebug($ex, "error");
            $this->session->data["error"] = $this->language->get(
                "error_no_payment"
            );
            $this->response->redirect(
                $this->url->link("checkout/checkout", "", true)
            );
            exit();
        }

        if ($response && $response->status === "OK") {
            $this->model_checkout_order->addOrderHistory(
                $order_id,
                $this->config->get("payment_maib_order_success_status_id"),
                "Payment_Info: " . json_encode($response, JSON_PRETTY_PRINT),
                $notify = true
            );
            $this->response->redirect(
                $this->url->link("checkout/success", "", true)
            );
            exit();
        } else {
            $this->model_checkout_order->addOrderHistory(
                $order_id,
                $this->config->get("payment_maib_order_fail_status_id"),
                "Payment_Info: " . json_encode($response, JSON_PRETTY_PRINT),
                $notify = true
            );
            $this->response->redirect(
                $this->url->link("checkout/failure", "", true)
            );
            exit();
        }
    }
	
    // Send refund request to maib API when order status changed to Refunded
	public function addOrderHistoryBefore($route, &$data) {
		$order_id = $data[0];
		$order_status_id = $data[1];
		$comment = ($data[2] ?? '');
		$notify = ($data[2] ?? false);
		$override = ($data[2] ?? false);

		if ($order_status_id !== $this->config->get("payment_maib_order_refund_status_id")) {
			return;
		}

		$this->load->model('checkout/order');
		$order_info = $this->model_checkout_order->getOrder($order_id);

		if (!$order_info) {
			return;
		}

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_history WHERE order_id = '{$order_id}' AND comment LIKE '%Payment_ID%'");

		if (!$query->num_rows) {
			return;
		}

		$str = $query->row['comment'];
		$explode = explode(": ", $str);

		if (!isset($explode[1])) {
			return;
		}

		list($key, $pay_id) = $explode;

		$params = ['payId' => strval($pay_id)];

		try {
			// Initiate Refund Payment Request to maib API
			$response = MaibApiRequest::create()->refund(
				$params,
				$this->getAccessToken()
			);

			$this->logIfDebug(
				sprintf(
					"Response from refund endpoint: %s, order_id: %d",
					json_encode($response, JSON_PRETTY_PRINT),
					$order_id
				),
				"info"
			);

			if ($response && $response->status === "OK") {
				$order_status_id = $this->config->get("payment_maib_order_refund_status_id");
				$comment = "Success_Refunded";
				$notify = 1;
				$this->logIfDebug(strtr('Full refunded payment @payid for order @orderid', [
					'@payid' => $pay_id,
					'@orderid' => $order_id,
				]), "info");

				// Update order total title for refunded order
				$this->db->query("UPDATE " . DB_PREFIX . "order_total SET title = 'Total (refunded)' WHERE order_id = '{$order_id}' AND code = 'total'");

			} else if ($response && $response->status === "REVERSED") {

				$order_status_id = $this->config->get("payment_maib_order_refund_status_id");
				$comment = "Already_Refunded";
				$notify = 1;
				$this->logIfDebug(strtr('Already refunded payment @payid for order @orderid', [
					'@payid' => $pay_id,
					'@orderid' => $order_id,
				]), "info");

			} else {
				$order_status_id = 9;
				$comment = "Failed_Refund";
				$notify = 0;

				$this->logIfDebug(strtr('Failed refund payment @payid for order @orderid', [
					'@payid' => $pay_id,
					'@orderid' => $order_id,
				]), "error");
			}

		} catch (Exception $e) {
			$order_status_id = 9;
			$comment = "Failed_Refund";
			$notify = 0;

			$this->logIfDebug(strtr('Failed refund payment @payid for order @orderid', [
				'@payid' => $pay_id,
				'@orderid' => $order_id,
			]), "error");
		}

		// Update the $data array with modified values
		$data[0] = $order_id;
		$data[1] = $order_status_id;
		$data[2] = $comment;
		$data[3] = $notify;
	}

	
    // Get the client's IP address
    private function getClientIp()
    {
        if (!empty($this->request->server["HTTP_CLIENT_IP"])) {
            return $this->request->server["HTTP_CLIENT_IP"];
        } elseif (!empty($this->request->server["HTTP_X_FORWARDED_FOR"])) {
            return $this->request->server["HTTP_X_FORWARDED_FOR"];
        } elseif (!empty($this->request->server["REMOTE_ADDR"])) {
            return $this->request->server["REMOTE_ADDR"];
        } else {
            return "127.0.0.1";
        }
    }

    // Log messages if debug mode is enabled
    private function logIfDebug($message, $type = "info")
    {
        if ($this->config->get("payment_maib_debug")) {
            $this->log->write("maib " . $type . ": " . $message);
        }
    }
}
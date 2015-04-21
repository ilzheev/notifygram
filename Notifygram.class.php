<?php
/**
 * Notifygram.org — easy & secure notifications directly to Telegram
 * @author Anton Ilzheev <ilzheev@gmail.com>
 */
class Notifygram
{
    const REQUEST_SUCCESS = 'success';
    const REQUEST_ERROR = 'error';

    protected
        $api_key       = null,
        $api_token        = null,
        $sign 		= null,
        $url        = 'https://notifygram.org/api/v1',
        $response   = null;

	/**
	 * Notifygram declaration
	 */
	 
    public function Notifygram($api_key, $api_token, $sign = null, $url = 'https://notifygram.org/api/v1')
    {
        $this->api_key = $api_key;
        $this->api_token = $api_token;
        $this->sign = $sign;
        $this->url = $url;
    }

    /**
     * Notify all active users
     *
     * @param string $api_key
     * @param string $api_token
     * @param string $message
     *
     * @return boolean|integer
     */
    public function notify($message = NULL)
    {
        $params = array(
            'api_key'    => $this->api_key,
            'api_token'       => $this->api_token,
            'message'        => $message,
        );

        $response = $this->make_request($params);
        return $response == self::REQUEST_SUCCESS;
    }

    /**
     * Notify all active users
     *
     * @param string $api_key
     * @param string $api_token
     * @param string $message
     *
     * @return string "success" || error message
     */    
    protected function make_request(array $params = array())
    {
        $params = $this->join_array_values($params);
        $sign = $this->generate_sign($params);

        $post = http_build_query(array_merge($params, array('sign' => $sign)), '', '&');
        
        if (function_exists('curl_init')) {
            $ch = curl_init($this->url);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $response = curl_exec($ch);
            curl_close($ch);
        } else {
            $context = stream_context_create(array(
                'http' => array(
                    'method' => 'POST',
                    'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                    'content' => $post,
                    'timeout' => 10,
                ),
            ));
            $response = file_get_contents($this->url, false, $context);
        }

        return $this->response = json_decode($response, true);
    }

	/**
	 * Helpers
	 */
    protected function join_array_values($params)
    {
        $result = array();
        foreach ($params as $name => $value) {
            $result[$name] = is_array($value) ? join(',', $value) : $value;
        }
        return $result;
    }
    protected function generate_sign(array $params)
    {
        return md5(sha1(join('', $params) . $this->sign));
    }

}
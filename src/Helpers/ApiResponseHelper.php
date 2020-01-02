<?php


namespace Mappweb\Api\Helpers;

use Carbon\Carbon;

class ApiResponseHelper
{
    public $code;
    public $message;
    public $data;
    public $extra;

    /**
     * ApiResponse constructor.
     * @param $code
     * @param $message
     * @param $data
     * @param array $extra
     */
    public function __construct($code,$message,$data, $extra = [])
    {
        $this->code = $code;
        $this->message = $message;
        $this->data = $data;
        $this->extra = $extra;
    }

    /**
     * @param $message
     */
    public function setMessage($message)
    {
        $this->message= $message;
    }

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    public function getData()
    {
        return $this->data;
    }

    /**
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function response()
    {
        $date_time = Carbon::now();

        $content = [
            'code'    => $this->code,
            'messages'  => $this->message,
            'data'      => $this->data,
            'extra'      => $this->extra
        ];

        $content['completed_at'] = $date_time->toDateTimeString();
        if($this->code) {
            return response($content, $this->code);
        } else {
            return response($content);
        }
    }
}
<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    public $success;
    public $message;
    public $data;

    /**
     * __construct
     *
     * @param  mixed $success
     * @param  mixed $message
     * @param  mixed $data
     * @return void
     */
    public function __construct($success, $message, $data)
    {
        parent::__construct($data);
        $this->success  = $success;
        $this->message = $message;
        $this->data = $data;
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'success' => $this->success,
            'message' => $this->message,
            'data' => $this->data,
        ];
    }
}

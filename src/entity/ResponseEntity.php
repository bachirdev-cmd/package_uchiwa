<?php
namespace AppDAF\ENTITY;

use AppDAF\ABSTRACT\AbstractEntity;

class ResponseEntity extends AbstractEntity
{

    protected ?CitoyenEntity $data;
    protected string $message ;
    protected Statut $status ;
    protected int $code ;

    public function __construct(
        string $message = '',
        Statut $status = Statut::ERROR,
        int $code = 404,
        ?CitoyenEntity $data = null,

    ) {
        $this->data = $data;
        $this->status = $status;
        $this->code = $code;
        $this->message = $message;
    }


    public function toArray(): array
    {
        return [
            'data' => $this->data ? $this->data->toArray() : null,
            'status' => $this->status,
            'code' => $this->code,
            'message' => $this->message,
        ];
    }
    public static function toObject(array $data): static
    {
        return new static();
    }
}

<?php
namespace yaLogsApi;
class logRequest
{
    /**
     * Описание типа log_request
     */
    private $request_id;
    private $counter_id;
    private $source;
    private $date1;
    private $date2;
    private $fields=[];
    /**
     * @var
     */
    private $status;
    /**
     * Размер логов запроса в байтах.
     * @var int
     */
    private $size=-1;

    public function __construct($data=[])
    {
        if (is_array($data) && sizeof($data))
        {
            $this->request_id=$data['request_id'];
            $this->counter_id=$data['counter_id'];
            $this->source=$data['source'];
            $this->date1=$data['date1'];
            $this->date2=$data['date2'];
            $this->fields=$data['fields'];
            $this->status=$data['status'];
        }
    }

    /**
     * @return mixed
     */
    public function getCounterId()
    {
        return $this->counter_id;
    }

    /**
     * @return mixed
     */
    public function getDate1()
    {
        return $this->date1;
    }

    /**
     * @return mixed
     */
    public function getDate2()
    {
        return $this->date2;
    }

    /**
     * @return array|mixed
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * @return mixed
     */
    public function getRequestId()
    {
        return $this->request_id;
    }

    /**
     * @return int
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * @return mixed
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    public function isProcessed()
    {
        return ('processed'==$this->getStatus());
    }

    public function getHash()
    {
        return $this->getCounterId().'_'.$this->getRequestId().'_'.str_replace([' ',':','-'],['_'],$this->getDate1().'_'.$this->getDate2());
    }
}
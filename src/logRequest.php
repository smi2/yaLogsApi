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
    private $parts=[];

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
            if (!empty($data['parts']))
            $this->parts=$data['parts'];
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

    private function humanFileSize($size, $unit = '')
    {
        if ((!$unit && $size >= 1 << 30) || $unit == 'GB') {
            return number_format($size / (1 << 30), 2) . ' GB';
        }
        if ((!$unit && $size >= 1 << 20) || $unit == 'MB') {
            return number_format($size / (1 << 20), 2) . ' MB';
        }
        if ((!$unit && $size >= 1 << 10) || $unit == 'KB') {
            return number_format($size / (1 << 10), 2) . ' KB';
        }

        return number_format($size) . ' bytes';
    }

    public function getPartsNumbers()
    {
        if (!sizeof($this->parts)) return [];
        $out=[];
        foreach ($this->parts as $part)
        {
            $out[$part['part_number']]=1;
        }
        return array_keys($out);
    }
    public function getPartSize($num,$humanSize=true)
    {
        if (!isset($this->parts[$num]['size'])) return false;
        if ($humanSize)
        {
            return $this->humanFileSize($this->parts[$num]['size']);
        }
        return $this->parts[$num]['size'];
    }
    public function getParts()
    {
        return $this->parts;
    }
    public function getHash()
    {
        return
                $this->getCounterId().'_'.
                $this->getRequestId().'_'.
                str_replace([' ',':','-'],['_'],$this->getDate1().'_'.$this->getDate2()).'_'.
                substr(sha1(implode(',',$this->fields)),0,4);
    }
}
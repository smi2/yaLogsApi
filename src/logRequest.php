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
}
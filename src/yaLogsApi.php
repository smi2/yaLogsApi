<?php
namespace yaLogsApi;
class Connector
{

    /**
     * @var \yamApi\Transport
     */
    private $__transport;
    public function __construct($counter_id,$token)
    {
        $this->__transport=new \yamApi\Transport($counter_id,$token);
    }

    /**
     * @return \yamApi\Transport
     */
    protected function transport()
    {
        return $this->__transport;
    }
    /**
     * Создание запроса логов
     *
     * @param $fields string Список полей через запятую
     * @param $source string Источник логов.  Допустимые значения: hits — хиты. visits — визиты.
     */
    public function makeNew($fields,$source,$date1,$date2)
    {
        $this->transport()->postJson('/management/v1/counter/{counterId}/logrequests',
            [
                'date1'=>$date1,
                'date2'=>$date2,
                'fields'=>implode(',',$fields),
                'source'=>$source
            ]
        );

    }

    /**
     * Список запросов логов
     *
     * @return logRequest[]|bool
     */
    public function getList()
    {
        $data=$this->transport()->getJson('/management/v1/counter/{counterId}/logrequests');
        $out=[];
        if (isset($data['requests']))
        {
            foreach ($data['requests'] as $request)
            {
                $out[]=new logRequest($request);

            }
        }
        else
        {
            return false;
        }
        return $out;
    }

    /**
     * Отмена не обработанного запроса логов
     *
     * @param logRequest $request  идентификатор запроса логов.
     * @return logRequest|bool
     */
    public function cancel(logRequest $request)
    {

        $r=$this->transport()->postJson('/management/v1/counter/{counterId}/logrequest/{requestId}/cancel',[],['requestId'=>$request->getRequestId()]);
        if (isset($r['log_request']))
        {
            return new logRequest($r['log_request']);
        }
        return false;
    }

    /**
     * Оценивает возможность создания запроса логов по его примерному размеру.
     * Return Примерное максимально возможное количество дней с учетом текущей квоты.
     *
     * @param $fields string Список полей через запятую
     * @param $source string Источник логов.  Допустимые значения: hits — хиты. visits — визиты.
     * @return int|bool
     */
    public function evaluate($fields,$source,$date1,$date2)
    {
        $r=$this->transport()->getJson('/management/v1/counter/{counterId}/logrequests/evaluate',
            [
                'date1'=>$date1,
                'date2'=>$date2,
                'fields'=>implode(',',$fields),
                'source'=>$source
            ]
            );

        if (isset($r['log_request_evaluation']) && !empty($r['log_request_evaluation']['possible']))
        {
            return intval($r['log_request_evaluation']['max_possible_day_quantity']);
        }
        return false;
    }

    /**
     * ??????
     *
     * @param logRequest $request
     * @return bool|logRequest
     */
    public function info(logRequest $request)
    {
        $r=$this->transport()->getJson('/management/v1/counter/{counterId}/logrequest/{requestId}',[],['requestId'=>$request->getRequestId()]);
        if (isset($r['log_request']))
        {
            return new logRequest($r['log_request']);
        }
        return false;
    }
    /**
     * Скачивание части подготовленных логов обработанного запроса
     *
     * @param logRequest $request
     * @param $partNumber int номер части подготовленных логов обработанного запроса.
     */
    public function download(logRequest $request,$partNumber=0)
    {
        $this->transport()->downloadToFile(
            '/management/v1/counter/{counterId}/logrequest/'.$request->getRequestId().'/part/'.$partNumber.'/download',
            '/tmp/YAM_'.$request->getHash()

        );

        /*
         * GET https://api-metrika.yandex.ru/management/v1/counter/{counterId}/logrequest/{requestId}/part/{partNumber}/download
         */
    }

    /**
     * Очистка подготовленных для скачивания логов обработанного запроса
     * @param logRequest $request
     */
    public function clean(logRequest $request)
    {
        /*
         * POST https://api-metrika.yandex.ru/management/v1/counter/{counterId}/logrequest/{requestId}/clean
         */
    }
}
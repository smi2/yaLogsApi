<?php
namespace yaLogsApi;
class Connector
{
    private $counter_id='';
    private $token='';
    public function __construct($counter_id,$token)
    {
        $this->counter_id=$counter_id;
        $this->token=$token;
    }

    /**
     * Создание запроса логов
     *
     * @param $fields string Список полей через запятую
     * @param $source string Источник логов.  Допустимые значения: hits — хиты. visits — визиты.
     */
    public function newLogRequests($fields,$source)
    {
        /**
         * POST https://api-metrika.yandex.ru/management/v1/counter/{counterId}/logrequests?fields=ym:pv:dateTime,ym:pv:referer&source=hits
         * Формат ответа {"log_request" :  < log_request > }
         * log_request	Запрос логов
         */
    }

    /**
     * Список запросов логов
     *
     */
    public function getLogRequests()
    {
        //    "requests" : [  < log_request > , ... ]
        // GET https://api-metrika.yandex.ru/management/v1/counter/{counterId}/logrequests
    }

    /**
     * Отмена не обработанного запроса логов
     *
     * @param logRequest $request  идентификатор запроса логов.
     */
    public function cancelLogRequests(logRequest $request)
    {
        /**
         * POST https://api-metrika.yandex.ru/management/v1/counter/{counterId}/logrequest/{requestId}/cancel
         * "log_request" :  < log_request >
         *
         */
    }

    /**
     * Оценивает возможность создания запроса логов по его примерному размеру.
     *
     * @param $fields string Список полей через запятую
     * @param $source string Источник логов.  Допустимые значения: hits — хиты. visits — визиты.
     */
    public function evaluateLogRequests($fields,$source)
    {

        /**
         * GET https://api-metrika.yandex.ru/management/v1/counter/{counterId}/logrequests/evaluate ?fields=<string>& source=<log_request_source>
         * {
        "log_request_evaluation" : {
        "possible" :  < boolean > ,
        "max_possible_day_quantity" :  < long >
        }
        }log_request_evaluation	Оценка возможности создания запросов логов.
        log_request_evaluation
        possible	Возможность создания запроса логов.
        max_possible_day_quantity	Примерное максимально возможное количество дней с учетом текущей квоты.
         */
    }

    /**
     * Скачивание части подготовленных логов обработанного запроса
     *
     * @param logRequest $request
     * @param $partNumber int номер части подготовленных логов обработанного запроса.
     */
    public function download(logRequest $request,$partNumber)
    {
            /**
             * GET https://api-metrika.yandex.ru/management/v1/counter/{counterId}/logrequest/{requestId}/part/{partNumber}/download
             */
    }

    /**
     * Очистка подготовленных для скачивания логов обработанного запроса
     * @param logRequest $request
     */
    public function clean(logRequest $request)
    {
        /**
         * POST https://api-metrika.yandex.ru/management/v1/counter/{counterId}/logrequest/{requestId}/clean
         */
    }
}
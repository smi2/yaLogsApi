<?php
namespace yamApi;
class Transport
{
    private $counter_id='';
    private $token='';

    private $host='https://api-metrika.yandex.ru';
    /**
     * @var \Curler\CurlerRolling
     */
    private $roll;

    private $timeout=100000;


    public function __construct($counter_id,$token)
    {
        $this->roll=new \Curler\CurlerRolling();

        $this->token=$token;
        $this->counter_id=$counter_id;
    }

    private function templateUrl($url,$getParams=[],$url_templates=[])
    {
        $url_vars=$url_templates;
        $url_vars['counterId']=$this->counter_id;


        foreach ($url_vars as $key=>$val)
        {
            $url=str_ireplace('{'.$key.'}',urlencode($val),$url);
        }
        if (is_array($getParams))
        {
            $url=$url.'?'.http_build_query($getParams);

        }
        $url=$this->host.'/'.ltrim($url,'/');
        return $url;


    }
    /**
     * @param $url
     * @param $url_vars
     * @param $params
     * @param $is_post
     * @return \Curler\Request
     */
    private function makecurlRequest($url,$getParams=[],$url_templates=[],$is_post=false)
    {
        $url=$this->templateUrl($url,$getParams,$url_templates);

        $request=new \Curler\Request();
        $request->timeOut($this->timeout);

        $request->header('Authorization','OAuth '.$this->token);
        $request->header('Content-Type','application/x-yametrika+json');

        $request->url($url);
        if ($is_post)
        {
            $request->POST();
        }
        else
        {
            $request->GET();
        }

        return $request;
    }

    /**
     *
     * @param $url
     * @return resource
     */
    private function makefopenRequest($url,$encode=false)
    {
        $url=$this->templateUrl($url);

        $header  = ['Authorization: OAuth '.$this->token];
            //"Transfer-Encoding: chunked"];

        if ($encode=='gzip')
        {
            $header[]="Accept-Encoding: gzip";
        }
        if ($encode=='deflate')
        {
            $header[]="Accept-Encoding: deflate";
        }
        $o=[
            "http" =>
                [
                    "protocol_version"  => "1.1",
                    "method"  => "GET",
                    "timeout" => $this->timeout,
                    "header"  => $header
                ]
        ];

        $stream = stream_context_create($o);
        return fopen($url, "r",false , $stream);
    }

    /**
     * @param \Curler\Request $r
     * @return \Curler\Response
     */
    public function executeRequest(\Curler\Request $r)
    {

        $this->roll->addQueLoop($r);
        $this->roll->execLoopWait();
        return $r->response();
    }


    public function postJson($url,$params=[],$url_templates=[])
    {
        return
            $this->executeRequest(
                $this->makecurlRequest($url,$params,$url_templates,true)
            )->json()
        ;
    }

    /**
     * @param $url
     * @return resource
     */
    public function downloadToStream($url,$encode)
    {
        return $this->makefopenRequest($url,$encode);
    }

    public function downloadToFile($url,$file,$isGz=true)
    {
        $request=$this->makecurlRequest($url);

        if ($isGz)
        {
            $request->httpCompression(true);
        }

        $fout = fopen($file, 'w');


        $request->setNoProgress(false);
        $request->setResultFileHandle($fout, $isGz)->setCallbackFunction(function (\Curler\Request $request) {
            fclose($request->getResultFileHandle());
        });
        $this->executeRequest($request);

        echo ">>>> size ".$request->response()->size_download()."\tspeed:\t".$request->response()->speed_download()." <<<<\n";
    }


    public function getJson($url,$params=[],$url_templates=[])
    {
        return
            $this->executeRequest(
                $this->makecurlRequest($url,$params,$url_templates)
            )->json()
        ;
    }


}
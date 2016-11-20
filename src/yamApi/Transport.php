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


    public function __construct($counter_id,$token)
    {
        $this->roll=new \Curler\CurlerRolling();

        $this->token=$token;
        $this->counter_id=$counter_id;
    }


    /**
     * @param $url
     * @param $url_vars
     * @param $params
     * @param $is_post
     * @return \Curler\Request
     */
    public function makecurlRequest($url,$getParams=[],$url_templates=[],$is_post=false)
    {
        $url_vars=$url_templates;
        $url_vars['counterId']=$this->counter_id;

        foreach ($url_vars as $key=>$val)
        {
            $url=str_ireplace('{'.$key.'}',urlencode($val),$url);
        }


        $request=new \Curler\Request();

        $request->header('Authorization','OAuth '.$this->token);
        $request->header('Content-Type','application/x-yametrika+json');

//        $request->verbose(true);

        if (is_array($getParams))
        {
            $url=$url.'?'.http_build_query($getParams);

        }
        $request->url($this->host.'/'.ltrim($url,'/'));
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
    public function downloadToFile($url,$file,$isGz=false)
    {
        $request=$this->makecurlRequest($url);
        $request->httpCompression(true);

        $fout = fopen($file, 'w');
        if ($isGz) {
            fwrite($fout, "\x1f\x8b\x08\x00\x00\x00\x00\x00");
        }
        $request->setResultFileHandle($fout, $isGz)->setCallbackFunction(function (Request $request) {
            fclose($request->getResultFileHandle());
        });


        $this->executeRequest($request);
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
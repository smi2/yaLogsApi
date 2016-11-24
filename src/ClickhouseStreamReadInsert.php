<?php
class ClickhouseStreamReadInsert
{
    /**
     * @var \Curler\Request
     */
    private $insert;
    /**
     * @var \Curler\Request
     */
    private $read;

    public function __construct(\Curler\Request $clickhouse,\Curler\Request $read)
    {
        $this->insert=$clickhouse;
        $this->read=$read;

    }
    private function roll()
    {
        $roll=new \Curler\CurlerRolling();
//        $roll->addQueLoop($this->read);
        $roll->addQueLoop($this->insert);
        $roll->execLoopWait();

        echo "V:".$roll->countActive()."\n\n";
    }
    public function makeHappy()
    {
        echo "makeHappy\n";

        $this->read->verbose(true);
        $this->insert->verbose(true);

        $this->read->httpCompression(false);
        $this->insert->httpCompression(false);


        @unlink('/tmp/w_stream');

        $url=$this->read->getUrl();

        $h=[];

        $o=[
            "http" =>
            [
                "method"  => "GET",
                "timeout" => 1000,
                "header"  => $this->read->getHeaders()
            ]
        ];

        $stream = stream_context_create($o);

        $w_stream = fopen($url, "r",false , $stream);


        $this->insert->header('Transfer-Encoding','chunked');
        $this->insert->setReadFunction(function ($ch, $fd, $length) use ($w_stream) {
            $d=fread($w_stream, $length);
            return  ($d?$d:"");
        });
        $this->insert->setCallbackFunction(function (\Curler\Request $request)  use ($w_stream)  {
            fclose($w_stream);
        });

        $this->roll();
        $state=new \ClickHouseDB\Statement($this->insert);
        $state->error();

        return $state;

    }
}
/*
 $mime_type = $metadata['mime_type'];
// now open a data stream of that mime type
// for example, for a jpeg file this would be "data://image/jpeg"
$stream = fopen('data://' .mime_type . ',','w+'); // w+ allows both writing and reading
$dropbox->getFile($file,$stream); // loads the file into the data stream
rewind($stream)
ftp_fput($ftp_connection,$remote_filename,$stream,FTP_BINARY); // send the stream to the ftp server
// now close everything
fclose($stream);
ftp_close($ftp_connection);
*/
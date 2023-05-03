<?php


namespace tests;



use Owlet\CmbcSign\Cmbc;
use PHPUnit\Framework\TestCase;

class ClientTest extends  TestCase
{

        public function testRequest(){
            $cmbc = \Mockery::mock(Cmbc::class)->makePartial();

            $cmbc->request();
        }
}

<?php
use function Bluefrg\retry;

class retryTest extends PHPUnit_Framework_TestCase
{
    public function testOne()
    {
        $i = 0;
        retry(100, function () use (&$i) {
            $i++;
        });

        $this->assertEquals(1, $i, 'Retry should have only ran once');
    }

    public function testMultiple()
    {
        $iFail = 3; // We expect to fail 3 times

        $i = 0;
        try {
            retry($iFail, function () use (&$i) {
                $i++;

                throw new Exception();
            });
        }
        catch(\Exception $oEx) {}

        $this->assertEquals($iFail, $i, 'Retry did not run callable 3 times');
    }

    public function testReturn()
    {
        $bResult = retry(333, function () {
            return true;
        });

        $this->assertTrue($bResult, 'Retry callable did not return true');
    }

    public function testInvalidRetries()
    {
        $this->expectException('PHPUnit_Framework_Error');

        retry(-555, function () {});
    }

    public function testFinally()
    {
        $bFinallyRan = false;

        retry(1, function ()  {}, function () use (&$bFinallyRan) {
            $bFinallyRan = true;
        });

        $this->assertTrue($bFinallyRan, 'Finally callback did not run');
    }

    public function testFinallyAfterFailure()
    {
        $bFinallyRan = false;

        try {
            retry(3, function ()  {
                throw new Exception();
            }, function () use (&$bFinallyRan) {
                $bFinallyRan = true;
            });
        }
        catch(\Exception $oEx) {}

        $this->assertTrue($bFinallyRan, 'Finally callback did not run after failure');
    }

    public function testNonBaseExceptionThrow()
    {
        $oEx = null;

        try {
            retry(1, function ()  {
                throw new RuntimeException();
            });
        }
        catch(\Exception $oEx) {}

        $this->assertInstanceOf('RuntimeException', $oEx, 'Expected to catch a RuntimeException exception');
    }
}

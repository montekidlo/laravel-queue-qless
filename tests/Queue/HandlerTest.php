<?php

namespace LaravelQless\Tests\Queue;

use LaravelQless\Contracts\JobHandler;
use LaravelQless\Queue\QlessConnectionHandler;
use LaravelQless\Queue\QlessQueue;
use Orchestra\Testbench\TestCase;
use Qless\Client;

class HandlerTest extends TestCase
{
    public function testCustomHandler()
    {
        $queue = $this->getQueue();

        $queue->push(\stdClass::class, ['firstKey' => 'firstValue'], 'handler_test');

        $job = $queue->pop('handler_test');

        $job->fire();

        $this->assertEquals($job->getData()['classHandler'], Job::class);
    }

    protected function getQueue()
    {
        $queue = new QlessQueue(
            new QlessConnectionHandler(
                [new Client([
                    'host' => REDIS_HOST,
                    'port' => REDIS_PORT,
                ])]
            ),
            [
                'queue' => 'test_qless_queue',
                'connection' => 'qless',
            ]
        );

        $queue->setContainer($this->app);

        return $queue;
    }

    protected function getApplicationProviders($app)
    {
        $app->bind(JobHandler::class, CustomHandler::class);

        return $app['config']['app.providers'];
    }
}

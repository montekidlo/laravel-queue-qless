<?php

namespace LaravelQless\Queue;

use Illuminate\Queue\Connectors\ConnectorInterface;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Config;
use Qless\Client;

/**
 * Class QlessConnector
 * @package LaravelQless\Queue
 */
class QlessConnector implements ConnectorInterface
{
    /**
    * Establish a queue connection.
    *
    * @param array $config
    *
    * @return QlessQueue
    */
    public function connect(array $config): QlessQueue
    {
         $redisConnection = Arr::get($config, 'redis_connection', 'qless');

         $redisConfig = Config::get('database.redis.' . $redisConnection, []);

         return new QlessQueue(
             new Client($redisConfig),
             $config
         );
    }
}

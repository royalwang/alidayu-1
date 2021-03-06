<?php
namespace Larastarscn\AliDaYu;

use Larastarscn\AliDaYu\Contracts\Factory;
use Larastarscn\AliDaYu\Providers\SmsProvider;
use Larastarscn\AliDaYu\Providers\TTSProvider;
use Larastarscn\AliDaYu\Providers\FlowProvider;
use Larastarscn\AliDaYu\Providers\VoiceProvider;
use Larastarscn\AliDaYu\Exceptions\UnknowDriverException;

class AliDaYuManager implements Factory
{
    /**
     * The config of the service.
     *
     * @var array
     */
    protected $config;

    /**
     * The HTTP client instance.
     *
     * @var \GuzzleHttp\Client
     */
    protected $httpClient;

    /**
     * The instances of the driviers.
     *
     * @var array
     *
    */
    protected $instances;

    /**
     * Create a new manager instance.
     *
     * @param  array  $config
     * @param  \GuzzleHttp\Client  $httpClient
     * @return void
     */
    public function __construct($config, $httpClient)
    {
        $this->config = $config;
        $this->httpClient = $httpClient;
    }

    /**
     * Creata a new provider implementation.
     *
     * @param  string  $driver
     * @return \Larastarscn\AliDaYu\Providers\AbstractProvider
     */
    public function driver($driver)
    {
        if ($this->instance[$driver]) {
            return $this->instance[$driver];
        }

        switch ($driver) {
            case 'sms':
                $this->instance[$driver] =  new SmsProvider($this->config, $this->httpClient);
                break;
            case 'tts':
                $this->instance[$driver] = new TTSProvider($this->config, $this->httpClient);
                break;
            case 'voice':
                $this->instance[$driver] = new VoiceProvider($this->config, $this->httpClient);
                break;
            case 'flow':
                $this->instance[$driver] = new FlowProvider($this->config, $this->httpClient);
                break;
            default:
                throw new UnknowDriverException("The name of driver is not found.");
                break;
        }

        return $this->instance[$driver];
    }
}

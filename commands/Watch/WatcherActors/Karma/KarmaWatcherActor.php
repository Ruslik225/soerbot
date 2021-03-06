<?php

namespace SoerBot\Commands\Watch\WatcherActors\Karma;

use CharlotteDunois\Yasmin\Models\Message;
use SoerBot\Watcher\Interfaces\WatcherActorInterface;
use SoerBot\Commands\Watch\WatcherActors\Karma\Implementations\UserModel;

class KarmaWatcherActor implements WatcherActorInterface
{
    private $user;

    public function __construct(\CharlotteDunois\Livia\LiviaClient $client)
    {
        $this->client = $client;
        $this->user = new UserModel();
    }

    /**
     * Проверяет соответствует ли сообщение требованиям Watcher-а.
     *
     * @param $message
     * @return boolean;
     */
    public function isPassRequirements(Message $message)
    {
        if (!$message->author->bot) {
            return true;
        }

        return false;
    }

    /**
     * Выполняет действие, заложенное в Wathcher.
     *
     * @param $message
     * @return void
     */
    public function run(Message $message)
    {
        try {
            $this->user->incrementUserKarma($message->author->username);
        } catch (InvalidUserNameException $error) {
            $this->client->emit('debug', $error->getMessage());
        }
    }
}

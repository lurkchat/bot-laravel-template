<?php

namespace App\Http\Controllers;

use App\Process;
use App\ProcessData;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Lurkchat\Laravel\Facades\LurkchatBot;

class BotController extends Controller
{
    /**
     * Request type (message|file etc.)
     *
     * @var string $type
     */
    public $type;

    /**
     * Text message
     *
     * @var array $message
     */
    public $message;

    /**
     * All available commands
     *
     * @var array $availavleCommands
     */
    public $availableCommands = [
        'start' => 'Start',
    ];

    /**
     * @var array $commands
     */
    public $commands;

    /**
     * Current process for message sender
     *
     * @var Process $process
     */
    public $process;

    /**
     * Bot instance
     *
     * @var
     */
    public $bot;

    /**
     * Determine if message comes from group
     *
     * @var
     */
    public $from_group;


    public function __construct()
    {
        $this->commands = array_keys($this->availableCommands);
        $this->bot = LurkchatBot::getMe()->result;
    }

    /**
     * Handle incoming request each time bot receives message
     *
     * @param Request $request
     */
    public function handleWebhook(Request $request)
    {
        $this->type = $request->type;
        $this->message = $request->data;
        $this->from_group = $this->message['from_group'];

        $this->process = $this->getProcess();

        $command = $this->parseCommand();
        if ($command){
            $this->$command();
        }else{
            $this->processBotMessage();
        }
    }

    public function getProcess()
    {
        $process = Process::where('conversation_id', $this->message['conversation_id'])->first();
        if (!$process){
            $process = new Process();
            $process->conversation_id = $this->message['conversation_id'];
            $process->save();
        }

        return $process;
    }

    /**
     * Parse command from received message
     *
     * @param array $fromCommandsArray
     * @return string|null
     */
    public function parseCommand($fromCommandsArray = [])
    {
        if (!$fromCommandsArray){
            $fromCommandsArray = $this->commands;
        }
        $command = Str::replaceFirst('/', '', $this->message['message']);
        if (in_array($command, $fromCommandsArray)){
            return $command;
        }

        return null;
    }

    /**
     * Process the message sent to the bot if it's not a command
     */
    private function processBotMessage()
    {
        if (in_array($this->process->command, $this->commands)){
            $command = $this->process->command;
            $this->$command();
        }
    }

    /**
     * Clear existing process data
     */
    public function clearProcessData()
    {
        ProcessData::where('process_id', $this->process->id)->delete();
    }

    /**
     * Set status for current process
     *
     * @param $status
     * @param null $command
     */
    public function updateProcessStatus($status, $command = null)
    {
        $this->process->status = $status;
        if ($command){
            $this->process->command = $command;
        }
        $this->process->save();

        if ($status == 'done'){
            $this->clearProcessData();
        }
    }




    public function start()
    {
        $this->updateProcessStatus('', 'start');
        $this->clearProcessData();

        $msg = 'Welcome! How can I help you? Choose a command' .self::END_STR;
        $keyboard = [];
        foreach ($this->availableCommands as $command => $description) {
            if ($this->from_group){
                $command .= '@' . $this->bot->username;
            }
            $keyboard[] = [
                'text' => $description,
                'action' => '/'. $command
            ];

            $msg .= $description .': /'. $command .self::END_STR;
        }
        $keyboard = array_chunk($keyboard, 2);
        $message = [
            'message' => $msg,
            'keyboard' => $keyboard,
            'conversation_id' => $this->message['conversation_id']
        ];
        LurkchatBot::sendMessage($message);
    }

}

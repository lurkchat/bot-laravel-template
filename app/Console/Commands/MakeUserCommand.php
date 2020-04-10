<?php

namespace App\Console\Commands;

use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class MakeUserCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create new user in database';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $data = [
            'login' => '',
            'password' => '',
            'name' => '',
        ];
        do{
            $question = 'Enter login';
            $data['login'] = $this->ask($question);
        }
        while (!$data['login']);

        do{
            $question = 'Enter name';
            $data['name'] = $this->ask($question);
        }
        while (!$data['name']);

        do{
            $question = 'Enter password (characters are hidden)';
            $data['password'] = $this->secret($question);
        }
        while (!$data['password']);

        $confirmationTries = 0;
        do{
            $question = 'Repeat password (characters are hidden)';
            if ($confirmationTries){
                $this->error('Incorrect password confirmation, try again');
            }
            $confirmation = $this->secret($question);
            $confirmationTries++;
        }
        while ($confirmation != $data['password']);

        $rules = [
            'login' => 'required|string|min:6|max:255|unique:users',
            'name' => 'required|string|min:3|max:255|unique:users',
            'password' => 'required|min:6|max:255',
        ];
        $validator = Validator::make($data, $rules);
        if ($validator->fails()){
            foreach($validator->errors()->all() as $error){
                $this->error($error);
            }
            return false;
        }

        $user = new User();
        $user->fill($data);
        $user->password = Hash::make($data['password']);
        $user->save();

        $this->alert('User created successfully');
    }
}

<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use \App\User;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        \App\Console\Commands\Inspire::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('inspire')
                 ->hourly();
                 
    /**
     * Get all the projects, which were update this week. Once you have all those projects
     * iterate through them and get the values we want: States, variables and the time
     * a user have stayed in their pages. Once we get all of that data we create a
     * new row in our database with the updated values every Saturday at 23:00
     * so Leaders can access project_stats and get the information they want
     */
        $schedule->call(function (){
            $projects = User::leaders()->get();
            $stats = array();
            
            foreach($projects as $project){
                $group = $project->groups[0];
                
                $clients = $group->clients()
                                 ->updatedThisWeek()
                                 ->get();
                
                $states = array();
                foreach ($clients as $client){
                    if(isset($states[$client->status])){
                        $states[$client->status]++;
                    }
                    else{
                        $states[$client->status] = 1;
                    }
                }
                
                foreach ($states as $value) $value = $value * 100 / count($clients);
                
                foreach($states as $key=>$value){
                    $project->stats()->create([
                        'name' => $key,
                        'value' => $value,
                        'type' => 'state'
                    ]);
                }
                
                $variables = $group->variables;
                
                foreach($variables as $variable){
                    $formerValue = $project->stats()
                                           ->where('name', '=', $variable->name)
                                           ->where('type', '=', 'variable')
                                           ->orderBy('created_at', 'DESC')
                                           ->first();
                                           
                    $formerValue = (isset($formerValue)) ? $formerValue->value : 0;
                    
                    $vars[$variable->name] = $variable->value - $formerValue;
                    
                    $project->stats()
                            ->create([
                                'name' => $variable->name,
                                'variation' => $variable->value - $formerValue,
                                'value' => $variable->value / count($group->clients),
                                'type' => 'variable'
                            ]);
                }
                
                $pages = $group->pages;
                $times = array();
                foreach($pages as $page){
                    $project->stats()
                            ->create([
                        'name' => $page->url,
                        'value' => $page->averageTime,
                        'type' => 'time'
                    ]);
                }
            }
        })->weekly()->saturdays()->at('23:00');
    }
}

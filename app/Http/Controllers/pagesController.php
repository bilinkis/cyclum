<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests,
    App\Http\Controllers\Controller,
    App\Group,
    Validator,
    App\Variable,
    App\User,
    App\Client,
    App\Task,
    App\Page,
    App\Stat,
    App\Subteam,
    \Auth,
    App\Http\Requests\PassConfirmationRequest,
    App\Http\Requests\EditUserRequest,
    \Crypt,
    \Session,
    \Hash,
    \Input,
    Carbon\Carbon;

class pagesController extends Controller
{
    public function __construct() {
        view()->share('userType', Session::get('userType'));
        view()->share('user', Auth::user());
    }
    
    public function index(){
        $userType = Session::get('userType');
        if(!isset($userType)){
            $userType = 'startup';
        }
        return redirect('/' . $userType);
    }
    
    public function indexUsers(){
        Session::put('userType', 'user');
        view()->share('userType', 'user');
        return view('pages/landing');
    }
    
    public function indexStartups(){
        Session::put('userType', 'startup');
        view()->share('userType', 'startup');
        return view('pages/landing');
    }
    
    public function indexConsultants(){
        Session::put('userType', 'consultant');
        view()->share('userType', 'consultant');
        return view('pages/landing');
    }
    
    public function home(){
        $user = Auth::user();
        $leader = ($user->isALeader()) ? $user : $user->leader;
        $workers = $leader->teams[0]->workers;
        $tasks = $leader->teams[0]->tasks;
        $teams = ($user->isALeader()) ? $user->teams : $user->leader->teams;
        /* Esto crashea Xq lo comentan?a sifak q tira?
        foreach ($tasks as $key => $task) {
            $tasks[$key].encrypted_id = Crypt::encrypt($task[$id]);
        }
        */
        return view('pages/home', compact('workers', 'tasks', 'teams'));
    }
    
    public function setCookie(Request $request){
        $group = Group::find($request->group_id);
        $user_id = $request->user_id;
        
        if($group->clients()->find($user_id)){
            return $user_id;
        }
        else{
            $user = $group->clients()->create(['status' => $request->default_status]);
            return $user->id;
        }
    }
    
    public function getPage(Group $group, $url){
        if($group->pages()->whereUrl($url)->count() == 0){
            $group->pages()->create(['url' => $url, 'views' => 0, 'averageTime' => 0]);
        }
        
        return $group->pages()->whereUrl($url)->first();
    }
    
    public function setVisitTime(Request $request){
        $group = Group::find($request->group_id);

        $page = $this->getPage($group, $request->url);
        
        $page->averageTime = ($page->averageTime + $request->seconds) / ($page->views + 1);
        $page->views = $page->views + 1;
        $page->save();
        return('200');
    }
    
    public function addTask(Request $request){
        
        $validator = Validator::make($request->all(), [
            'text' => 'required|max:64',
        ]);
        
        if($validator->fails()){
            return redirect()->back()->withErrors($validator)
                   ->withInputs($request->all())
                   ->with([
                        'show_modal' => 'add-task-modal',
                        'type' => 'add_task' 
                   ]);
        }
        
        else{
            if(Auth::user()->tasks()->whereStatus('todo')->count() >= 3){
                return redirect('/home')->with([
                    'show_modal' => 'add-task-modal',
                    'type' => 'add_task'
                ])
                ->withInputs($request->all())
                ->withErrors([
                    'Ya hay 3 tareas para hacer!',
                ]);
            }
            else{
                $task = Auth::user()->tasks()->create(['status' => 'todo', 'text' => $request->input('text'), 'subteam_id' => $request->input('subteam_id')]);
                $task->groups()->create([]);
                $task->groups()->create([]);
                return redirect('/home');
            }
        }
    }
    
    public function deleteTask(Request $request) {
        // Todo fijarse si el usuario tiene un task con ese id.
        Auth::user()->tasks()->find($request->input('task_id'))->delete();
        return '200';
    }
    
    public function editTask(Request $request) {
        $taskToEdit = Auth::user()->tasks()->find($request->input('task_id'));
        $taskToEdit->text = $request->input('edited_text');
        $taskToEdit->save();
        return redirect('/home');
    }
    
    public function changeTask(Request $request) {
        $user = Auth::user();
        $taskToChange = ($user->isALeader()) ? $user->tasks()->find($request->task_id) : $user->leader->tasks()->find($request->task_id);
        $taskToChange->status = $request->status;
        $taskToChange->save();
        return '200';
    }
    
    public function addWorker(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:64',
            'email' => 'required|unique:users,email|max:64',
        ]);
        
        if($validator->fails()){
            return redirect()->back()->withErrors($validator)
                   ->withInputs($request->all())
                   ->with([
                        'show_modal' => 'add-worker-modal',
                        'type' => 'add_worker'
                   ]);
        }
        else{
            $worker = Auth::user()->workers()->create(['name' => $request->input('name'), 'email' => $request->input('email'), 'mail_validated' => false, 'teamName' => Auth::user()->teamName, 'rank' => 'worker']);
            if ($request->input('subteam_id') > 0) {
                $worker->subteam_id = $request->input('subteam_id');
                $worker->save();
            }
            $message = 
                'Hola ' . $request->input('name') . '!
                <br>
                Para que puedas comenzar a usar Cyclum debes activar tu cuenta.
                Para activar tu cuenta ingresá <a href="' . url() . '/validateworker/' . Crypt::encrypt($worker->id) . '">acá</a>
                ';
            //dd($message);
            $headers  = 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
            $headers .= 'To: User <' . $request->input('email') . '>' . "\r\n";
            $headers .= 'From: Cyclum <noreply@cyclum.io>' . "\r\n";
            
            /*
            dd($request->input('email'),
                'Activá tu cuenta en Cyclum!',
                $message,
                $headers
            );
            */
            
            mail
            (
                $request->input('email'),
                'Activá tu cuenta en Cyclum!',
                $message,
                $headers
            );
            return redirect('/home')->with([
                'selected_tab' => 'team',
            ]);
        }
    }
    
    public function deleteWorker($encryptedId) {
        $id = Crypt::decrypt($encryptedId);
        if (Auth::user()->isALeader()) User::destroy($id);
        return redirect('/home');
    }
    
    public function stats($cryptedId){
        $id = Crypt::decrypt($cryptedId);
        $user = Auth::user();
        $backButton = true;
        $task = ($user->isALeader()) ? $user->tasks()->find($id) : $user->leader->tasks()->find($id);
        $groups = $task->groups;
        
        // ESTADOS
        $clientsStatusData = [];
        
        foreach ($groups as $group){
            $possibleValuesRepeated = $group->clients()->lists('status')->toArray();
            $possibleValues = [];
            
            for ($i = 0; $i < count($possibleValuesRepeated); $i++){
                if (isset($possibleValues[$possibleValuesRepeated[$i]])){
                    $possibleValues[$possibleValuesRepeated[$i]]++;
                } else {
                    $possibleValues[$possibleValuesRepeated[$i]] = 1;
                }
            }
            
            array_push($clientsStatusData, $possibleValues);
        }
        
        // VARIABLES
        $variables = Variable::where('group_id', $groups->first()->id)->orWhere('group_id', $groups->last()->id)->get()->toArray();
        
        foreach ($variables as $variable) $variable->value /= count(Group::find($request->group_id)->clients);
        
        $varsData = [];
        
        for ($i = 0; $i < count($variables); $i++){
            if (!isset($varsData[$variables[$i]['name']])){
                $varsData[$variables[$i]['name']] = [];
            }
            array_push($varsData[$variables[$i]['name']], $variables[$i]['value']);
        }
        
        // TIEMPOS
        $pages = Page::where('group_id', $groups->first()->id)->orWhere('group_id', $groups->last()->id)->get()->toArray();
        $pagesData = [];
        
        for ($i = 0; $i < count($pages); $i++){
            if (!isset($pagesData[$pages[$i]['url']])){
                $pagesData[$pages[$i]['url']] = [];
            }
            array_push($pagesData[$pages[$i]['url']], $pages[$i]['averageTime']);
        }
        
        $clientsStatusData = json_encode($clientsStatusData);
        $varsData = json_encode($varsData);
        $pagesData = json_encode($pagesData);
        // dd($pagesData);
        
        return view('pages/stats', compact('backButton', 'clientsStatusData', 'task', 'varsData', 'pagesData'));
    }
    
    public function projectStats(){
        $user = Auth::user();
        $backButton = true;
        
        if ($user->isALeader()){
            $states = $user->stats()->states()->get();
            $variables = $user->stats()->variables()->get();
            $times = $user->stats()->times()->get();
        }
        else if ($user->isAWorker) {
            $states = $user->leader->stats()->states()->get();
            $variables = $user->leader->stats()->variables()->get();
            $times = $user->leader->stats()->times()->get();
        }
        
        $stateNames = array();
        $stateCount = 0;
        foreach($states as $state){
            if(!isset($stateNames[$state->name])){
                $stateNames[$state->name] = $stateCount;
                $stateCount++;
            }
        }
        
        $stateCount++;
        
        $jsonStates = array(); 
        $jsonStates[] = array_merge((array) 'Fecha', array_keys($stateNames), (array) "{'role':'annotation'}");
        
        foreach($states as $state){
            $date = $state->created_at->toDateTimeString();
            $dates = array_column($jsonStates, 'fecha');
            $position = array_search($date, $dates);
            
            if($position !== false){
                $jsonStates[$position + 1][$stateNames[$state->name]] = $state->value;
            }
            
            else{
                $row = array();
                $row['fecha'] = $date;
                
                for($i = 0; $i < $stateNames[$state->name]; $i++){
                    $row[] = null;
                }
                
                $row[] = $state->value;
                
                for($i = $stateNames[$state->name] + 1; $i < $stateCount; $i++){
                    $row[] = null;
                }
                
                $jsonStates[] = $row;
            }
        }
        
        $variableNames = array();
        $variableCount = 0;
        foreach($variables as $variable){
            if(!isset($variableNames[$variable->name])){
                $variableNames[$variable->name] = $variableCount;
                $variableCount++;
            }
        }
        $variableCount++;
        $jsonVariables = array();
        $jsonVariables[] = array_merge((array) 'Fecha', array_keys($variableNames), (array) "{'role':'annotation'}");
        
        foreach($variables as $variable){
            $date = $variable->created_at->toDateTimeString();
            $dates = array_column($jsonVariables, 'fecha');
            $position = array_search($date, $dates);
            if($position !== false){
                $jsonVariables[$position + 1][$variableNames[$variable->name]] = $variable->variation;
            }
            else{
                $row = array();
                $row['fecha'] = $date;
                
                for($i = 0; $i < $variableNames[$variable->name]; $i++){
                    $row[] = null;
                }
                
                $row[] = $variable->variation;
                
                for($i = $variableNames[$variable->name] + 1; $i < $variableCount; $i++){
                    $row[] = null;
                }
                
                $jsonVariables[] = $row;
            }
        }
        
        $timeNames = array();
        $timeCount = 0;
        foreach($times as $time){
            if(!isset($timeNames[$time->name])){
                $timeNames[$time->name] = $timeCount;
                $timeCount++;
            }
        }
        $timeCount++;
        $jsonTimes = array();
        $jsonTimes[] = array_merge((array) 'Fecha', array_keys($timeNames), (array) "{'role':'annotation'}");
        foreach($times as $time){
            $date = $time->created_at->toDateTimeString();
            $dates = array_column($jsonTimes, 'fecha');
            $position = array_search($date, $dates);
            if($position !== false){
                $jsonTimes[$position + 1][$timeNames[$time->name]] = $time->value;
            }
            else{
                $row = array();
                $row['fecha'] = $date;
                
                for($i = 0; $i < $timeNames[$time->name]; $i++){
                    $row[] = null;
                }
                
                $row[] = $time->value;
                
                for($i = $timeNames[$time->name] + 1; $i < $timeCount; $i++){
                    $row[] = null;
                }
                
                $jsonTimes[] = $row;
            }
        }
        
        $changes = $user->tasks()->accepted()->get();
        
        foreach ($changes as $change) {
            $tempState = [$change->validated_at];
            $tempVariable = [$change->validated_at];
            $tempTime = [$change->validated_at];
            foreach ($stateNames as $state) array_push($tempState, null);
            foreach ($variableNames as $variable) array_push($tempVariable, null);
            foreach ($timeNames as $time) array_push($tempTime, null);
            array_push($tempState, $change->text);
            array_push($jsonStates, $tempState);
            array_push($tempVariable, $change->text);
            array_push($jsonVariables, $tempVariable);
            array_push($tempTime, $change->text);
            array_push($jsonTimes, $tempTime);
        }
        
        $jsonStates = json_encode($this->recursive_array_values($jsonStates));
        $jsonVariables = json_encode($this->recursive_array_values($jsonVariables));
        $jsonTimes = json_encode($this->recursive_array_values($jsonTimes));
        return view('pages/project_stats', compact('backButton', 'stats', 'jsonStates', 'jsonVariables', 'jsonTimes'));
    }
    
    public function recursive_array_values($array) {
        foreach ($array as $k => $val)
        {
            if(is_array($val))
            {
                $array[$k] = $this->recursive_array_values($val);
            }
        }
        
        return array_values($array);
    }
    
    public function poop(){
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
                dd($variables);
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
    }
    
    public function saveStats(Request $request) {
        if ($request->type == 'variable') {
            $variable = Variable::where('name', $request->name)->where('group_id', $request->group_id)
                                                               ->first();
            if ($variable) {
                $variable->value += $request->value;
            } else {
                $variable = new Variable();
                $variable->value = $request->value;
            }
            $variable->name = $request->name;
            $variable->group_id = $request->group_id;
            $variable->save();
        } else if ($request->type == 'state') {
            $client = Client::find($request->user_id);
            if ($client) {
                $client->status = $request->name;
                $client->save();
            } else {
                return '403';
            }
        }
        return '200';
    }
    
    public function validateChange($encryptedId, $desition){
        $id = Crypt::decrypt($encryptedId);
        $task = Task::find($id);
        $task->validated = $desition;
        $task->status = 'finished';
        $task->save();
        return redirect('/home');
    }
    
    public function account(){
        return view('pages/account', ['backButton' => true]);
    }
    
    public function passConfirmation(PassConfirmationRequest $request) {
        $worker = User::find(Crypt::decrypt($request->id_hash));
        $worker->password = bcrypt($request->password);
        $worker->mail_validated = true;
        $worker->save();
        Auth::login($worker);
        return redirect('/home');
    }
    
    public function getTaskWorkers(Request $request) {
        $task = Task::findOrFail($request->id_task);
        
        if (count($task->workers) == 0) {
            return 'no_workers';
        } else {
            return json_encode($task->workers()->lists('id'));
        }
        
    }
    
    public function addWorkerTask(Request $request) {
        $task = Auth::user()->tasks()->find($request->input('task_id'));
        $task->workers()->sync($request->input('workers_list'));
        return redirect('/home');
    }
    
    public function validateWorker($id) {
        $worker = User::find(Crypt::decrypt($id));
        return view('pages/validate_worker', compact('id', 'worker'));
    }
    
    public function validateLeader($id) {
        $user = User::find(Crypt::decrypt($id));
        $user->mail_validated = true;
        $user->save();
        Auth::login($user);
        return redirect('/home');
    }
    
    public function editUser(EditUserRequest $request){
        $user = Auth::user();
        if(Hash::check($request->password, $user->password)){
            $user->update(Input::except('password'));
            return redirect('/home');
        }
        
        else{
            return redirect('/account')
                ->withInputs($request->all())
                ->withErrors([
                    'La contraseña es incorrecta',
            ]);
        }
    }
    
    public function upgrade($encryptedId) {
        $id = Crypt::decrypt($encryptedId);
        $user = User::findOrFail($id);
        $user->rank = 'leader';
        $user->save();
        return redirect('/home');
    }
    
    public function downgrade($encryptedId) {
        $id = Crypt::decrypt($encryptedId);
        $user = User::findOrFail($id);
        $user->rank = 'worker';
        $user->save();
        return redirect('/home');
    }
    
    public function addTeam(Request $request) {
        $user = Auth::user();
        $subteam = Subteam::create($request->all());
        $subteam->user_id = $user->isALeader() ? $user->id : $user->leader->id;
        $subteam->save();
        return redirect('/home');
    }
    
    public function changeTeamTasks(Request $request) {
        $user = Auth::user();
        
        try {
            $team = Subteam::findOrFail($request->team_id);
            $leaderId = $user->isALeader() ? $user->id : $user->leader->id;
            $tasks = Task::where('user_id', '=', $user->id)->where('subteam_id', '=', $team->id)->get();
            return $tasks;
        } catch(Exception $e) {
            return '404';
        }
    }
    
    public function changeTeams(Request $request) {
        $user = Auth::user();
        
        try {
            $team = Subteam::findOrFail($request->team_id);
            return [$team->workers, $user->rank] ;
        } catch(Exception $e) {
            return '404';
        }
    }
    
    /*
     | Delete a task -> /deletetask?task_id=taskId
     | Change task place -> /changetask?status=containerId&task_id=taskId
     | Change task text -> /edittask?task_id=taskId&edited_text=texto
     | Save changes -> /savestats?type=state&name=name&value=value&user_id=userId&group_id=group_id
     | Set cookie -> /setcookie?user_id=userId&group_id=group_id
     | Set time -> /settime?group_id=group_id&url=URL&seconds=seconds
     | Get task workers -> /gettaskworkers?task_id=task_id
    */
}
<?php

namespace App\Http\Controllers\Admin\Users;

use App\Exports\ExportUsers;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Inertia\Inertia;
use Module\User\Models\User;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Models\ElamaModel;
use Reviews\Events\NewExternalReviewsEvent;
use Reviews\Models\ReviewForm;
use Module\Campaign\Models\Campaign;
use Reviews\Enums\ReviewSource;
use Illuminate\Support\Facades\Storage; 

use Illuminate\Support\Facades\DB;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ElamaUsersListController
{
    public function __invoke(Request $request): Responsable
    {
        
        //print_r($users);
        $hasActiveSubscriptionFilter = $request->query('has_active_subscriptions');

        if ($hasActiveSubscriptionFilter === 'active') {
            $users = $users->whereHas('activeSubscriptions');
        } elseif ($hasActiveSubscriptionFilter === 'inactive') {
            $users = $users->whereDoesntHave('activeSubscriptions');
        }
 
        return Inertia::render('Admin/Users/List', [
            'users' => $users->get(),
        ]);
    }

    public function export(): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        /*
        if (Schema::hasTable('elama_users')) {
            echo '</br>Отслеживание elama_user</br></br>';
            Schema::table('elama_users', function (Blueprint $table) {
                $table->string('name');
            });

        }
       */
        
        /**///print_r($date2);
        //$data = $request->session()->all();

        print_r('jjj');
        $request->session()->regenerate();
           echo "</br>";
        /////////////////////////////////////////////////////////
            $date = Carbon::now();
            
            $date1 = $date->addDays(-1);
            $date2 = Carbon::now();
           
            echo '</br>';
            echo '</br>Отслеживание создания форм за период</br></br>';
            foreach (ReviewForm::whereBetween('created_at', [$date1, $date2])->get() as $review) {
                echo 'name = ' . $review->name . '</br>';
                echo 'created_at = ' . $review->created_at. '</br>';
                echo 'updated_at = ' . $review->updated_at. '</br>';
            }
            echo '</br>';
        /////////////////////////////////////////////////////////////////////
        //dd(ReviewForm::whereBetween('created_at', [$date1, $date2])->get());
        /////////////////////////////////////////////////////////

        echo '</br>';
        echo '</br>Отслеживание создания Компаний за период</br></br>';
        foreach (Campaign::whereBetween('created_at', [$date1, $date2])->get() as $comp) {
            echo 'name = ' . $comp->name . '</br>';
            echo 'created_at = ' . $comp->created_at. '</br>';
            echo 'updated_at = ' . $comp->updated_at. '</br>';
        }
        echo '</br>';
        /////////////////////////////////////////////////////////////////////

        echo '</br>';
        echo '</br>Отслеживание комментариев по источникам</br></br>';
        $users = DB::table('review_reviews')->get();

        //dd($users);
        foreach ($users as $user) {
            echo 'comment = ' . $user->comment. '</br>';
            echo 'created_at = ' . $user->created_at. '</br>';
          }
        /////////////////////////////////////////////////////////////////////

        /////////////////////////////////////////////////////////////////////

        echo '</br>';
        echo '</br>Отслеживание виджетов</br></br>';
        $users = DB::table('sw_stats')->get();

        foreach ($users as $user) {
            echo 'Просмотров = ' . $user->views. '</br>';
            echo 'Кликов = ' . $user->clicks . '</br>';
          }
        /////////////////////////////////////////////////////////////////////
        
        /////////////////////////////////////////////////////////////////////

        echo '</br>';
        echo '</br>Отслеживание пользователей</br></br>';
        
        $users = DB::table('users')->get();

        dd($users);
        foreach ($users as $user) {
            echo 'id = ' . $user->id . '</br>';
            echo 'name = ' . $user->name . '</br>';
        }
        /////////////////////////////////////////////////////////////////////

        /////////////////////////////////////////////////////////////////////

        echo '</br>';
        echo '</br>Отслеживание пользователей</br></br>';
        
        $tables = DB::select('SHOW TABLES');
        
        foreach ($users as $user) {
            //echo 'id = ' . $user->id . '</br>';
            //echo 'name = ' . $user->name . '</br>';
        }
        /////////////////////////////////////////////////////////////////////
        echo '</br>';echo '</br>';echo '</br>';
        //dd($users);
/**/
        //$users = Campaign::all();
        //
        //$users = ElamaModel::all();
        print_r('55555');
        
        $userExport = new ExportUsers($users);
        $spreadSheet = $userExport->create();
        

        $filename = '/list.xls';
        
        $dir = $_SERVER['DOCUMENT_ROOT'] . '/elama_reports';

        //$xls = (new Xlsx($spreadSheet))->save($dir . '/list.xls');
        //Storage::disk('local')->put('hello.xls', 'Contents');
        
        //Сохраняем файл в текущей папке, в которой выполняется скрипт.
        //Чтобы указать другую папку для сохранения. 
        //Прописываем полный путь до папки и указываем имя файла
        

        $writer = new Xlsx($spreadSheet);

        ob_start();
        $writer->save('php://output');
        $content = ob_get_contents();
        ob_end_clean();

        Storage::disk('local')->put("weekReport.xlsx", $content); 
        
        
        return response()->stream(function () use ($spreadSheet): void {
            
        }, 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment;filename="' . $filename . '"',
        ])->send();
    }
}

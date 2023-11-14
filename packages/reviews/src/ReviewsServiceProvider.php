<?php

declare(strict_types=1);

namespace Reviews;

use App\Http\Middleware\Authenticate;
use App\Http\Middleware\HasCampaignAccessMiddleware;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Route;
use Request;
use Reviews\Console\Commands\AuthYandexBusinessCommand;
use Reviews\Console\Commands\FetchExternalReviewsCommand;
use Reviews\Console\Commands\ReDispatchReviewAnswersCommand;
use Reviews\Console\Commands\SetYandexBusinessSessionCommand;
use Reviews\Events\NewExternalReviewEvent;
use Reviews\Http\Middleware\HasReviewFormAccess;
use Reviews\Listeners\NewExternalReviewListener;
use Reviews\Models\AnswerTemplate;
use Reviews\Models\Review;
use Reviews\Models\ReviewForm;
use Reviews\Services\ReviewAnswersService;

final class ReviewsServiceProvider extends RouteServiceProvider
{
    public function boot(): void
    {
        Route::middlewareGroup('reviews-public', [
            // Для публичных страниц
        ]);

        Route::model('reviewForm', ReviewForm::class);
        Route::model('review', Review::class);
//        Route::model('reviewAnswerTemplate', AnswerTemplate::class);
        Route::bind('reviewAnswerTemplate', static fn($value) =>
            Request::route('campaign')
                ?->reviewAnswerTemplates()
                ?->findOrFail($value));

        $this->routes(function (): void {
            // public
            Route::prefix('r')
                ->as('reviews.public.')
                ->middleware('web') // Или лучше отдельный список мидлвейров?
                ->middleware('reviews-public')
                ->namespace($this->namespace)
                ->group(self::routeFile('public'));

            // private
            Route::prefix('c/{campaign}/reviews')
                ->as('reviews.private.')
                ->middleware([
                    'web',
                    Authenticate::class,
                    HasCampaignAccessMiddleware::class,

                    // Если в параметрах роута нет reviewForm, то оно будет его игнорить
                    HasReviewFormAccess::class,
                ])
                ->namespace($this->namespace)
                ->group(self::routeFile('private'));
        });

        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        $this->commands([
            FetchExternalReviewsCommand::class,
            AuthYandexBusinessCommand::class,
            SetYandexBusinessSessionCommand::class,
            ReDispatchReviewAnswersCommand::class,
        ]);

        app()->singleton(ReviewAnswersService::class);

//        Event::listen(NewExternalReviewsEvent::class, NewExternalReviewsListener::class);
        Event::listen(NewExternalReviewEvent::class, NewExternalReviewListener::class);
    }

    private static function routeFile(string $name): string
    {
        return __DIR__ . "/../routes/$name.php";
    }
}

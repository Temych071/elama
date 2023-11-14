<?php

declare(strict_types=1);

namespace Reviews\Actions;

use App\Exceptions\BusinessException;
use Illuminate\Support\Str;
use Reviews\Models\ReviewForm;

final class CopyReviewFormAction
{
    /**
     * @throws BusinessException
     */
    public function execute(ReviewForm $reviewForm): ReviewForm
    {
        $copiedReviewForm = $reviewForm->replicate();
        $copiedReviewForm->name .= ' - копия';
        $copiedReviewForm->slug = Str::before(Str::uuid(), '-');

        $copiedReviewForm->logo_path = null;
        $copiedReviewForm->banner_path = null;
        $copiedReviewForm->thx_banner_path = null;

        $copiedReviewForm->copyMedia('logo', $reviewForm->logo_path);
        $copiedReviewForm->copyMedia('banner', $reviewForm->banner_path);
        $copiedReviewForm->copyMedia('thx_banner', $reviewForm->thx_banner_path);

        $copiedReviewForm->save();

        return $copiedReviewForm;
    }
}

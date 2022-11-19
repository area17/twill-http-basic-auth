<?php

namespace A17\TwillHttpBasicAuth\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use A17\Twill\Http\Controllers\Admin\ModuleController;
use A17\TwillHttpBasicAuth\Models\TwillHttpBasicAuth;
use A17\TwillHttpBasicAuth\Repositories\TwillHttpBasicAuthRepository;

class TwillHttpBasicAuthController extends ModuleController
{
    protected $moduleName = 'TwillHttpBasicAuth';

    protected $titleColumnKey = 'site_key';

    protected $indexOptions = ['edit' => false];

    public function redirectToEdit(TwillHttpBasicAuthRepository $repository): RedirectResponse
    {
        return redirect()->route('admin.TwillHttpBasicAuth.show', ['TwillHttpBasicAuth' => $repository->theOnlyOne()->id]);
    }

    /**
     * @param int|null $parentModuleId
     * @return array|\Illuminate\View\View|RedirectResponse
     */
    public function index($parentModuleId = null)
    {
        return redirect()->route('admin.TwillHttpBasicAuth.redirectToEdit');
    }

    /**
     * @param int $id
     * @param int|null $submoduleId
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function edit($id, $submoduleId = null)
    {
        $repository = new TwillHttpBasicAuthRepository(new TwillHttpBasicAuth());

        return parent::edit($repository->theOnlyOne()->id, $submoduleId);
    }

    protected function formData($request): array
    {
        return [
            'editableTitle' => false,
            'customTitle' => ' ',
        ];
    }

    protected function getViewPrefix(): string|null
    {
        return 'twill-http-basic-auth::admin';
    }
}

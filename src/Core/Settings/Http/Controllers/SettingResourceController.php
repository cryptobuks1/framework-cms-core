<?php

namespace Core\Settings\Http\Controllers;

use App\Http\Controllers\ResourceController as BaseController;
use Core\Settings\Http\Requests\SettingRequest;
use Core\Settings\Interfaces\SettingRepositoryInterface;
use Core\Settings\Models\Setting;

/**
 * Resource controller class for setting.
 */
class SettingResourceController extends BaseController
{
    /**
     * Initialize setting resource controller.
     *
     * @param type SettingRepositoryInterface $setting
     *
     * @return null
     */
    public function __construct(SettingRepositoryInterface $setting)
    {
        parent::__construct();
        $this->repository = $setting;
        $this->repository
            ->pushCriteria(\Core\Repository\Criteria\RequestCriteria::class);
    }

    /**
     * Display a list of setting.
     *
     * @return Response
     */
    public function index(SettingRequest $request)
    {
        return $this->response->setMetaTitle(trans('settings::setting.names'))
            ->view('settings::index')
            ->data(compact('settings'))
            ->output();
    }

    /**
     * Create new setting.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function show($slug)
    {
        return view('settings::partial.' . $slug);
    }

    /**
     * Create new setting.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function store(SettingRequest $request, $type)
    {
        try {
            $attributes = $request->all();

            if (user()->hasRole('superuser')) {

                if (isset($attributes['settings']) && is_array($attributes['settings'])) {
                    foreach ($attributes['settings'] as $key => $value) {
                        $this->repository->setValue($key, $value);
                    }
                }

                if (isset($attributes['env']) && is_array($attributes['env'])) {
                    foreach ($attributes['env'] as $key => $value) {
                        $this->repository->env($key, $value);
                    }
                }

                if (isset($attributes['upload']) && is_array($attributes['upload'])) {
                    foreach ($attributes['upload'] as $key => $value) {
                        $this->repository->upload($request, $key, $value);
                    }
                }
            }

            if (isset($attributes['user']) && is_array($attributes['user'])) {
                foreach ($attributes['user'] as $key => $value) {
                    $this->repository->setForuser($key, $value);
                }
            }

            return $this->response->message(trans('messages.success.updated', ['Module' => trans('settings::setting.name')]))
                ->code(204)
                ->status('success')
                ->url(guard_url("/settings/$type"))
                ->redirect();
        } catch (Exception $e) {
            return $this->response->message($e->getMessage())
                ->code(400)
                ->status('error')
                ->url(guard_url("/settings/$type"))
                ->redirect();
        }

    }

    /**
     * Show setting for editing.
     *
     * @param Request $request
     * @param Model   $setting
     *
     * @return Response
     */
    public function getValue($key, $default = null)
    {
        return $this->repository->getValue($key, $default = null);
    }

    /**
     * Update the setting.
     *
     * @param Request $request
     * @param Model   $setting
     *
     * @return Response
     */
    public function setValue($key, $value)
    {
        return $this->repository->setValue($key, $value);
    }

}

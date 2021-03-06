<?php

namespace Core\Install\Http\Controllers;

use App\Http\Controllers\PublicController;

class WelcomeController extends PublicController
{

    /**
     * Display the installer welcome page.
     *
     * @return \Illuminate\Http\Response
     */
    public function welcome()
    {
        return $this->response->metaTitle('Dashboard')
            ->layout('blank')
            ->view('install::welcome')
            ->output();
    }

}

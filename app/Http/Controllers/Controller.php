<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;

abstract class Controller
{
    //
    private array $breadcrumb = [];

    protected function addBreadcrumb( $name, $url){
        $this->breadcrumb[] = ['name' => $name, 'url' => $url];
    }

    /**
     * @param $component
     * @param array $component_data
     * @return Response
     */
    protected function render($component, array $component_data = []): Response
    {
        array_unshift($this->breadcrumb, ['name' => 'Accueil', 'url' => route('dashboard')]);
        $additionalData = [
            'breadcrumb' => $this->breadcrumb,
        ];
        $mergedData = array_merge($component_data, $additionalData);
        return Inertia::render($component, $mergedData);
    }
}

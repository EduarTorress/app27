<?php

namespace App\View\Components;

use App\Controllers\SerieController;
use Core\View\Component;

class SerieComponent extends Component
{
  public function render()
  {
    $series = SerieController::listarseries();
    return view('components/series', ['series' => $series]);
  }
}
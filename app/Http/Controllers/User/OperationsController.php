<?php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OperationsController extends Controller
{
    public function index()
    {
        return view('user.fardeauMO.operations.index');
    }
}

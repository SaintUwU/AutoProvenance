<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCarRequest;
use App\Http\Requests\UpdateCarRequest;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use App\Http\Resources\CarResource;
use App\Models\Car;

class CarController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Admin/Cars/CarsIndex',[
            'cars'=>CarResource::collection(Car::all())]);
    }
    
     /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render(component:'Admin/Cars/Create'); 
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->middleware('auth');
        $car = new Car();
        $car->ownerId = auth()->id();
        $car->name = $request->input('name');
        $car->CarId = $request->input('CarId');
        $car->save();
        
        return redirect()->route('cars.index');
    }

    

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $CarId)
    {
        $car = Car::where('CarId',$CarId)->first();

        if(!$car){
            return abort(404);
        }

        //dd($car);

        return Inertia::render('Admin/Cars/Edit', [
            'car' => new CarResource($car)
            
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCarRequest $request, string $CarId)
    {
        $car = Car::where('CarId', $CarId);
        $car->update($request->validated());
        return to_route('cars.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $CarId)
    {
        $car = Car::where('CarId', $CarId);
        $car->delete();
        return back();
    }
}

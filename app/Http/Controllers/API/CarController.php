<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController;
use App\Models\Car;
use Validator;

class CarController extends BaseController
{
    private Car $car;

    public function __construct(Car $car)
    {
        $this->car = $car;
    }

    /**
     * Get all Cars
     */
    public function index()
    {
        $cars = $this->car->all();
        return $this->successResponse($cars, 'Cars retrieved successfully');
    }

    /**
     * Store new Car
     */
    public function store(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'name' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->errorsResponse($validator->errors(), 'Data invalid');
        }

        $newCar = Car::create($input);
        $newCar->save();
        return $this->successResponse($newCar, 'Car created successfully', 201);
    }

    /**
     * Get one car from id
     */
    public function show(int $id)
    {
        $car = $this->car->find($id);
        if (!$car) {
            return $this->errorsResponse(null, 'No car found', 404);
        }
        return $this->successResponse($car, 'Car retrieved successfully');
    }

    /**
     * Update car from id
     */
    public function update(Request $request, int $id)
    {
        $car = $this->car->find($id);
        if (!$car) {
            return $this->errorsResponse(null, 'No car found', 404);
        }

        $input = $request->all();

        $validator = Validator::make($input, [
            'name' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->errorsResponse($validator->errors(), 'Data invalid');
        }

        $car->name = $input['name'];
        $car->save();
        return $this->successResponse($car, 'Car updated successfully');
    }

    /**
     * Delete car from id
     */
    public function destroy($id)
    {
        $car = $this->car->find($id);
        if (!$car) {
            return $this->errorsResponse(null, 'No car found', 404);
        }
        $car->delete();
        return $this->successResponse(null, 'Car deleted successfully');
    }
}

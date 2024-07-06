<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Notifications\EventCreated;
use App\Notifications\EventDeleted;
use App\Notifications\EventUpdated;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\EventResource;
use App\Http\Resources\EventCollection;
use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;

class EventController extends Controller
{


    public function index(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            $search = $request->input('search');
            $query = Event::query()->latest('id');

            if ($search) {
                $query->where(function ($query) use ($search) {
                    $fillableColumns = (new Event())->getFillable();
                    foreach ($fillableColumns as $column) {
                        $query->orWhere($column, 'like', '%' . $search . '%');
                    }
                });
            }

            $events = $query->paginate($perPage);

            if ($events->isEmpty()) {
                return response()->json([
                    'success' => true,
                    'message' => 'No data found.',
                ], 200);
            }

            // $eventsResource = EventResource::collection($events);
            // return $eventsResource->response()->setStatusCode(200);
            return response()->json(new EventCollection($events))->setStatusCode(200);
        } catch (\Exception $error) {
            Log::error('An error occurred while fetching events', [
                'error' => $error->getMessage(),
            ]);
            return response()->json([
                'success' => false,
                'error' => 'An error occurred.',
                'message' => $error->getMessage(),
            ], 500);
        }
    }

    public function store(StoreEventRequest $request): JsonResponse
    {
        try {
            $event = Event::create($request->validated());
            // Notify via email and other channels as needed
            $user = $request->user();
            $user->notify(new EventCreated($event));


            return response()->json([
                'message' => 'Event created successfully.',
                'status' => 201,
                'success' => true,
                'event' => new EventResource($event),
            ], 201);
        } catch (\Exception $error) {
            Log::error('An error occurred while storing the event', [
                'error' => $error->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'error' => 'An error occurred.',
                'message' => $error->getMessage(),
            ], 500);
        }
    }

    public function show($id): JsonResponse
    {
        try {
            $event = Event::findOrFail($id);

            return response()->json([
                'message' => 'Event showed successfully.',
                'status' => 201,
                'success' => true,
                'event' => new EventResource($event),
            ], 201);
        } catch (Exception $e) {
            Log::error('Error fetching event details', ['event_id' => $id, 'error' => $e->getMessage()]);
            return response()->json(['message' => 'Error fetching event details', 'error' => $e->getmessage()], 500);
        }
    }

    public function update(UpdateEventRequest $request, $id): JsonResponse
    {
        try {
            $event = Event::findOrFail($id);
            $event->update($request->validated());
            $user = $request->user();
            $user->notify(new EventUpdated($event));

            Log::info('Event updated successfully', ['event' => $event]);
            return response()->json([
                'message' => 'Event updated successfully.',
                'status' => 201,
                'success' => true,
                'event' => new EventResource($event),
            ], 201);
        } catch (Exception $e) {
            Log::error('Error updating event', ['event_id' => $id, 'error' => $e->getMessage()]);
            return response()->json(['message' => 'Error updating event', 'error' => $e->getmessage()], 500);
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            $event = Event::findOrFail($id);
            $event->delete();

            $user = auth()->user();
            $user->notify(new EventDeleted($event));

            Log::info('Event deleted successfully', ['event_id' => $id]);

            return response()->json([
                'message' => 'Event deleted successfully',
                'status' => 201,
                'success' => true,
            ]);
        } catch (Exception $e) {
            Log::error('Error deleting event', ['event_id' => $id, 'error' => $e->getMessage()]);
            return response()->json(['message' => 'Error deleting event', 'error' => $e->getmessage()], 500);
        }
    }
}

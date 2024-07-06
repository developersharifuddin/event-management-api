<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'date' => $this->date,
            'location' => $this->location,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }



    /**
     * Add additional data to the resource response.
     *
     * @param  Request  $request
     * @return array
     */
    // public function with($request)
    // {
    //     return [
    //         'meta' => [
    //             'key' => true,
    //             'total' => $this->total(),
    //             'per_page' => $this->perPage(),
    //             'current_page' => $this->currentPage(),
    //             'last_page' => $this->lastPage(),
    //             'first_page_url' => $this->url(1),
    //             'last_page_url' => $this->url($this->lastPage()),
    //             'next_page_url' => $this->nextPageUrl(),
    //             'prev_page_url' => $this->previousPageUrl(),
    //             'path' => $this->path(),
    //             'from' => $this->firstItem(),
    //             'to' => $this->lastItem(),
    //         ],
    //     ];
    // }


    /**
     * Customize the outgoing response for the resource.
     *
     * @param  Request  $request
     * @param  \Illuminate\Http\JsonResponse  $response
     * @return void
     */
    // public function withResponse($request, $response)
    // {
    //     $response->header('X-Value', 'True');
    // }
}

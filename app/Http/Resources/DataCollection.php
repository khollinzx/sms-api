<?php
namespace App\Http\Resources;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class DataCollection extends ResourceCollection
{

    /**
     * @var mixed|string
     */
    protected $name;
    protected $links;
    protected array $pagination;

    public function __construct($resource, $name = "data")
    {
        $this->name       = $name;
        $this->pagination = [
            'total'       => (int) $resource->total(),
            'count'       => (int) $resource->count(),
            'perPage'     => (int) $resource->perPage(),
            'currentPage' => (int) $resource->currentPage(),
            'totalPages'  => (int) $resource->lastPage(),
            'links'       => [
                'first' => $resource->url(1),
                'last'  => $resource->url($resource->lastPage()),
                'prev'  => $resource->previousPageUrl(),
                'next'  => $resource->nextPageUrl(),
            ],
        ];
        $resource = $resource->getCollection();
        parent::__construct($resource);
    }

    /**
     * @param Request $request
     * @param JsonResponse $response
     */
    public function withResponse($request, $response): void
    {
        $json_response = json_decode($response->getContent(), true);
        $response->setContent(json_encode($json_response));
    }

    /**
     * Transform the resource collection into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            $this->name  => $this->collection,
            'pagination' => $this->pagination,
        ];
    }
}

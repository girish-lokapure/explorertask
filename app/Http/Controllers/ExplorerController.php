<?php

namespace App\Http\Controllers;


use App\Models\Cost;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class ExplorerController extends Controller
{

    public function index(Request $request)
    {

        $requestArr = [
            'clientId' => 'client_id',
            'costTypeId' => 'cost_type_id',
            'projectId' => 'project_id'
        ];

        foreach ($requestArr as $k => $v) {
            $$k = [];
            if ($request->has($v)) {
                $$k = $request->get($v);
            }
        }


//        echo "<pre>";
        $results = $this->getCosts($clientId, $costTypeId, $projectId);
//        print_r($results->toArray());
//        exit;
        $response = [];
        foreach ($results as $result) {

            $clientId = $result->project->client->id;
            $projectId = $result->project->id;

            $response = $this->setClient($response, $clientId, $result);

            $response = $this->setProject($response, $clientId, $projectId, $result);

            $response = $this->setCost($response, $clientId, $projectId, $result);

            ksort($response);
        }

        $response = $this->formatResponse($response);

//        print_r($response);
        return response()->json(['query'=>urldecode($request->fullUrl()),'data'=>$response]);
    }

    /**
     * @param $clientId
     * @param $costTypeId
     * @param $projectId
     * @return Collection
     */
    protected function getCosts($clientId, $costTypeId, $projectId): Collection
    {
        $results = Cost::with('project.client', 'type');

        if (!empty($clientId)) {
            $results->whereHas('project.client', function ($query) use ($clientId) {
                $query->whereIn('id', $clientId);
            });
        }

        if (!empty($costTypeId)) {
            $results->whereHas('type', function ($query) use ($costTypeId) {
                $query->whereIn('id', $costTypeId);
            });
        }

        if (!empty($projectId)) {
            $results->whereIn('project_id', $projectId);

        }

        $results = $results->get();
        return $results;
    }

    /**
     * @param array $response
     * @param int $clientId
     * @param mixed $result
     * @return array
     */
    protected function setClient(array $response, int $clientId, mixed $result): array
    {
        if (!isset($response[$clientId])) {
            $response[$clientId] = $result->project->client->toArray();
            $response[$clientId]['type'] = 'client';
            $response[$clientId]['amount'] = 0;
        }
        return $response;
    }

    /**
     * @param array $response
     * @param int $clientId
     * @param int $projectId
     * @param mixed $result
     * @return array
     */
    protected function setProject(array $response, int $clientId, int $projectId, mixed $result): array
    {
        if (!isset($response[$clientId]['children'][$projectId])) {
            $response[$clientId]['children'][$projectId] = $result->project->toArray();
            $response[$clientId]['children'][$projectId]['type'] = 'project';
            $response[$clientId]['children'][$projectId]['amount'] = 0;
            unset($response[$clientId]['children'][$projectId]['client']);
            unset($response[$clientId]['children'][$projectId]['client_id']);

        }
        return $response;
    }

    /**
     * @param array $response
     * @param int $clientId
     * @param int $projectId
     * @param mixed $result
     * @return array
     */
    protected function setCost(array $response, int $clientId, int $projectId, mixed $result): array
    {
        $temp = $result->type->toArray();
        $temp['type'] = 'cost';
        $temp['amount'] = $result->amount;
        $temp['children'] = [];
        $response[$clientId]['children'][$projectId]['children'][$temp['id']] = $temp;
        unset($temp);
        return $response;
    }

    /**
     * @param array $response
     * @return array
     */
    protected function formatResponse(array $response): array
    {
        foreach ($response as &$client) {
            foreach ($client['children'] as &$project) {
                foreach ($project['children'] as $k => &$cost) {

                    if (isset($cost['parent_id']) && !empty($project['children'][$cost['parent_id']])) {
                        $project['children'][$cost['parent_id']]['amount'] += $project['children'][$k]['amount'];
                        $project['children'][$cost['parent_id']]['children'][] = array_diff_key($project['children'][$k], ['parent_id' => '1']);
                        unset($project['children'][$k]);
                    }
                    unset($cost['parent_id']);
                    $project['amount'] += $cost['amount'];

                }
                $project['children'] = array_values($project['children']);
                $client['amount'] += $project['amount'];
            }
            $client['children'] = array_values($client['children']);
        }
        return array_values($response);
    }
}


<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeleteUrlRequest;
use App\Http\Requests\PaginationRequest;
use App\Http\Requests\StoreUrlRequest;
use App\Models\Protocol;
use App\Models\Url;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class UrlController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(PaginationRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['search'] = Auth::id();

        $perPage = $data['perPage'] ??  5;

        $urls = (new Url)->getSortedAndFiltered($data, ['user_id'], 1);

        return response()->json($urls->paginate($perPage));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUrlRequest $request): JsonResponse
    {
        $data = $request->validated();
        $protocol = null;
        $protocols = Protocol::all('protocol')->toArray();
        //Get the protocol, if it's there.
        foreach($protocols as $p) {
            if( Str::startsWith($data['url'], $p) ){
                //trim it and set the protocol
                $data['url'] = ltrim($data['url'], $p['protocol']);
                $protocol = $p;
                break;
            }
        }

        if(is_null($protocol)) {
            $protocol = 'https://'; //TODO undo hardcoded defaults
        }

        $data['protocol_id'] = (Protocol::whereProtocol($protocol)->first())->id ?? 1;
        $data['user_id'] = Auth::id();

        try {
            $url = new Url($data);
            $url->save();

        } catch (Exception $e) {
            Log::error('Error in ' . __CLASS__ . ' ' . __METHOD__ . ' unable to create URL using ' . print_r($data, true) . ' ' . $e->getMessage());

            return response()->json(
                [
                    'status' => 'error', 'message' => 'Failed to create URL'
                ],
                409
            );
        }
        return response()->json($url->toArray());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  DeleteUrlRequest $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Url $url): JsonResponse
    {
        if(!$url || Auth::id() != $url->user_id) {
            return response()->json(['status' => 'error', 'message' => 'Resource unathorized'], 403);
        }
        try {
            $url->delete();
        } catch (Exception $e) {
            Log::error('Failed to delete URL ' . $url->id . '. ' . $e->getMessage());
            //I hate 409, but the RFC is pretty clear neither 403 or 405 are better choices
            return response()->json(409, ['status' => 'error', 'message' => 'Can not delete URL ' . $url->id]);
        }

        return response()->json(['status' => 'success', 'message' => 'Resource deleted']);
    }
}

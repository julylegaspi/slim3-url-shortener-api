<?php

namespace App\Controllers;

use App\Models\Link;

class HomeController extends Controller
{
    public function index ($request,$response)
    {
        return '<h1>URL shortener API</h1>';
    }

    public function generate ($request,$response)
    {
        
        $data = $request->getParsedBody();

        if (empty($data) || empty(trim($data['url']))) {

            return $response->withStatus(400)->withJson([
                'error' => [
                    'code' => 100,
                    'message' => 'URL is required'
                ]
            ]);
        }

        if (!filter_var($data['url'], FILTER_VALIDATE_URL)) {

            return $response->withStatus(400)->withJson([
                'error' => [
                    'code' => 101,
                    'message' => 'A valid URL is required'
                ]
            ]);
        }

        $link = Link::where('url', $data['url'])->first();

        if ($link) {
            return $response->withStatus(201)->withJson([
                'url' => $data['url'],
                'generated' => [
                    'url' => $this->container['settings']['baseUrl'] . $link->code,
                    'code' => $link->code
                ]
            ]);
        }

        $newLink = Link::create([
            'url' => $data['url']
        ]);

        $newLink->update([
            'code' => base_convert(time(), 10, 36)
        ]);

        return $response->withJson([
            'url' => $data['url'],
            'generated' => [
                'url' => $this->container['settings']['baseUrl'] . $newLink->code,
                'code' => $newLink->code
            ]
        ]);
    }

    public function code ($request,$response, $args)
    {
        $link = Link::where('code', $args['code'])->first();
        if (!$link) {
            throw new \Slim\Exception\NotFoundException($request, $response);
        }
        return $response->withRedirect($link->url);
    }
}
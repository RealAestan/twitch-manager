<?php

namespace App\Client;

class TwitchClient
{
    private \NewTwitchApi\NewTwitchApi $newTwitchApi;
    private string $twitchAccessToken;

    public function __construct(private string $twitchClientId, private string $twitchClientSecret)
    {
        $twitch_scopes = '';
        $helixGuzzleClient = new \NewTwitchApi\HelixGuzzleClient($this->twitchClientId);
        $newTwitchApi = new \NewTwitchApi\NewTwitchApi($helixGuzzleClient, $this->twitchClientId, $this->twitchClientSecret);
        $oauth = $newTwitchApi->getOauthApi();
        try {
            $token = $oauth->getAppAccessToken($twitch_scopes ?? '');
            $data = json_decode($token->getBody()->getContents());
            $this->twitchAccessToken = $data->access_token ?? null;
        } catch (Exception $e) {
            //TODO: Handle Error
        }
        $this->newTwitchApi = $newTwitchApi;
    }

    public function getBroadcasterId(string $channelName): ?string
    {
        try {
            $response = $this->newTwitchApi->getSearchApi()->searchChannels($this->twitchAccessToken, $channelName);
            $responseContent = json_decode($response->getBody()->getContents());

            foreach ($responseContent->data as $datum) {
                if (strtolower($datum->display_name) === strtolower($channelName)) {
                    return $datum->id;
                }
            }

            return null;
        } catch (GuzzleException $e) {
            //TODO: Handle Error
        }
    }

    public function getStreamData(string $broadcasterId): \stdClass {

        try {
            $data = new \stdClass();
            $response = $this->newTwitchApi->getStreamsApi()->getStreamForUserId($this->twitchAccessToken, $broadcasterId);
            $responseContent = json_decode($response->getBody()->getContents());
            if (!empty($responseContent->data)) {
                $data->stream = $responseContent->data[0];
                $response = $this->newTwitchApi->getGamesApi()->getGames($this->twitchAccessToken, [$data->stream->game_id]);
                $responseContent = json_decode($response->getBody()->getContents());
//            $data->thumbnail_url = str_replace('{height}', '216', str_replace('{width}', '380', $data->thumbnail_url));
//            $data->box_art_url = str_replace('{height}', '380', str_replace('{width}', '285', $responseContent->data[0]->box_art_url));
                $data->game = $responseContent->data[0];
            }
            $response = $this->newTwitchApi->getUsersApi()->getUserById($this->twitchAccessToken, $broadcasterId);
            $responseContent = json_decode($response->getBody()->getContents());
            $data->user = $responseContent->data[0];

            return $data;
        } catch (GuzzleException $e) {
            //TODO: Handle Error
        }
    }
}

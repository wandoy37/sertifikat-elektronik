<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class SimpeltanService
{
        protected string $baseUrl;

        public function __construct()
        {
                $this->baseUrl = config('services.simpeltan.base_url');
        }

        protected function client()
        {
                $client = Http::baseUrl($this->baseUrl);

                return $client;
        }

        public function getPeserta()
        {
                return $this->client()->get('/data-peserta')->json();
        }

        public function getPesertaById($id)
        {
                return $this->client()->get("/data-peserta/{$id}")->json();
        }
}

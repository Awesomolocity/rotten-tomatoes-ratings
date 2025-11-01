<?php

namespace App\Livewire\Movie\Index;

use App\Rules\RottenTomatoesMovie;
use Dom\HTMLDocument;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Uri;
use JetBrains\PhpStorm\NoReturn;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Page extends Component
{
    #[Validate]
    public string $url = '';

    public ?string $rating;

    #[NoReturn]
    public function getRating(): void
    {
        $this->validate();

        $key = Uri::of($this->url)->pathSegments()->get(1);

        $this->rating = Cache::remember("rating.{$key}", 60 * 60, function () {
            return $this->getRTData();
        });
    }

    /**
     * @throws ConnectionException
     */
    private function getRTData()
    {
        $response = Http::get($this->url);

        $elements = HTMLDocument::createFromString($response->body(), LIBXML_NOERROR)->querySelectorAll('#media-scorecard-json');
        if (count($elements)) {
            return Collection::fromJson($elements[0]->textContent)['criticsScore']['averageRating'];
        }

        return null;
    }

    protected function rules()
    {
        return [
            'url' => [
                'required',
                new RottenTomatoesMovie,
            ],
        ];
    }

    public function render()
    {
        return view('livewire.movie.index.page');
    }
}

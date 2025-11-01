<flux:main class="space-y-6">
    <flux:heading size="xl" level="1">Rotten Tomatoes Average Rating</flux:heading>
    @if($rating)
        <flux:subheading size="xl" class="text-center">
            {{ $rating }}/10
        </flux:subheading>
    @endif
    <flux:card>
        <form class="space-y-3" action="/" wire:submit="getRating">
            <flux:field>
                <flux:label>URL</flux:label>
                <flux:description>The link to the movie on Rotten Tomatoes</flux:description>
                <flux:input wire:model.blur="url" placeholder="https://www.rottentomatoes.com/m/lady_bird" name="url" />
                <flux:error name="url" />
            </flux:field>
            <flux:button class="w-full" variant="primary" type="submit">Get Rating</flux:button>
        </form>
    </flux:card>
</flux:main>

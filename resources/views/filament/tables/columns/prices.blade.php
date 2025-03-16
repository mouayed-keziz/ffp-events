<div class="space-y-1">
    @foreach ($getState() as $currency => $amount)
        <div class="flex items-center gap-1">
            <span class="font-medium">{{ $currency }}:</span>
            <span>{{ number_format($amount, 2) }}</span>
        </div>
    @endforeach
</div>

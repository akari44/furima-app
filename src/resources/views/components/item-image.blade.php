<div class="item-img">
    @if($item->status === 'sold')
        <div class="sold-overlay">SOLD</div>
    @endif

    <img
        src="{{ $item->images->first()
            ? asset('storage/' . $item->images->first()->image_path)
            : asset('images/noimage.png') }}"
        alt="{{ $item->item_name }}">
</div>

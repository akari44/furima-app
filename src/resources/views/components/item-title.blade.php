<p class="item-title">
    @if($item->status === 'sold')
        <span class="sold-inline">【SOLD】</span>
    @endif
    {{ $item->item_name }}
</p>

<p class="item-title">
    @if($item->status === 'sold')
        <span class="sold-inline">【Sold】</span>
    @endif
    {{ $item->item_name }}
</p>

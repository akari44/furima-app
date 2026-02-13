<div class="item-groups">

    {{-- 売り切れならリンクなし --}}
    @if($item->status === 'sold')
        <x-item-image :item="$item" />
    @else
        <a href="{{ url('/item/' . $item->id) }}">
            <x-item-image :item="$item" />
        </a>
    @endif

    {{-- 商品名 --}}
    <x-item-title :item="$item" />

</div>

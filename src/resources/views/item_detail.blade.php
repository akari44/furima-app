
@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/item_detail.css') }}">
@endsection

@section('content')
<div class="content-wrapper">
<!--左側（商品画像）-->
    <div class="wrapper-left">
        <div class="item-image">   
            <x-item-image :item="$item" />
        </div>
    </div>

<!--右側（商品詳細）-->
    <div class="wrapper-right">
        <!--購入エリア(クエリパラメータを送る）-->
        <div class="detail__name">
            <x-item-title :item="$item"/>
        </div>
        <div class="detail__brand">
            <p>{{ $item->brand}}</p>
        </div>
        <div class="detail__price">
            <p class="item-price">￥<span>{{number_format($item->price) }}</span>（税込）</p>

        </div>    

    
        <!--いいねとコメントマーク-->
        <script>
            const isAuth = @json(Auth::check());
        </script>

        <div class="detail__marks-wrapper">
            <div class="likes"
            id="likeBtn"
            data-item-id="{{ $item->id }}"
            style="cursor: pointer;">
                <img
                src="{{ asset($isLiked ? 'images/likes_active.png' : 'images/likes.png') }}"
                alt="イイね"
                id="likeIcon">
                <p id="likeCount">{{ $item->likes_count }}</p>
            </div>

            <div class="comments">
                <img src="{{asset ('images/comments.png')}}" alt="コメント">
                <p>{{ $item->comments_count }}</p>
            </div>
        </div> 


        <div class="detail__buy-button">
            @if($item->status === 'sold')
            <a href="{{ route('items.index') }}">この商品は売り切れです</a>
            @else
            <a href="{{ route('purchase.show', ['item_id' => $item->id]) }}">購入手続きへ</a>
            @endif
        </div>

        <!--商品の情報-->
        <h5>商品説明</h5>
        <div class="detail__item-description">
            <p>{{ $item->description}}</p>
        </div>
        <h5>商品の情報</h5>
        <div class="detail__item-info">
            <div class="categories__wrapper">
                <p class="info-text">カテゴリー</p>
                <div class="item-categories">
                         @foreach ($item->categories as $category)
                        <p class="category-name">{{ $category->category_name }}</p>
                        @endforeach
                        
                </div>
            </div>
            <div class="conditions__wrapper">
                <p class="info-text">商品の状態</p> 
                <div class="item-condition">{{ $item->condition_label}}</div>
            </div>
        </div>
        <!--商品へのコメントフォーム-->
        <h5>コメント（{{ $item->comments_count }}）</h5>
       
        <div class="comment-area">
            @forelse($item->comments as $comment)
            <div class="author">
                <div class="author-icon">
                    <img class="author-icon__image"
                        src="{{ $comment->user->avatar_path ? asset('storage/' . $comment->user->avatar_path) : asset('images/default.png') }}"
                        alt="author icon">
                </div>    
                <p class="author-name">{{ $comment->user->name }}</p>
            </div>

            <div class="author-body">
                <div class="comment-meta">
                <span>{{ $comment->created_at->format('Y/m/d H:i') }}</span>
                </div>
                <p>{{ $comment->body }}</p>
            </div>
            @empty
            <p>まだコメントはありません。</p>
            @endforelse

        </div>
        <div class="create-comment_title">
            <p>商品へのコメント</p>
        </div>    
        
        <div class="create-comment">
            <form action="{{ route('comments.store', $item->id) }}" method="post">
            @csrf
                <textarea name="body" rows="3">{{ old('body') }}</textarea>
            @error('body') <p class="error">{{ $message }}</p> @enderror
                <button class="detail__comment-button" type="submit">コメントを送信する</button>
            </form>
        </div>

        
    </div>
</div>

<!--いいね用ＪＳ-->
<script>
document.addEventListener('DOMContentLoaded', () => {
  const btn = document.getElementById('likeBtn');
  if (!btn) return;

  btn.addEventListener('click', function () {

    if (!isAuth) {
      window.location.href = '/login';
      return;
    }
    const itemId = this.dataset.itemId;

    fetch(`/items/${itemId}/like`, {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': '{{ csrf_token() }}',
        'X-Requested-With': 'XMLHttpRequest',
        'Accept': 'application/json',
      },
      credentials: 'same-origin',
    })
    .then(async (res) => {
      // 失敗したら原因が分かるようにする
      const text = await res.text();
      console.log('status:', res.status);
      console.log('response:', text);

      if (!res.ok) throw new Error(`HTTP ${res.status}`);
      return JSON.parse(text);
    })
    .then(data => {
      const icon = document.getElementById('likeIcon');
      if (icon) {
        icon.src = data.liked
          ? '{{ asset("images/likes_active.png") }}'
          : '{{ asset("images/likes.png") }}';
      }

      const countEl = document.getElementById('likeCount');
      if (countEl) countEl.textContent = data.count;
    })
    .catch(e => console.error('like error:', e));
  });
});
</script>

@endsection
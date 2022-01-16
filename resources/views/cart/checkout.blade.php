<p>決済ページへリダイレクトします。</p>
{{-- Stripe.jsスクリプトの読み込み --}}
<script src="https://js.stripe.com/v3/"></script>

<script>
    // 公開可能キーを代入
    const publicKey = '{{ $publicKey }}';
    // Stripeオブジェクトの作成
    const stripe = Stripe(publicKey);

    // 決済画面へリダイレクト
    window.onload = function() {
        stripe.redirectToCheckout({
            sessionId: '{{ $session->id }}'
        // エラー処理
        }).then(function (result) {
            window.location.href = 'http://localhost/cart';
        });
    }
</script>

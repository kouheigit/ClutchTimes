@props(['user'])

<div class="bg-white border rounded-lg p-4">
    <h4 class="text-lg font-semibold mb-3">ユーザー情報</h4>
    <div class="space-y-2 text-sm">
        <div class="flex justify-between">
            <span class="text-gray-600">名前:</span>
            <span class="font-medium">{{ $user->name }}</span>
        </div>
        <div class="flex justify-between">
            <span class="text-gray-600">メールアドレス:</span>
            <span class="font-medium">{{ $user->email }}</span>
        </div>
        @if($user->member_id)
        <div class="flex justify-between">
            <span class="text-gray-600">会員ID:</span>
            <span class="font-medium">{{ $user->member_id }}</span>
        </div>
        @endif
        @if($user->tel)
        <div class="flex justify-between">
            <span class="text-gray-600">電話番号:</span>
            <span class="font-medium">{{ $user->tel }}</span>
        </div>
        @endif
        <div class="flex justify-between">
            <span class="text-gray-600">タイプ:</span>
            <span class="font-medium">
                @if($user->type == \App\Consts\UserConst::TYPE_OWNER)
                オーナー
                @else
                一般ユーザー
                @endif
            </span>
        </div>
    </div>
</div>





















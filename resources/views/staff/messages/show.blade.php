@extends('layouts.app')

@section('content')
<style>
    .page-title { color: #4a0080; font-weight: 800; }
    .chat-box { background: white; border-radius: 18px; box-shadow: 0 5px 20px rgba(74,0,128,0.08); overflow: hidden; max-width: 750px; margin: 0 auto; }
    .chat-header { background: linear-gradient(135deg,#4a0080,#7b2ff7); padding: 18px 22px; display: flex; align-items: center; gap: 14px; color: white; }
    .chat-av { width: 42px; height: 42px; border-radius: 50%; background: rgba(255,255,255,0.25); display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 1.1rem; flex-shrink: 0; }
    .chat-name { font-weight: 700; font-size: 0.95rem; }
    .chat-sub  { font-size: 0.75rem; opacity: 0.75; }
    .chat-body { padding: 20px; min-height: 400px; max-height: 480px; overflow-y: auto; background: #faf5ff; display: flex; flex-direction: column; gap: 10px; }
    .msg-bubble { max-width: 75%; padding: 10px 14px; border-radius: 16px; font-size: 0.85rem; line-height: 1.5; }
    .msg-bubble.mine { background: linear-gradient(135deg,#4a0080,#7b2ff7); color: white; align-self: flex-end; border-bottom-right-radius: 4px; }
    .msg-bubble.theirs { background: white; color: #1f2937; align-self: flex-start; border-bottom-left-radius: 4px; box-shadow: 0 2px 8px rgba(0,0,0,0.06); }
    .msg-time { font-size: 0.68rem; margin-top: 4px; }
    .msg-time.mine   { text-align: right; color: rgba(255,255,255,0.7); }
    .msg-time.theirs { color: #9ca3af; }
    .msg-sender { font-size: 0.72rem; font-weight: 700; color: #9b59b6; margin-bottom: 3px; }
    .chat-footer { padding: 16px 20px; border-top: 1px solid #f3e8ff; background: white; }
    .chat-input { border: 1.5px solid #e9d5ff; border-radius: 10px; padding: 10px 14px; font-size: 0.88rem; width: 100%; outline: none; resize: none; }
    .chat-input:focus { border-color: #7b2ff7; }
    .chat-send { background: linear-gradient(135deg,#4a0080,#7b2ff7); color: white; border: none; border-radius: 10px; padding: 10px 20px; font-weight: 700; font-size: 0.85rem; cursor: pointer; }
    .chat-send:hover { opacity: 0.9; }
    .empty-chat { flex: 1; display: flex; align-items: center; justify-content: center; color: #ce93d8; font-size: 0.85rem; }
</style>

<div class="mb-3">
    <a href="{{ route('staff.messages.index') }}" style="color:#7b2ff7;font-size:0.85rem;text-decoration:none;">
        <i class="fas fa-arrow-left me-1"></i> Back to Messages
    </a>
</div>

<div class="chat-box">
    <div class="chat-header">
        <div class="chat-av">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
        <div>
            <div class="chat-name">{{ $user->name }}</div>
            <div class="chat-sub">{{ $user->email }}</div>
        </div>
    </div>

    <div class="chat-body" id="chatBody">
        @forelse($messages as $msg)
        @php $isMine = $msg->sender_role !== 'user'; @endphp
        <div>
            @if(!$isMine)
                <div class="msg-sender">{{ $msg->sender->name }}</div>
            @endif
            <div class="msg-bubble {{ $isMine ? 'mine' : 'theirs' }}">
                {{ $msg->body }}
            </div>
            <div class="msg-time {{ $isMine ? 'mine' : 'theirs' }}">{{ $msg->created_at->format('M d, g:i A') }}</div>
        </div>
        @empty
        <div class="empty-chat"><i class="fas fa-comment-dots me-2"></i> No messages yet.</div>
        @endforelse
    </div>

    <div class="chat-footer">
        @if(session('success'))
            <div class="alert alert-success py-2 mb-2" style="border-radius:8px;font-size:0.83rem;">{{ session('success') }}</div>
        @endif
        <form action="{{ route('staff.messages.reply', $user->id) }}" method="POST">
            @csrf
            <div class="d-flex gap-2">
                <textarea name="body" class="chat-input" rows="2" placeholder="Type a message..." required></textarea>
                <button type="submit" class="chat-send"><i class="fas fa-paper-plane"></i></button>
            </div>
        </form>
    </div>
</div>

<script>
    // Scroll to bottom
    const cb = document.getElementById('chatBody');
    cb.scrollTop = cb.scrollHeight;
</script>
@endsection
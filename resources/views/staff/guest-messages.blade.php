@extends('layouts.app')
@section('title', 'Guest Messages')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-0" style="color:#2d0a4e;">Guest Messages</h4>
        <small class="text-muted">Messages sent from the website</small>
    </div>
    @if($unread > 0)
    <span style="background:#ef4444;color:white;padding:4px 12px;border-radius:20px;font-size:12px;font-weight:700;">
        {{ $unread }} unread
    </span>
    @endif
</div>

@if(session('success'))
<div class="alert alert-success mb-3" style="border-radius:10px;font-size:13px;">{{ session('success') }}</div>
@endif

<div class="row g-3">
    @forelse($messages as $msg)
    <div class="col-12">
        <div class="card border-0 shadow-sm {{ !$msg->is_read ? 'border-start border-4 border-primary' : '' }}"
             style="border-radius:12px;{{ !$msg->is_read ? 'border-left:3px solid #4a0080 !important;' : '' }}">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <div style="font-weight:700;color:#2d0057;font-size:14px;">{{ $msg->guest_name }}</div>
                        <div style="font-size:12px;color:#9ca3af;">
                            <i class="fas fa-phone me-1"></i>{{ $msg->guest_phone }}
                            @if($msg->guest_email)
                            · <i class="fas fa-envelope me-1"></i>{{ $msg->guest_email }}
                            @endif
                        </div>
                        <div style="font-size:11px;color:#9ca3af;margin-top:2px;">{{ $msg->created_at->diffForHumans() }}</div>
                    </div>
                    <div class="d-flex gap-2 align-items-center">
                        @if(!$msg->is_read)
                        <span style="background:#fef3c7;color:#92400e;padding:3px 10px;border-radius:20px;font-size:10px;font-weight:700;">NEW</span>
                        @endif
                        @if($msg->staff_reply)
                        <span style="background:#d1fae5;color:#065f46;padding:3px 10px;border-radius:20px;font-size:10px;font-weight:700;">REPLIED</span>
                        @endif
                    </div>
                </div>

                {{-- Guest Message --}}
                <div style="background:#f5f0ff;border-radius:10px;padding:12px 14px;margin-bottom:12px;font-size:13px;color:#374151;">
                    <div style="font-size:10px;font-weight:700;color:#9b59b6;margin-bottom:4px;text-transform:uppercase;">Message</div>
                    {{ $msg->message }}
                </div>

                {{-- Staff Reply --}}
                @if($msg->staff_reply)
                <div style="background:#f0fdf4;border-radius:10px;padding:12px 14px;margin-bottom:12px;font-size:13px;color:#374151;border-left:3px solid #10b981;">
                    <div style="font-size:10px;font-weight:700;color:#059669;margin-bottom:4px;text-transform:uppercase;">Your Reply · {{ $msg->replied_at?->format('M d, Y h:i A') }}</div>
                    {{ $msg->staff_reply }}
                </div>
                @endif

                {{-- Reply Form --}}
                <form action="{{ route('staff.guest-messages.reply', $msg->id) }}" method="POST">
                    @csrf
                    <div class="d-flex gap-2">
                        <input type="text" name="reply"
                               class="form-control form-control-sm"
                               placeholder="{{ $msg->staff_reply ? 'Send another reply...' : 'Type your reply...' }}"
                               style="border-radius:8px;font-size:12px;border:1.5px solid #e9d5ff;"
                               required>
                        <button type="submit" class="btn btn-sm"
                                style="background:#4a0080;color:white;border-radius:8px;font-size:12px;font-weight:600;white-space:nowrap;">
                            <i class="fas fa-paper-plane me-1"></i> Reply
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="text-center py-5" style="color:#9ca3af;">
            <i class="fas fa-comments fa-3x mb-3 d-block" style="color:#ce93d8;"></i>
            No guest messages yet.
        </div>
    </div>
    @endforelse
</div>

<div class="mt-3">{{ $messages->links() }}</div>
@endsection
@extends('layouts.app')

@section('title', 'Guest Messages')

@section('content')

<style>
    .page-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 24px;
    }
    .page-title {
        font-size: 1.1rem;
        font-weight: 800;
        color: #2d0057;
        border-left: 4px solid #7b2ff7;
        padding-left: 14px;
    }
    .unread-badge {
        background: linear-gradient(135deg, #7b2ff7, #9b4fea);
        color: #fff;
        font-size: 0.78rem;
        font-weight: 700;
        padding: 5px 14px;
        border-radius: 20px;
    }

    /* Alert */
    .alert-success {
        background: #e8f5e9;
        color: #2e7d32;
        border: 1px solid #c8e6c9;
        border-radius: 10px;
        padding: 12px 18px;
        margin-bottom: 18px;
        font-size: 0.88rem;
        font-weight: 600;
    }

    /* Message Card */
    .msg-card {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(74,0,128,0.07);
        margin-bottom: 18px;
        overflow: hidden;
        border: 1px solid #f0e6ff;
        transition: box-shadow 0.15s;
    }
    .msg-card:hover { box-shadow: 0 6px 28px rgba(74,0,128,0.12); }
    .msg-card.unread { border-left: 4px solid #7b2ff7; }

    /* Card Header */
    .msg-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 16px 20px 12px;
        border-bottom: 1px solid #f3e8ff;
    }
    .guest-info { display: flex; align-items: center; gap: 12px; }
    .guest-avatar {
        width: 40px; height: 40px; border-radius: 50%;
        background: linear-gradient(135deg, #7b2ff7, #9b4fea);
        color: #fff; display: flex; align-items: center;
        justify-content: center; font-size: 0.9rem; font-weight: 700;
        flex-shrink: 0;
    }
    .guest-name { font-weight: 700; font-size: 0.92rem; color: #1f2937; }
    .guest-contact { font-size: 0.76rem; color: #9b59b6; margin-top: 2px; }
    .msg-meta { display: flex; align-items: center; gap: 8px; }
    .badge-new {
        background: #7b2ff7; color: #fff;
        font-size: 0.68rem; font-weight: 700;
        padding: 3px 10px; border-radius: 20px;
    }
    .badge-replied {
        background: #e8f5e9; color: #2e7d32;
        border: 1px solid #c8e6c9;
        font-size: 0.68rem; font-weight: 700;
        padding: 3px 10px; border-radius: 20px;
    }
    .msg-time { font-size: 0.74rem; color: #bbb; }

    /* Message Body */
    .msg-body { padding: 16px 20px; }
    .msg-label {
        font-size: 0.72rem; font-weight: 700;
        color: #9b59b6; text-transform: uppercase;
        letter-spacing: 0.05em; margin-bottom: 6px;
    }
    .msg-text {
        font-size: 0.88rem; color: #374151;
        line-height: 1.6; background: #faf5ff;
        border-radius: 10px; padding: 12px 14px;
    }

    /* Staff Reply */
    .reply-section {
        padding: 0 20px 16px;
        border-top: 1px dashed #e9d5ff;
        margin-top: 4px;
        padding-top: 14px;
    }
    .reply-header {
        display: flex; align-items: center; gap: 8px;
        margin-bottom: 8px;
    }
    .reply-label {
        font-size: 0.72rem; font-weight: 700;
        color: #2e7d32; text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    .reply-date { font-size: 0.72rem; color: #bbb; }
    .reply-text {
        font-size: 0.88rem; color: #374151;
        line-height: 1.6; background: #e8f5e9;
        border: 1px solid #c8e6c9;
        border-radius: 10px; padding: 12px 14px;
    }

    /* Reply Form */
    .reply-form-section { padding: 0 20px 18px; }
    .reply-textarea {
        width: 100%;
        border: 1px solid #e0d0f5;
        border-radius: 10px;
        padding: 10px 14px;
        font-size: 0.85rem;
        font-family: inherit;
        color: #374151;
        resize: vertical;
        min-height: 80px;
        transition: border-color 0.15s;
        background: #fdfaff;
    }
    .reply-textarea:focus {
        outline: none;
        border-color: #7b2ff7;
        box-shadow: 0 0 0 3px rgba(123,47,247,0.08);
    }
    .btn-reply {
        margin-top: 8px;
        background: linear-gradient(135deg, #4a0080, #7b2ff7);
        color: #fff; border: none;
        border-radius: 8px; padding: 8px 20px;
        font-size: 0.82rem; font-weight: 700;
        cursor: pointer; display: inline-flex;
        align-items: center; gap: 6px;
        font-family: inherit;
        transition: opacity 0.15s;
    }
    .btn-reply:hover { opacity: 0.88; }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #bbb;
    }
    .empty-icon {
        width: 60px; height: 60px; border-radius: 50%;
        background: #f3e8ff;
        display: flex; align-items: center; justify-content: center;
        margin: 0 auto 16px;
    }
    .empty-icon i { font-size: 1.6rem; color: #ce93d8; }
    .empty-text { font-size: 0.9rem; color: #aaa; }

    /* Pagination */
    .pagination-wrap { margin-top: 20px; }
</style>

{{-- Page Header --}}
<div class="page-header">
    <p class="page-title mb-0">
        <i class="fas fa-comments me-2"></i> Guest Messages
    </p>
    @if($unread > 0)
        <span class="unread-badge">
            <i class="fas fa-envelope me-1"></i> {{ $unread }} unread
        </span>
    @endif
</div>

{{-- Success Alert --}}
@if(session('success'))
    <div class="alert-success">
        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
    </div>
@endif

{{-- Messages List --}}
@forelse($messages as $msg)
    <div class="msg-card {{ !$msg->is_read ? 'unread' : '' }}">

        {{-- Header --}}
        <div class="msg-header">
            <div class="guest-info">
                <div class="guest-avatar">
                    {{ strtoupper(substr($msg->guest_name, 0, 1)) }}
                </div>
                <div>
                    <div class="guest-name">{{ $msg->guest_name }}</div>
                    <div class="guest-contact">
                        {{ $msg->guest_phone }}
                        @if($msg->guest_email)
                            &nbsp;·&nbsp; {{ $msg->guest_email }}
                        @endif
                    </div>
                </div>
            </div>
            <div class="msg-meta">
                @if(!$msg->is_read)
                    <span class="badge-new">NEW</span>
                @endif
                @if($msg->staff_reply)
                    <span class="badge-replied">
                        <i class="fas fa-check me-1"></i> REPLIED
                    </span>
                @endif
                <span class="msg-time">
                    <i class="fas fa-clock me-1"></i>
                    {{ $msg->created_at->diffForHumans() }}
                </span>
            </div>
        </div>

        {{-- Guest Message --}}
        <div class="msg-body">
            <div class="msg-label">
                <i class="fas fa-comment me-1"></i> Message
            </div>
            <div class="msg-text">{{ $msg->message }}</div>
        </div>

        {{-- Staff Reply (if exists) --}}
        @if($msg->staff_reply)
            <div class="reply-section">
                <div class="reply-header">
                    <span class="reply-label">
                        <i class="fas fa-reply me-1"></i> Your Reply
                    </span>
                    @if($msg->replied_at)
                        <span class="reply-date">
                            · {{ $msg->replied_at->format('M d, Y h:i A') }}
                        </span>
                    @endif
                </div>
                <div class="reply-text">{{ $msg->staff_reply }}</div>
            </div>
        @endif

        {{-- Reply Form --}}
        <div class="reply-form-section">
            <form action="{{ route('staff.messages.reply', $msg->id) }}" method="POST">
                @csrf
                <textarea
                    name="staff_reply"
                    class="reply-textarea"
                    placeholder="{{ $msg->staff_reply ? 'Send another reply...' : 'Type your reply...' }}"
                    required
                ></textarea>
                <button type="submit" class="btn-reply">
                    <i class="fas fa-paper-plane"></i>
                    {{ $msg->staff_reply ? 'Send Again' : 'Reply' }}
                </button>
            </form>
        </div>

    </div>
@empty
    <div class="empty-state">
        <div class="empty-icon">
            <i class="fas fa-inbox"></i>
        </div>
        <div class="empty-text">No guest messages yet.</div>
    </div>
@endforelse

{{-- Pagination --}}
<div class="pagination-wrap">
    {{ $messages->links() }}
</div>

@endsection
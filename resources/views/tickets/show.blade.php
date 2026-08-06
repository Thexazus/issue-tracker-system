@extends('layouts.app')

@section('title', 'Detail Tiket ' . $ticket->ticket_number . ' - IT Ticketing System')
@section('page_title', 'Detail Tiket')

@section('content')
<div class="mb-4 d-flex justify-content-between align-items-center">
    <a href="{{ route('tickets.index') }}" class="btn btn-sm btn-light border text-muted">
        <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar Tiket
    </a>
    
    <div class="d-flex gap-2">
        @if(Auth::user()->isAdmin() || (Auth::user()->isQA() && $ticket->reporter_id === Auth::id()) || (Auth::user()->isDeveloper() && $ticket->assigned_to_id === Auth::id()))
            <a href="{{ route('tickets.edit', $ticket) }}" class="btn btn-sm btn-outline-success">
                <i class="bi bi-pencil-square me-1"></i> Edit Tiket
            </a>
        @endif

        @if(Auth::user()->isAdmin() || (Auth::user()->isQA() && $ticket->reporter_id === Auth::id() && $ticket->status === 'open'))
            <form action="{{ route('tickets.destroy', $ticket) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus tiket ini? Tindakan ini tidak dapat dibatalkan.');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-outline-danger">
                    <i class="bi bi-trash me-1"></i> Hapus Tiket
                </button>
            </form>
        @endif
    </div>
</div>

<div class="row g-4">
    <!-- Left Column: Ticket Details & Comments -->
    <div class="col-lg-8">
        <!-- Details Card -->
        <div class="card-custom">
            <div class="d-flex flex-wrap align-items-center justify-content-between border-bottom pb-3 mb-3 gap-2">
                <div>
                    <span class="text-primary fw-bold" style="font-size: 0.9rem;">{{ $ticket->ticket_number }}</span>
                    <h3 class="fw-bold mb-0 text-dark">{{ $ticket->title }}</h3>
                </div>
                <div class="d-flex gap-2">
                    <span class="badge priority-{{ $ticket->priority }} py-2 px-3 small">{{ ucfirst($ticket->priority) }}</span>
                    <span class="badge badge-status status-{{ $ticket->status }} py-2 px-3">{{ str_replace('_', ' ', ucfirst($ticket->status)) }}</span>
                </div>
            </div>

            <!-- Meta Data Row -->
            <div class="row bg-light rounded p-3 mb-4 g-3" style="font-size: 0.9rem;">
                <div class="col-sm-6">
                    <div class="text-muted small">Dilaporkan Oleh</div>
                    <div class="fw-semibold text-dark">{{ $ticket->reporter->name }} <span class="badge bg-secondary-subtle text-secondary py-0 px-2 fw-semibold" style="font-size: 0.65rem;">{{ strtoupper($ticket->reporter->role) }}</span></div>
                    <div class="text-muted small" style="font-size: 0.8rem;">Pada: {{ $ticket->created_at->translatedFormat('d M Y, H:i') }}</div>
                </div>
                <div class="col-sm-6">
                    <div class="text-muted small">Ditugaskan Kepada</div>
                    @if($ticket->assignee)
                        <div class="d-flex align-items-center mt-1">
                            <div class="rounded-circle bg-primary-subtle text-primary fw-bold d-flex align-items-center justify-content-center me-2" style="width: 28px; height: 28px; font-size: 0.75rem;">
                                {{ substr($ticket->assignee->name, 0, 2) }}
                            </div>
                            <div class="fw-semibold text-dark">{{ $ticket->assignee->name }}</div>
                        </div>
                    @else
                        <div class="text-muted italic mt-1"><i class="bi bi-person-dash me-1"></i>Belum Ditugaskan</div>
                    @endif
                </div>
            </div>

            <!-- Description -->
            <div class="mb-4">
                <h5 class="fw-bold text-dark mb-2">Deskripsi Masalah</h5>
                <div class="text-dark p-3 border rounded bg-white" style="white-space: pre-wrap; line-height: 1.6;">{{ $ticket->description }}</div>
            </div>

            <!-- Screenshot (if uploaded) -->
            @if($ticket->screenshot)
                <div class="mb-4 border-top pt-4">
                    <h5 class="fw-bold text-dark mb-3"><i class="bi bi-image me-1"></i> Screenshot Bukti Error</h5>
                    <div class="p-2 border rounded bg-light text-center overflow-hidden" style="max-height: 500px;">
                        <img src="{{ asset('storage/' . $ticket->screenshot) }}" class="img-fluid rounded" alt="Screenshot Error" style="max-height: 480px; object-fit: contain; cursor: pointer;" data-bs-toggle="modal" data-bs-target="#imageModal">
                    </div>
                </div>
            @endif
        </div>

        <!-- Comments Section -->
        <div class="card-custom">
            <h5 class="fw-bold text-dark mb-4"><i class="bi bi-chat-left-text me-2"></i>Diskusi & Komentar ({{ $ticket->comments->count() }})</h5>

            <!-- Comment Form -->
            <form action="{{ route('tickets.comments.store', $ticket) }}" method="POST" class="mb-4 pb-4 border-bottom">
                @csrf
                <div class="mb-3">
                    <textarea name="content" rows="3" class="form-control" placeholder="Tulis komentar atau tanggapan pengerjaan..." required></textarea>
                </div>
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-send-fill me-1"></i> Kirim Komentar</button>
                </div>
            </form>

            <!-- Comments List -->
            <div class="comments-thread">
                @forelse($ticket->comments as $comment)
                    <div class="comment-item d-flex mb-3 border-bottom pb-3">
                        <div class="rounded-circle bg-primary-subtle text-primary fw-bold d-flex align-items-center justify-content-center me-3 flex-shrink-0" style="width: 36px; height: 36px; font-size: 0.9rem;">
                            {{ substr($comment->user->name, 0, 2) }}
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex align-items-center justify-content-between mb-1">
                                <div>
                                    <span class="fw-bold text-dark me-1" style="font-size: 0.925rem;">{{ $comment->user->name }}</span>
                                    <span class="badge bg-secondary-subtle text-secondary py-0 px-2 fw-semibold" style="font-size: 0.65rem;">{{ strtoupper($comment->user->role) }}</span>
                                </div>
                                <span class="text-muted small">{{ $comment->created_at->diffForHumans() }}</span>
                            </div>
                            <div class="text-dark small" style="white-space: pre-wrap;">{{ $comment->content }}</div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-4 text-muted small">
                        <i class="bi bi-chat-square-dots fs-3 text-muted mb-2 d-block"></i>
                        Belum ada komentar. Mulai diskusi di atas!
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Right Column: Activity Logs Timeline -->
    <div class="col-lg-4">
        <div class="card-custom">
            <h5 class="fw-bold text-dark border-bottom pb-3 mb-3"><i class="bi bi-clock-history me-2"></i>Histori Aktivitas</h5>
            
            <div class="position-relative ps-3" style="border-left: 2px solid #e2e8f0; margin-left: 10px;">
                @forelse($ticket->activityLogs as $log)
                    <div class="position-relative mb-4">
                        <!-- Dot indicator -->
                        <span class="position-absolute bg-white text-primary rounded-circle border border-primary d-flex align-items-center justify-content-center" style="width: 16px; height: 16px; left: -19px; top: 4px; font-size: 0.6rem;">
                            <i class="bi bi-circle-fill" style="transform: scale(0.6);"></i>
                        </span>
                        
                        <div class="small">
                            <div class="fw-semibold text-dark">{{ $log->description }}</div>
                            <div class="text-muted small" style="font-size: 0.75rem;">{{ $log->created_at->diffForHumans() }} ({{ $log->created_at->format('d M H:i') }})</div>
                            
                            @if($log->changes)
                                <div class="bg-light p-2 rounded mt-1 text-muted" style="font-size: 0.75rem;">
                                    <strong>Perubahan:</strong> 
                                    @if(isset($log->changes['old']) || isset($log->changes['new']))
                                        <code>{{ is_array($log->changes['old']) ? json_encode($log->changes['old']) : $log->changes['old'] ?: 'Null' }}</code> 
                                        &rarr; 
                                        <code>{{ is_array($log->changes['new']) ? json_encode($log->changes['new']) : $log->changes['new'] ?: 'Null' }}</code>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="text-muted small py-3">Tidak ada catatan log aktivitas.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Screenshot Modal -->
@if($ticket->screenshot)
<div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content bg-transparent border-0">
            <div class="modal-header border-0 p-0 position-relative">
                <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-3" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0 text-center">
                <img src="{{ asset('storage/' . $ticket->screenshot) }}" class="img-fluid rounded" alt="Screenshot Error">
            </div>
        </div>
    </div>
</div>
@endif
@endsection

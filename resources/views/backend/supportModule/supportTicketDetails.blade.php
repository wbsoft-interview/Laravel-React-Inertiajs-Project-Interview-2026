@extends('backend.master')
@section('content')
@section('title') Support Ticket Details | ITDER - It Development Education & Research @endsection
@section('ticket-support') active @endsection
@section('ticket-support.index') active @endsection
@section('styles')
<style>
    .conversation-entry {
        border-bottom: 1px solid #e0e0e0;
        padding-bottom: 15px;
        margin-bottom: 15px;
    }

    .conversation-entry:last-child {
        border-bottom: none;
    }

    .modal-body .form-group {
        margin-bottom: 1rem;
    }

</style>
@endsection

@section('main_content_section')
<div class="row py-3 ps-2">
    <div class="heading d-flex justify-content-start align-items-center">
        <h3 class="mb-0"><span>Support Ticket Details</span></h3>
    </div>
</div>

<div class="table_wrapper py-1 card">
    <div class="row px-3">
        <!-- Ticket Information -->
        <div class="card mb-3">
            <div class="card-header bg-primary text-white mt-3 p-2">
                <h5 class="m-0 p-0">Ticket Information</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- Left Side -->
                    <div class="col-md-6">
                        <p><strong>Ticket ID:</strong> {{ $supportTicketDetails->id }}</p>
                        <p><strong>Ticket Number:</strong> {{ $supportTicketDetails->ticket_number }}</p>
                        <p><strong>Support Type:</strong> {{ $supportTicketDetails->support_type ?? 'N/A' }}</p>
                    </div>
                    <!-- Right Side -->
                    <div class="col-md-6">
                        <p><strong>Status:</strong> {{ $supportTicketDetails->status == 2 ? 'Closed' : 'Open' }}</p>
                        <p><strong>Created By:</strong> {{ $supportTicketDetails->ticketByData->name ?? 'Unknown' }}</p>
                        <p><strong>Created At:</strong> {{ $supportTicketDetails->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ticket Conversation -->
        <div class="card mb-3">
            <div class="card-header bg-secondary text-white mt-3 p-2 d-flex justify-content-between align-items-center">
                <h5 class="m-0 p-0">Ticket Conversation</h5>
                <!-- Reply Button -->
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                    data-bs-target="#replyModal" {{ $supportTicketDetails->status == 2 ? 'disabled' : '' }}>
                    <i class="fas fa-reply me-1"></i> {{ $supportTicketDetails->status == 2 ? 'Closed' : 'Reply' }}
                </button>
            </div>
            <div class="card-body">
                @forelse($supportTicketDetails->supportTicketDetailData as $detail)
                <div class="conversation-entry">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div class="d-flex align-items-center">
                            <!-- Profile Avatar Icon -->
                            <i class="fas fa-user-circle fa-2x me-2 text-muted"></i>
                            <!-- User Info -->
                            @if($detail->ticket_by_id != null)
                            <div>
                                <strong>{{ Str::title($detail->ticketByData->name ?? 'Unknown User') }}</strong>
                                <div>
                                    <small class="text-muted">{{ $detail->ticketByData->email ?? 'N/A' }}</small>
                                </div>
                            </div>
                            @else
                            <div>
                                <strong>{{ Str::title($detail->ticketReplyData->name ?? 'Unknown User') }}</strong>
                                <div>
                                    <small class="text-muted">{{ $detail->ticketReplyData->email ?? 'N/A' }}</small>
                                </div>
                            </div>
                            @endif
                        </div>
                        <!-- Timestamp -->
                        <small class="text-muted">{{ $detail->created_at->diffForHumans() }}</small>
                    </div>
                    <div class="bg-light p-3 rounded">
                        <p><strong>Subject:</strong> {{ $detail->subject ?? 'N/A' }}</p>
                        <strong>Details:</strong> {!! $detail->details ?? 'No details provided.' !!}
                        @if($detail->image)
                        <p><strong>Attachment:</strong></p>
                        <a href="{{ $detail->image ? Storage::url('uploads/supportFile/' . $detail->image) : asset('backend/template-assets/images/img_preview.png') }}" target="_blank">
                            <img src="{{ $detail->image ? Storage::url('uploads/supportFile/' . $detail->image) : asset('backend/template-assets/images/img_preview.png') }}" alt="Attachment" class="img-fluid"
                                style="max-width: 300px;">
                        </a>
                        @else
                        <p><strong>Attachment:</strong> 
                            @if($detail->image != null)
                            <a href="{{$detail->image}}" data-rel="lightcase">
                                <img src="{{$detail->image}}" height="100" width="100" alt="">
                            </a>
                            @endif
                        @endif
                    </div>
                </div>
                @empty
                <p>No conversation details available for this ticket.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Reply Modal -->
<div class="modal fade" id="replyModal" tabindex="-1" aria-labelledby="replyModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="replyModalLabel">Reply to Support Ticket
                    #{{ $supportTicketDetails->ticket_number }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('ticket-support-reply', $supportTicketDetails->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="details" class="form-label">Details <span class="text-danger">*</span></label>
                        <textarea class="form-control summernote" id="details" name="details"></textarea>
                        <span id="warnTextDesc2" class="text-danger warn d-none">Details is required</span>
                        @error('details')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Submit Reply</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
            $('.summernote').summernote({
                placeholder: 'Details',
                height: 200,
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'italic', 'underline', 'clear']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['insert', ['link', 'picture', 'video']],
                    ['view', ['fullscreen', 'codeview', 'help']]
                ]
            });

            // Client-side validation
            $('form').on('submit', function(e) {
                var details = $('#details').summernote('code');
                if (details === '' || details === '<p><br></p>') {
                    e.preventDefault();
                    $('#warnTextDesc2').removeClass('d-none');
                } else {
                    $('#warnTextDesc2').addClass('d-none');
                }
            });
        });
</script>
@endsection
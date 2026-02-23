@extends('admin.master')
@section('title') Notification | Beauty Parlour @endsection
@section('push-notification') active @endsection
@section('push-notification.create') active @endsection
@section('styles')
@endsection


@section('main_content_section')
<div class="row py-3 ps-2">
    <div class="heading d-flex justify-content-start align-items-center">
        <h3 class="mb-0"><span> Timely Notification Create</span></h3>
    </div>
</div>

<!-- BEGIN: Content-->
<div class="app-content content ">
    <div class="card">
        <div class="card-body">
            <form action="{{route('push-notification.store')}}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                    <div class="row">

                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label for="sending_date">Date <span class=" text-danger">(required)</span></label>
                                <input type="text" class=" form-control flatpickr-basic custom-date-picker" name="sending_date" id="sending_date"
                                    placeholder="DD-MM-YYYY" required>
                            </div>
                        
                            @error('sending_date')
                            <span class=text-danger>{{$message}}</span>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label for="sending_date">Time <span class=" text-danger">(required)</span></label>
                                <input type="text" class=" form-control flatpickr-time custom-time-picker" name="sending_time" id="sending_time"
                                    placeholder="HH:MM" required>
                            </div>
                        
                            @error('sending_time')
                            <span class=text-danger>{{$message}}</span>
                            @enderror
                        </div>
                        
                        <div class="col-md-12 mb-3">
                            <div class="form-group">
                                <label for="name">Title <span class=" text-danger">(required)</span></label>
                                <input type="text" class=" form-control" name="notification_title"
                                    id="notification_title" placeholder="Title" required>
                            </div>

                            @error('notification_title')
                            <span class=text-danger>{{$message}}</span>
                            @enderror
                        </div>

                        <div class="col-md-12 mb-3">
                            <div class="form-group">
                                <label for="name">Message <span class=" text-danger">(required)</span></label>
                                <textarea class=" form-control" name="notification_message" id="notification_message"
                                    cols="20" rows="5" placeholder="Message"></textarea>
                                <span id="warnTextDesc2" class="text-danger warn d-none">Message is required</span>
                            </div>

                            @error('notification_message')
                            <span class=text-danger>{{$message}}</span>
                            @enderror
                        </div>

                        <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12" style="margin-top: 15px;">
                            <button type="submit" class="btn btn btn-success m-0 py-2 text-right"
                                onclick="return validate()">Save</button>
                        </div>

                    </div>

                </div>
            </form>
        </div>

    </div>

</div>
<!-- END: Content-->
@endsection

@section('scripts')
<!-- tinymce text editor -->
<script src="{{ asset('backend/template-assets/js/tinymce.min.js') }}"></script>
<script src="{{ asset('backend/template-assets/js/custom_tinymce.js') }}"></script>

<script>
    $("#sending_date").flatpickr({
		allowInput: true,
        dateFormat: "d-m-Y",
	});

	$("#sending_time").flatpickr({
		enableTime: true,
		noCalendar: true,
		allowInput: true
	});

    //To check validation...
    function validate(){
        if ((tinymce.EditorManager.get('notification_message').getContent()) == '') {
            document.getElementById('warnTextDesc2').classList.add('active')
            return false;
        }
    };
</script>

<script>
    var editor_config = {
            path_absolute : "/",
            selector: "textarea",
            plugins: [
                "advlist autolink lists link image charmap print preview hr anchor pagebreak",
                "searchreplace wordcount visualblocks visualchars code fullscreen",
                "insertdatetime media nonbreaking save table contextmenu directionality",
                "emoticons template paste textcolor colorpicker textpattern"
            ],
            toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media",
            relative_urls: false,
            file_browser_callback : function(field_name, url, type, win) {
                var x = window.innerWidth || document.documentElement.clientWidth || document.getElementsByTagName('body')[0].clientWidth;
                var y = window.innerHeight|| document.documentElement.clientHeight|| document.getElementsByTagName('body')[0].clientHeight;

                var cmsURL = editor_config.path_absolute + 'laravel-filemanager?field_name=' + field_name;
                if (type == 'image') {
                    cmsURL = cmsURL + "&type=Images";
                } else {
                    cmsURL = cmsURL + "&type=Files";
                }

                tinyMCE.activeEditor.windowManager.open({
                    file : cmsURL,
                    title : 'Filemanager',
                    width : x * 0.8,
                    height : y * 0.8,
                    resizable : "yes",
                    close_previous : "no"
                });
            }
        };

        tinymce.init(editor_config);
</script>
@endsection
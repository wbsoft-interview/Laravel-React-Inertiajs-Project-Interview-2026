<div class="footer_text my-3">
    <div class="container-fluid">
        @php
        //To get current user...
        $userId = App\Helpers\CurrentUser::getOwnerId();
        //To get single footer text data...
        $getSingleFooterTextData = App\Models\FooterText::where('user_id',$userId)->first();
        @endphp
        <div class="footer d-md-flex align-items-center justify-content-between">
        <p class="mb-0"><em> {{$getSingleFooterTextData != null ? $getSingleFooterTextData->solid_text : ''}}<a href="#" class="text-primary"></a></em></p> 
        <p class="mb-0"><em>Developed By <a href="http://wbsoftwares.com/">WB Softwares</a></em></p>
        </div>
    </div>
</div>
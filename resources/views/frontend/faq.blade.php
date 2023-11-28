@extends('frontend.layouts', ['activeMenu' => 'FAQ', 'activeSubMenu' => '', 'title' => 'Frequesntly Asked Questions'])
@section('content')

<!--begin::Card-->
<div class="card mt-5">     
    <!--begin::Body-->
    <div class="card-body p-lg-15">
        <!--begin::Layout-->
        <div class="d-flex flex-column flex-lg-row">

            <!--begin::Content-->
            <div class="flex-lg-row-fluid">
                <!--begin::Extended content-->
                <div class="mb-13">
                    <!--begin::Content head-->
                    <div class="mb-15">
                        <!--begin::Title-->
                        <h4 class="fs-2x text-gray-800 w-bolder mb-6">                           
                            Frequesntly Asked Questions              
                        </h4>
                        <!--end::Title-->
                        <div class="separator separator-dashed"></div>
                    </div>    
                    <!--end::Content head-->            

                    <!--begin::Accordion-->
                    <div class="mb-15" id="sectionFaq">
                        {{-- <!--begin::Section-->
                        <div class="m-0">
                            <!--begin::Heading-->
                            <div class="d-flex align-items-center collapsible py-3 toggle mb-0 collapsed" data-bs-toggle="collapse" data-bs-target="#kt_job_8_1" aria-expanded="false">
                                <!--begin::Icon-->
                                <div class="btn btn-sm btn-icon mw-20px btn-active-color-primary me-5">
                                    <i class="bi bi-dash-square toggle-on text-primary fs-1"></i>                
                                    <i class="bi bi-plus-square toggle-off fs-1"><span class="path3"></span></i> 
                                </div>
                                <!--end::Icon-->
                                <!--begin::Title-->
                                <h4 class="text-gray-700 fw-bold cursor-pointer mb-0">                           
                                    How does it work?                                
                                </h4>
                                <!--end::Title-->
                            </div>
                            <!--end::Heading-->  
                            <!--begin::Body-->
                            <div id="kt_job_8_1" class="fs-6 ms-1 collapse" style="">
                                <!--begin::Text-->
                                <div class="mb-4 text-gray-600 fw-semibold fs-6 ps-10">
                                    First, a disclaimer – the entire process of writing a blog post often takes more than a couple of hours, even if you can type eighty words as per minute and your writing skills are sharp.                
                                </div>
                                <!--end::Text-->
                            </div>                
                            <!--end::Content-->
                            <!--begin::Separator-->
                            <div class="separator separator-dashed"></div>
                            <!--end::Separator-->
                        </div>
                        <!--end::Section-->
                        <!--begin::Section-->
                        <div class="m-0">
                            <!--begin::Heading-->
                            <div class="d-flex align-items-center collapsible py-3 toggle collapsed mb-0" data-bs-toggle="collapse" data-bs-target="#kt_job_8_2">
                                <!--begin::Icon-->
                                <div class="btn btn-sm btn-icon mw-20px btn-active-color-primary me-5">
                                    <i class="bi bi-dash-square toggle-on text-primary fs-1"></i>                
                                    <i class="bi bi-plus-square toggle-off fs-1"><span class="path3"></span></i> 
                                </div>
                                <!--end::Icon-->
                                <!--begin::Title-->
                                <h4 class="text-gray-700 fw-bold cursor-pointer mb-0">                           
                                    Do I need a designer to use this Admin Theme ?                                
                                </h4>
                                <!--end::Title-->
                            </div>
                            <!--end::Heading-->  
                            <!--begin::Body-->
                            <div id="kt_job_8_2" class="collapse  fs-6 ms-1">
                                <!--begin::Text-->
                                <div class="mb-4 text-gray-600 fw-semibold fs-6 ps-10">
                                    First, a disclaimer – the entire process of writing a blog post often takes more than a couple of hours, even if you can type eighty words as per minute and your writing skills are sharp.                
                                </div>
                                <!--end::Text-->
                            </div>                
                            <!--end::Content-->

                            <!--begin::Separator-->
                            <div class="separator separator-dashed"></div>
                            <!--end::Separator-->
                        </div>
                        <!--end::Section--> --}}
                    </div>
                    <!--end::Accordion-->  
                    
                </div>
                <!--end::Extended content-->                
            </div>  
            <!--end::Content-->   

        </div>
        <!--end::Layout-->
    </div>
    <!--end::Body-->
</div>
<!--begin::End-->

@section('js')
<script src="{{ asset('/script/frontend/faq.js') }}"></script>
@stop
@endsection
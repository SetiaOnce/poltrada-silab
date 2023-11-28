@extends('frontend.layouts', ['activeMenu' => 'PROFILE', 'activeSubMenu' => '', 'title' => 'Profile Perpustakaan'])
@section('content')
<div class="py-5">
    <div class="card">
        <div class="rbt-event-area rbt-section-gap bg-gradient-5 pt--50">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 mb-5 mb-md-0">
                        <div class="blog-content-wrapper blog-content-detail rbt-article-content-wrapper pt--50 rounded">
                            <div class="content p-6 mt-4" id="sectionProfile">
                                <ul class="text-center">
                                    <h1 class="placeholder-glow my-0">
                                        <span class="placeholder col-lg-6 col-12 rounded"></span>
                                    </h1>
                                </ul>
                                <div class="mt--10 mb--50 text-start">
                                    <h5 class="card-title placeholder-glow">
                                        <span class="placeholder col-12"></span>
                                      </h5>
                                      <p class="card-text placeholder-glow">
                                        <span class="placeholder col-12"></span>
                                        <span class="placeholder col-10"></span>
                                        <span class="placeholder col-8"></span>
                                        <span class="placeholder col-10"></span>
                                        <span class="placeholder col-12"></span>
                                      </p>
                                </div>
                            </div>
                        </div>
                    </div>
        
                </div>
            </div>
        </div>
    </div>
</div>
@section('js')
<script src="{{ asset('/script/frontend/profile.js') }}"></script>
@stop
@endsection
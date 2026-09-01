{{--
    Portal shell — two-column layout shared by every admin + tenant screen.
    Screens set:
      @section('portal_class', 'wf-portal--dark')   dark theme (super admin)
      @section('sidebar')  ...  @include('includes.admin_sidebar')  etc.
      @section('topbar')   ...  @include('includes.admin_header')   etc.
      @section('page')     ...  the actual screen content
--}}
@extends('layouts.master')

@section('content')
    <div class="wf-portal @yield('portal_class')">

        @yield('sidebar')

        <div class="wf-portal__main">
            @yield('topbar')

            <div class="wf-portal__body">
                @yield('page')
            </div>
        </div>

    </div>

    @yield('modals')
@endsection

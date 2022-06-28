@extends('layouts.backend')

@section('title', __('string.Dashboard'))

@section('content')
<main>
  <div class="mb-4 bg-white p-4 shadow sm:rounded-md">
  @role('admin')
    <div class="flex flex-wrap">
      <div class="w-full sm:w-1/4">
        <span class="text-cyan-600"> @svg('icon-square', 'mr-2') {{__('string.All')}}</span>
        <span class="text-teal-600 ml-5"> @svg('icon-square', 'mr-2') {{__('string.Me')}}</span>
        <span class="text-orange-600 ml-5"> @svg('icon-square', 'mr-2') {{__('string.Guests')}}</span>
      </div>
      <div class="mt-8 sm:mt-0 text-uh-1 ">
        <b>@svg('icon-storage', 'mr-1.5') {{__('string.Free_Space')}}:</b>
        <span class="font-light">{{numberToAmountShort($keyRemaining)}} {{__('string.of')}} {{numberToAmountShort($keyCapacity)}} ({{$keyRemaining_Percent}})</span>
      </div>
    </div>

    <div class="flex flex-wrap sm:mt-8">
      <div class="w-full sm:w-1/4">
        <div class="block">
          <b class="text-uh-1">@svg('icon-link', 'mr-1.5') {{__('string.URLs')}}:</b>
          <span class="text-cyan-600">{{numberToAmountShort($totalUrl)}}</span> -
          <span class="text-teal-600">{{numberToAmountShort($urlCount_Me)}}</span> -
          <span class="text-orange-600">{{numberToAmountShort($urlCount_Guest)}}</span>
        </div>
        <div class="block">
          <b class="text-uh-1">@svg('icon-bar-chart', 'mr-1.5') {{__('string.Clicks')}}:</b>
          <span class="text-cyan-600">{{numberToAmountShort($totalClick)}}</span> -
          <span class="text-teal-600">{{numberToAmountShort($clickCount_Me)}}</span> -
          <span class="text-orange-600">{{numberToAmountShort($clickCount_Guest)}}</span>
        </div>
      </div>
      <div class="text-uh-1 w-full sm:w-1/4 mt-4 sm:mt-0">
        <div class="block">
          <b>@svg('icon-user', 'mr-1.5') {{__('string.Users')}}:</b>
          <span class="font-light">{{numberToAmountShort($userCount)}}</span>
        </div>
        <div class="block">
          <b>@svg('icon-user', 'mr-1.5') {{__('string.Guests')}}:</b>
          <span class="font-light">{{numberToAmountShort($guestCount)}}</span>
        </div>
      </div>
    </div>
  @else
    <div class="flex flex-wrap">
      <div class="w-full sm:w-1/4">
        <span class="font-semibold text-md sm:text-2xl">@svg('icon-link', 'mr-1.5') {{__('string.URLs')}}:</span>
        <span class="font-light text-lg sm:text-2xl">{{numberToAmountShort($urlCount_Me)}}</span>
      </div>
      <div class="w-full sm:w-1/4">
        <span class="font-semibold text-lg sm:text-2xl">@svg('icon-eye', 'mr-1.5') {{__('string.Clicks')}}:</span>
        <span class="font-light text-lg sm:text-2xl">{{numberToAmountShort($clickCount_Me)}}</span>
      </div>
    </div>
  @endrole
  </div>

  <div class="bg-white p-4 shadow sm:rounded-md">
    <div class="flex mb-8">
      <div class="w-1/2">
        <span class="text-2xl text-uh-1">{{__('string.My_URLs')}}</span>
      </div>
      <div class="w-1/2 text-right">
        <a href="{{ url('./') }}" target="_blank" title="{{__('string.Add_URL')}}"
          class="btn"
        >
          @svg('icon-add-link', '!h-[1.5em] mr-1')
          {{__('string.Add_URL')}}
        </a>
      </div>
    </div>

    @include('partials/messages')

    @livewire('my-url-table')
  </div>
</main>
@endsection

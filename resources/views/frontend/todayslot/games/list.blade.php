@extends('frontend.todayslot.layouts.app')
@section('page-title', $title)


<script type='text/javascript'>

	@if((!(isset ($errors) && count($errors) > 0) && !Session::get('success', false) && Auth::check()))
		var loginYN='Y';
		var currentBalance = {{ Auth::user()->balance }};
		var userName = "{{ Auth::user()->username }}";
		var accountName = "{{ Auth::user()->recommender }}";
		var bankName = "{{ Auth::user()->bank_name }}";
		var account_no = "{{ Auth::user()->account_no }}";
		<?php
			$user = auth()->user();
			while ($user && !$user->isInoutPartner())
			{
				$user = $user->referral;
			}
			$telegram = '';
			if ($user)
			{
				$telegram = $user->address;
			}
		?>
		var telegram_id = "{{$telegram}}";
	@else
		var loginYN='N';
		var currentBalance = 0;
		var userName = "";
	@endif


</script>
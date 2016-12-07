{{--

	@{{  }} -> AngularJS
	{{  }}  -> Laravel template

--}}
<div class="error-wrapper animate-slide-down" ng-if="app.error.message!=''">
	<div class="error">
		@{{ app.error.message }} <span ng-if="app.error.code!=0">(HTTP error code: @{{ app.error.code }})</span>
	</div>
</div>

<div class="game animate-fade" id="game" ng-show="app.ready()">
	<div class="left-side" id="left-side" ng-controller="DashboardController">

		<div class="user">
			<p class="username">{{{ Auth::user()->username }}} <span class="logout"><a href="{{ route('logout') }}"></a></span></p>
			<p class="rank">Rank {{{ Auth::user()->rank }}}</p>
		</div>

		<div score class="score">
			<h2>0</h2>
		</div>

		<div timer on-timeout="endGame()" class="timer-container"></div>

		<div class="animate-fade-in-down" d-modal ng-hide="modal.hidden" md-title="modal.title" md-content="modal.content" md-validate-button="modal.validate" md-canceled-button="modal.canceled"></div>

	</div>

	<div class="right-side" id="right-side" ng-controller="GridController">

		<div class="box" box box-index="@{{$index}}" id="@{{box.id}}" ng-repeat="box in grid.boxes" ng-class="'type-' + box.type"></div>

	</div>

</div>

<div spinner-loading ng-hide="app.ready()"></div>
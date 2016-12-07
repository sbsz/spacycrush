<div id="myModal" class="modal fade in" tabindex="-1" role="dialog" style="display: block;">
	<div class="modal-dialog">
		<div class="modal-content">

			<div class="modal-header">
				<h4 class="modal-title" id="myModalLabel">{{ title }}</h4>
			</div>
			<div class="modal-body" ng-bind-html="content"></div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" ng-click="canceled()">{{ canceledButton.title }}</button>
				<button type="button" class="btn btn-primary" ng-click="validate()">{{ validateButton.title }}</button>
			</div>

		</div>
	</div>
</div>
<table class="table ranking">
	<thead>
		<tr>
			<th>Rank</th>
			<th>Username</th>
			<th>Bestscore</th>
		</tr>
	</thead>
	<tbody>
		@foreach($users as $user)
		<tr @if($user['id'] == $currentUserId ) {{ 'class="active"' }} @endif>
			<td>{{{ $user['rank'] }}}</td>
			<td>{{{ $user['username'] }}}</td>
			<td>{{{ $user['bestScore'] }}}</td>
		</tr>
		@endforeach
	</tbody>
</table>
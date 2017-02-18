<?php foreach ($flashMessages as $type => $messages): ?>
	<?php foreach ($messages as $message): ?>
        <div class="alert alert-{{ $type }} alert-dismissable">
          <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
          <strong>{{ strtoupper($type) }}!</strong> {{ $message }}.
        </div>
    <?php endforeach; ?>
<?php endforeach; ?>
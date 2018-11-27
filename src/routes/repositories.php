<?php
$app->group('/repositories', function () {
  $this->get('/search/{repository-name}', RepositoriesController::class);
});

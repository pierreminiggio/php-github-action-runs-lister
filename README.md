Install using composer :
```
composer require pierreminiggio/github-action-runs-lister
```

```php
use PierreMiniggio\GithubActionRunsLister\GithubActionRunsLister;

require __DIR__ . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

$lister = new GithubActionRunsLister();
$list = $lister->list(
    'pierreminiggio',
    'remotion-test-github-action',
    'render-video.yml'
);

var_dump($list);
```
